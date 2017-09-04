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

/**
 * @Route("/messages")
 */
class MessagesController extends AbstractController
{
    /**
     * @Route("/preferences")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function preferencesAction(Request $request)
    {
        // Permission check
        if (!$this->get('zikula_intercom_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $currentUserManager = $this->get('zikula_intercom_module.user_manager')->getManager();
//        $form = $this->createFormBuilder($this->getVars())
//                //general settings
//                ->add('ic_note', 'choice', array('choices' => array('0' => $this->__('Off'), '1' => $this->__('On')),
//                    'multiple' => false,
//                    'expanded' => true,
//                    'required' => true))
//                ->add('ic_ar', 'choice', array('choices' => array('0' => $this->__('Off'), '1' => $this->__('On')),
//                    'multiple' => false,
//                    'expanded' => true,
//                    'required' => true))
//                ->add('ic_art', 'textarea', array('required' => false))
//                ->add('save', 'submit')
//                ->add('cancel', 'submit')
//                ->getForm();
//
//        $form->handleRequest($request);
//        if ($form->isValid()) {
//            if ($form->get('save')->isClicked()) {
//                $this->setVars('ZikulaIntercomModule', $form->getData());
//                $this->addFlash('status', $this->__('Done! preferences updated.'));
//            }
//            if ($form->get('cancel')->isClicked()) {
//                $this->addFlash('status', $this->__('Operation cancelled.'));
//            }
//            return $this->redirect($this->generateUrl('zikulaintercommodule_user_preferences'));
//        }

        return $this->render('ZikulaIntercomModule:User:preferences.html.twig', [
//                    'form' => $form->createView(),
//                    'modvars' => $this->getVars() // @todo temporary solution
            'currentUserManager' => $currentUserManager,
        ]);
    }

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
        $layout = ucfirst($this->getVar('layout'));

//        $options = ['isXmlHttpRequest' => $request->isXmlHttpRequest()];
//        $message = $this->get('zikula_intercom_module.manager.message')->create();
//        $form = $this->createForm('messageform', new MessageEntity(), $options);
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {
//            $this->get('zikula_intercom_module.manager.message')->setNewData($form->getData());
//            $this->get('zikula_intercom_module.manager.message')->send();
//            $message = $form->getData();
//            $em = $this->get('doctrine')->getManager();
//            $em->persist($message);
//            $em->flush();
//            if ($request->isXmlHttpRequest()) {
//                return new JsonResponse(array('status' => true));
//            } else {
//                $this->addFlash('status', "Message sent!");
//                return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages'));
//            }
//        }
//
//        if ($request->isXmlHttpRequest()) {
//            return new JsonResponse(array('status' => true, 'html' => $this->renderView('ZikulaIntercomModule:Message:form.html.twig', array(
//                    'form' => $form->createView(),
//                    'message' => $message,
//                    'settings' => $this->getVars()
//            ))));
//        }

        return $this->render("@ZikulaIntercomModule/Layouts/$layout/new.html.twig", [
//                    'form' => $form->createView(),
////                    'message' => $message,
//                    'settings' => $this->getVars()
            'currentUserManager' => $currentUserManager,
        ]);
    }

    /**
     * @Route("/reply" , options={"expose"=true})
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     *
     * @return Response symfony response object
     */
    public function replyMessageAction(Request $request)
    {
        // Permission check
        if (!$this->get('zikula_intercom_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }
//        $options = ['isXmlHttpRequest' => $request->isXmlHttpRequest()];
//        $message = $this->get('zikula_intercom_module.manager.message')->create();
//        $form = $this->createForm('messageform', new MessageEntity(), $options);
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {
//            $this->get('zikula_intercom_module.manager.message')->setNewData($form->getData());
//            $this->get('zikula_intercom_module.manager.message')->send();
//            $message = $form->getData();
//            $em = $this->get('doctrine')->getManager();
//            $em->persist($message);
//            $em->flush();
//            if ($request->isXmlHttpRequest()) {
//                return new JsonResponse(['status' => true]);
//            } else {
//                $this->addFlash('status', "Message sent!");
//                return $this->redirect($this->generateUrl('zikulaintercommodule_messages_getmessages'));
//            }
//        }
//
//        if ($request->isXmlHttpRequest()) {
//            return new JsonResponse(['status' => true, 'html' => $this->renderView('ZikulaIntercomModule:Message:form.html.twig', [
//                    'form' => $form->createView(),
//                    'message' => $message,
//                    'settings' => $this->getVars()
//            ])]);
//        }

        return $this->render('ZikulaIntercomModule:Message:new.html.twig', [
//                    'form' => $form->createView(),
//                    'message' => $message,
//                    'settings' => $this->getVars()
        ]);
    }

    /**
     * @Route("/{box}/{page}/{sortby}/{sortorder}/{limit}", options={"expose"=true}, requirements={"page" = "\d*"}, defaults={"box" = "inbox", "page" = 1,"sortby" = "send", "sortorder" = "DESC", "limit" = 10})
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     *
     * @return Response symfony response object
     */
    public function getMessagesAction(Request $request, $box, $page, $sortby, $sortorder, $limit)
    {
        // Permission check
        if (!$this->get('zikula_intercom_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

//        $mode = $this->getVar('mode');

        $filter = ['page' => $page,
            'limit'       => $limit > 0 ? $limit : $this->getVar('messages_perpage'),
            'sortorder'   => $sortorder,
            'sortby'      => $sortby,
        ];
//
//        $messages = $this->get('zikula_intercom_module.manager.messages')->load($box, $filter);
//
//        if ($request->isXmlHttpRequest()) {
//            //@todo decode request content type - supply html or json
//            $response = new JsonResponse();
//            $response->setData([
//                'filter' => $filter,
//                'pager' => $messages->getPager(),
//                'html' => $this->renderView("@ZikulaIntercomModule/Layouts/$layout/$box/conversation.list.html.twig", [
//                    'messages' => $messages->getmessages()
//                ])
//            ]);
//
//            return $response;
//        }

        $layout = ucfirst($this->getVar('layout'));

        return $this->render("@ZikulaIntercomModule/Layouts/$layout/index.html.twig", [
//                    'mode' => $mode,
//                    'layout' => $layout,
            'box'    => $box,
            'filter' => $filter,
//                    'pager' => $messages->getPager(),
//                    'messages' => $messages->getmessages(),
//                    'settings' => $this->getVars()
        ]);
    }
}
