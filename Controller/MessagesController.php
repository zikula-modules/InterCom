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

                return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages'));
            }
            if ($form->get('send')->isClicked()) {
                $managedMessage->send();
                $this->addFlash('status', $this->__('Message sent.'));

                return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages'));
            }
        }

        $layout = ucfirst($this->getVar('layout'));

        return $this->render("@ZikulaIntercomModule/Layouts/$layout/new.html.twig", [
            'form'               => $form->createView(),
            'managedMessage'     => $managedMessage,
            'settings'           => $this->getVars(),
            'currentUserManager' => $currentUserManager,
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

        $form = $this->createForm(ReplyType::class, $managedMessage->getReplyPrepared(), ['isXmlHttpRequest' => $request->isXmlHttpRequest()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $managedMessage->setMessage($form->getData());
            if ($form->get('preview')->isClicked()) {
                $managedMessage->prepareForPreview();
            }
            if ($form->get('saveAsDraft')->isClicked()) {
                $managedMessage->saveAsDraft();
                $this->addFlash('status', $this->__('Reply saved.'));

                return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages'));
            }
            if ($form->get('send')->isClicked()) {
                $managedMessage->send();
                $this->addFlash('status', $this->__('Reply sent.'));

                return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages'));
            }
        }

        $layout = ucfirst($this->getVar('layout'));

        return $this->render("@ZikulaIntercomModule/Layouts/$layout/reply.html.twig", [
            'form'               => $form->createView(),
            'managedMessage'     => $managedMessage,
            'settings'           => $this->getVars(),
            'currentUserManager' => $currentUserManager,
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

        $form = $this->createForm(ReplyType::class, $managedMessage->getReplyPrepared(), ['isXmlHttpRequest' => $request->isXmlHttpRequest()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $managedMessage->setMessage($form->getData());
            if ($form->get('preview')->isClicked()) {
                $managedMessage->prepareForPreview();
            }
            if ($form->get('saveAsDraft')->isClicked()) {
                $managedMessage->saveAsDraft();
                $this->addFlash('status', $this->__('Reply saved.'));

                return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages'));
            }
            if ($form->get('send')->isClicked()) {
                $managedMessage->send();
                $this->addFlash('status', $this->__('Reply sent.'));

                return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages'));
            }
        }

        $layout = ucfirst($this->getVar('layout'));

        return $this->render("@ZikulaIntercomModule/Layouts/$layout/reply.html.twig", [
            'form'               => $form->createView(),
            'managedMessage'     => $managedMessage,
            'settings'           => $this->getVars(),
            'currentUserManager' => $currentUserManager,
        ]);
    }

    /**
     * @Route("/{box}/{label}/{page}/{sortby}/{sortorder}/{limit}", options={"expose"=true}, requirements={"page" = "\d*"}, defaults={"box" = "inbox", "label" = ".*", "page" = 1,"sortby" = "send", "sortorder" = "DESC", "limit" = 10})
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
        $filter = ['page' => $page,
            'limit'       => $limit > 0 ? $limit : $this->getVar('messages_perpage'),
            'sortorder'   => $sortorder,
            'sortby'      => $sortby,
            'label'       => $label,
        ];
        $layout = ucfirst($this->getVar('layout'));
        $messenger = $this->get('zikula_intercom_module.messenger')->load($box, $filter);
        $messenger->loadUserData();
        if ($request->isXmlHttpRequest()) {
            //@todo decode request content type - supply html or json
//            $response = new JsonResponse();
//            $response->setData([
//                'filter' => $filter,
//                'pager' => $messenger->getPager(),
//                'html' => $this->renderView("@ZikulaIntercomModule/Layouts/$layout/$box/conversation.list.html.twig", [
//                    'messages' => $messenger->getmessages()
//                ])
//            ]);

//            return $response;
        }

        return $this->render("@ZikulaIntercomModule/Layouts/$layout/index.html.twig", [
            'box'                => $box,
            'filter'             => $filter,
            'pager'              => $messenger->getPager(),
            'messages'           => $messenger->getmessages(),
            'settings'           => $this->getVars(),
            'currentUserManager' => $currentUserManager,
        ]);
    }
}
