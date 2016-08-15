<?php

/**
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @subpackage User
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */

namespace Zikula\IntercomModule\Controller;

use Zikula\Core\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // used in annotations - do not remove
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; // used in annotations - do not remove
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Zikula\IntercomModule\Entity\MessageEntity as Message;

/**
 * @Route("/messages")
 */
class MessagesController extends AbstractController {

    /**
     * @Route("/preferences")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function preferencesAction(Request $request) {
        // Permission check
        if (!$this->get('zikula_intercom_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $form = $this->createFormBuilder($this->getVars())
                //general settings        
                ->add('ic_note', 'choice', array('choices' => array('0' => $this->__('Off'), '1' => $this->__('On')),
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true))
                ->add('ic_ar', 'choice', array('choices' => array('0' => $this->__('Off'), '1' => $this->__('On')),
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true))
                ->add('ic_art', 'textarea', array('required' => false))
                ->add('save', 'submit')
                ->add('cancel', 'submit')
                ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('save')->isClicked()) {
                $this->setVars('ZikulaIntercomModule', $form->getData());
                $this->addFlash('status', $this->__('Done! preferences updated.'));
            }
            if ($form->get('cancel')->isClicked()) {
                $this->addFlash('status', $this->__('Operation cancelled.'));
            }
            return $this->redirect($this->generateUrl('zikulaintercommodule_user_preferences'));
        }
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('ZikulaIntercomModule:User:preferences.html.twig', array(
                    'form' => $form->createView(),
                    'modvars' => $this->getVars() // @todo temporary solution
        ));
    }
    
    /**
     * @Route("/new" , options={"expose"=true})
     *
     * @return Response symfony response object
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function newMessageAction(Request $request) {
        // Permission check
        if (!$this->get('zikula_intercom_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }
        $options = ['isXmlHttpRequest' => $request->isXmlHttpRequest()];
        $message = $this->get('zikula_intercom_module.manager.message')->create();
        $form = $this->createForm('messageform', $message, $options);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('zikula_intercom_module.manager.message')->setNewData($form->getData());
            $this->get('zikula_intercom_module.manager.message')->send();
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array('status' => true));
            } else {
                $request->getSession()
                        ->getFlashBag()
                        ->add('status', "Message send!");
                return $this->redirect($this->generateUrl('zikulaintercommodule_inbox_view'));
            }
        }

        if ($request->isXmlHttpRequest()) {
           return new JsonResponse(array('status' => true, 'html' => $this->renderView('ZikulaIntercomModule:Message:form.html.twig', array(
                    'form' => $form->createView(),
                    'message' => $message,
                    'settings' => $this->getVars()
        )))); 
        }
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('ZikulaIntercomModule:Message:new.html.twig', array(
                    'form' => $form->createView(),
                    'message' => $message,
                    'settings' => $this->getVars()
        ));
    }    
    
   /**
     * @Route("/{box}/{page}/{sortby}/{sortorder}/{limit}", options={"expose"=true}, requirements={"page" = "\d*"}, defaults={"box" = "inbox", "page" = 1,"sortby" = "send", "sortorder" = "DESC", "limit" = 5})
     *
     * @return Response symfony response object
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function getMessagesAction(Request $request, $box , $page, $sortby, $sortorder, $limit) {
        // Permission check
        if (!$this->get('zikula_intercom_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }
           
        $filter = ['page' => $page,
            'limit' => $limit > 0 ? $limit : $this->getVar('messages_perpage'),
            'sortorder' => $sortorder,
            'sortby' => $sortby
        ];

        $messages = $this->get('zikula_intercom_module.manager.messages')->load($box, $filter);

        if ($request->isXmlHttpRequest()) {
            $response = new JsonResponse();
            $response->setData(array(
                'filter' => $filter,
                'pager' => $messages->getPager(),
                'html' => $this->renderView('ZikulaIntercomModule:Inbox:list.html.twig', array(
                    'messages' => $messages->getmessages()
                ))
            ));

            return $response;
        }

        $layout = $this->getVar('mode') == 0 ? 'Classic' : 'Conversation'; 
        
        $request->attributes->set('_legacy', true); // forces template to render inside old theme        
        return $this->render("@ZikulaIntercomModule/$layout/index.html.twig", array(
                    'layout' => $layout,
                    'box' => $box,
                    'filter' => $filter,
                    'pager' => $messages->getPager(),
                    'messages' => $messages->getmessages(),
                    'settings' => $this->getVars()
        ));
    }
    

    
    


    /**
     * @Route("/markasread/{id}" , options={"expose"=true}, requirements={"id" = "\d*"})
     * mark a message as read
     *
     */
//    public function markmsgreadAction($id) {
//
//        return true;
//    }

}
