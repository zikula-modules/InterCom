<?php

/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

namespace Zikula\IntercomModule\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // used in annotations - do not remove
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Core\Controller\AbstractController;
use Zikula\IntercomModule\Form\Type\ForwardType;
use Zikula\IntercomModule\Form\Type\MessageType;
use Zikula\IntercomModule\Form\Type\ReplyType;

/**
 * @Route("/messages")
 */
class MessagesController extends AbstractController
{
    /**
     * @Route("/new" , options={"expose"=true})
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     *
     * @return Response symfony response object
     */
    public function newMessageAction(Request $request)
    {
        // Permission check
        if (!$this->get('zikula_intercom_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $currentUserManager = $this->get('zikula_intercom_module.user_manager')->getManager();
        $managedMessage = $this->get('zikula_intercom_module.message_manager')->getManager();
        $form = $this->createForm(MessageType::class, $managedMessage->getNewMessage(), ['isXmlHttpRequest' => $request->isXmlHttpRequest()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $managedMessage->setMessage($form->getData());
            if ($form->get('preview')->isClicked()) {
                $managedMessage->prepareForPreview();
            }
            if ($form->get('saveAsDraft')->isClicked()) {
                $managedMessage->saveAsDraft();
                $this->addFlash('status', $this->__('Message saved as draft.'));

                return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages', ['box' => 'draft']));
            }
            if ($form->get('send')->isClicked()) {
                $managedMessage->send();
                $this->addFlash('status', $this->__('Message sent.'));

                return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages', ['box' => 'sent']));
            }
        }

        $layout = ucfirst($this->getVar('layout'));

        return $this->render("@ZikulaIntercomModule/Layouts/$layout/new.html.twig", [
            'form'               => $form->createView(),
            'managedMessage'     => $managedMessage,
            'settings'           => $this->getVars(),
            'currentUserManager' => $currentUserManager,
            'labelsHelper'       => $this->get('zikula_intercom_module.labels_helper'),
        ]);
    }

    /**
     * @Route("/read/{message}", options={"expose"=true}, requirements={"message" = "\d*"})
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     *
     * @return Response symfony response object
     */
    public function readMessageAction(Request $request, $message)
    {
        // Permission check
        if (!$this->get('zikula_intercom_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $currentUserManager = $this->get('zikula_intercom_module.user_manager')->getManager();
        $managedMessage = $this->get('zikula_intercom_module.message_manager')->getManager($message);
        $managedMessage->setSeen();
        $managedMessage->get()->getMessageDataByUser($currentUserManager->get());
        if (!$managedMessage->exists()) {
            $this->addFlash('error', $this->__('Message does not exists!'));

            return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages'));
        }

        $managedReplyMessage = $this->get('zikula_intercom_module.message_manager')->getManager($managedMessage->getReplyPrepared());
        $form = $this->createForm(ReplyType::class, $managedReplyMessage->get(), ['isXmlHttpRequest' => $request->isXmlHttpRequest()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $managedReplyMessage->setMessage($form->getData());
            if ($form->get('preview')->isClicked()) {
                $managedReplyMessage->prepareForPreview();
            }
            if ($form->get('saveAsDraft')->isClicked()) {
                $managedReplyMessage->saveAsDraft();
                $this->addFlash('status', $this->__('Reply saved.'));

                return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages', ['box' => 'draft']));
            }
            if ($form->get('send')->isClicked()) {
                $managedReplyMessage->send();
                $this->addFlash('status', $this->__('Reply sent.'));

                return $this->redirect($this->generateUrl('zikulaintercommodule_messages_readmessage', ['message' => $managedMessage->getId()]));
            }
        }

        $layout = ucfirst($this->getVar('layout'));

        return $this->render("@ZikulaIntercomModule/Layouts/$layout/read.html.twig", [
            'form'               => $form->createView(),
            'managedMessage'     => $managedMessage,
            'settings'           => $this->getVars(),
            'currentUserManager' => $currentUserManager,
            'labelsHelper'       => $this->get('zikula_intercom_module.labels_helper'),
        ]);
    }

    /**
     * @Route("/reply/{message}", options={"expose"=true}, requirements={"message" = "\d*"})
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     *
     * @return Response symfony response object
     */
    public function replyMessageAction(Request $request, $message)
    {
        // Permission check
        if (!$this->get('zikula_intercom_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }
        $currentUserManager = $this->get('zikula_intercom_module.user_manager')->getManager();
        $managedMessage = $this->get('zikula_intercom_module.message_manager')->getManager($message);

        if (!$managedMessage->exists()) {
            $this->addFlash('error', $this->__('Message does not exists!'));

            return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages'));
        }

        $managedReplyMessage = $this->get('zikula_intercom_module.message_manager')->getManager($managedMessage->getReplyPrepared());
        $form = $this->createForm(ReplyType::class, $managedReplyMessage->get(), ['isXmlHttpRequest' => $request->isXmlHttpRequest()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $managedReplyMessage->setMessage($form->getData());
            if ($form->get('preview')->isClicked()) {
                $managedReplyMessage->prepareForPreview();
            }
            if ($form->get('saveAsDraft')->isClicked()) {
                $managedReplyMessage->saveAsDraft();
                $this->addFlash('status', $this->__('Reply saved.'));

                return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages', ['box' => 'draft']));
            }
            if ($form->get('send')->isClicked()) {
                $managedReplyMessage->send();
                $this->addFlash('status', $this->__('Reply sent.'));

                return $this->redirect($this->generateUrl('zikulaintercommodule_messages_readmessage', ['message' => $managedMessage->getId()]));
            }
        }

        $layout = ucfirst($this->getVar('layout'));

        return $this->render("@ZikulaIntercomModule/Layouts/$layout/reply.html.twig", [
            'form'               => $form->createView(),
            'managedMessage'     => $managedMessage,
            'settings'           => $this->getVars(),
            'currentUserManager' => $currentUserManager,
            'labelsHelper'       => $this->get('zikula_intercom_module.labels_helper'),
        ]);
    }

    /**
     * @Route("/forward/{message}", options={"expose"=true}, requirements={"message" = "\d*"})
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     *
     * @return Response symfony response object
     */
    public function forwardMessageAction(Request $request, $message)
    {
        // Permission check
        if (!$this->get('zikula_intercom_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }
        $currentUserManager = $this->get('zikula_intercom_module.user_manager')->getManager();
        $managedMessage = $this->get('zikula_intercom_module.message_manager')->getManager($message);

        if (!$managedMessage->exists()) {
            $this->addFlash('error', $this->__('Message does not exists!'));

            return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages'));
        }

        $managedForwardMessage = $this->get('zikula_intercom_module.message_manager')->getManager($managedMessage->getForwardPrepared());
        $form = $this->createForm(ForwardType::class, $managedForwardMessage->get(), ['isXmlHttpRequest' => $request->isXmlHttpRequest()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $managedForwardMessage->setMessage($form->getData());
            if ($form->get('preview')->isClicked()) {
                $managedForwardMessage->prepareForPreview();
            }
            if ($form->get('saveAsDraft')->isClicked()) {
                $managedForwardMessage->saveAsDraft();
                $this->addFlash('status', $this->__('Message saved as draft.'));

                return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages', ['box' => 'draft']));
            }
            if ($form->get('send')->isClicked()) {
                $managedForwardMessage->send();
                $this->addFlash('status', $this->__('Message forwarded.'));

                return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages', ['box' => 'sent']));
            }
        }

        $layout = ucfirst($this->getVar('layout'));

        return $this->render("@ZikulaIntercomModule/Layouts/$layout/forward.html.twig", [
            'form'               => $form->createView(),
            'managedMessage'     => $managedForwardMessage,
            'settings'           => $this->getVars(),
            'currentUserManager' => $currentUserManager,
            'labelsHelper'       => $this->get('zikula_intercom_module.labels_helper'),
        ]);
    }

    /**
     * @Route("/store/{message}", options={"expose"=true}, requirements={"message" = "\d*"})
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     *
     * @return Response symfony response object
     */
    public function storeMessageAction(Request $request, $message)
    {
        // Permission check
        if (!$this->get('zikula_intercom_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $managedMessage = $this->get('zikula_intercom_module.message_manager')->getManager($message);

        if (!$managedMessage->exists()) {
            $this->addFlash('error', $this->__('Message does not exists!'));

            return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages'));
        }

        $managedMessage->toggleStored();
        if ($managedMessage->getMessageUserDetails()->getStored()) {
            $this->addFlash('status', $this->__('Message stored.'));
        } else {
            $this->addFlash('status', $this->__('Message removed from storage.'));
        }

        return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages', ['box' => 'stored']));
    }

    /**
     * @Route("/delete/{message}", options={"expose"=true}, requirements={"message" = "\d*"})
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     *
     * @return Response symfony response object
     */
    public function deleteMessageAction(Request $request, $message)
    {
        // Permission check
        if (!$this->get('zikula_intercom_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $managedMessage = $this->get('zikula_intercom_module.message_manager')->getManager($message);

        if (!$managedMessage->exists()) {
            $this->addFlash('error', $this->__('Message does not exists!'));

            return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages'));
        }

        $managedMessage->toggleDeleted();
        if ($managedMessage->getMessageUserDetails()->getDeleted()) {
            $this->addFlash('status', $this->__('Message moved to trash.'));
        } else {
            $this->addFlash('status', $this->__('Message removed from trash.'));
        }

        return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages', ['box' => 'trash']));
    }

    /**
     * @Route("/label/{message}/{label}", options={"expose"=true}, requirements={"message" = "\d*"})
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     *
     * @return Response symfony response object
     */
    public function labelMessageAction(Request $request, $message, $label = null)
    {
        // Permission check
        if (!$this->get('zikula_intercom_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $managedMessage = $this->get('zikula_intercom_module.message_manager')->getManager($message);
        $lablesHelper = $this->get('zikula_intercom_module.labels_helper');
        if (!$managedMessage->exists()) {
            $this->addFlash('error', $this->__('Message does not exists!'));

            return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages'));
        }

        $managedMessage->setLabel($lablesHelper->getByReference($label));
        $this->addFlash('status', $this->__('Message label updated.'));

        return $this->redirect($this->generateUrl('zikulaintercommodule_messages_readmessage', ['message' => $managedMessage->getId()]));
    }

    /**
     * @Route("/{box}/{label}/{page}/{sortby}/{sortorder}/{limit}", options={"expose"=true}, requirements={"page" = "\d*"}, defaults={"box" = "inbox", "label" = ".*", "page" = 1,"sortby" = "sent", "sortorder" = "DESC", "limit" = 25})
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     *
     * @return Response symfony response object
     */
    public function getMessagesAction(Request $request, $box, $page, $sortby, $sortorder, $limit, $label = null)
    {
        // Permission check
        if (!$this->get('zikula_intercom_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $currentUserManager = $this->get('zikula_intercom_module.user_manager')->getManager();
        $filter = ['page' => $request->get('page') ? $request->get('page') : $page,
            'limit'       => $limit == 25 ? $this->getVar('perpage') : $limit,
            'sortorder'   => $sortorder,
            'sortby'      => ($sortby == 'sent' && in_array($box, ['inbox', 'sent'])) ? $sortby : 'created',
            'label'       => ($label !== '.*') ? (int) str_replace('_', '', strstr($label, '_')) : null,
        ];
        $layout = ucfirst($this->getVar('layout'));
        $messenger = $this->get('zikula_intercom_module.messenger')
            ->getMessenger($currentUserManager->get())
            ->load($box, $filter)
            ->loadUserData();
        if ($request->isXmlHttpRequest()) {
            //@todo decode request content type - supply html or json
        }
        $filter['label'] = $label;

        return $this->render("@ZikulaIntercomModule/Layouts/$layout/index.html.twig", [
            'box'                => $box,
            'filter'             => $filter,
            'pager'              => $messenger->getPager(),
            'messenger'          => $messenger,
            'settings'           => $this->getVars(),
            'currentUserManager' => $currentUserManager,
            'labelsHelper'       => $this->get('zikula_intercom_module.labels_helper'),
        ]);
    }
}
