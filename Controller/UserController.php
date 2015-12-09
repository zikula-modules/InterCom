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

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // used in annotations - do not remove
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; // used in annotations - do not remove
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

use Zikula\IntercomModule\Util\Messages;
use Zikula\IntercomModule\Util\Message;


class UserController extends AbstractController
{
  
    public function postInitialize()
    {
        $this->view->setCaching(false);
    }
    
    /**
     * Route not needed here because this is a legacy-only method
     *
     * The default entry point.
     *
     * This redirects back to the default entry point for the Intercom module.
     *
     * @return RedirectResponse
     */
    public function mainAction()
    {
        return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_view', array(), RouterInterface::ABSOLUTE_URL));
    }    
    
    /**
     * @Route("")
     *
     * the main administration function
     *
     * @return RedirectResponse
    */ 
    public function indexAction(Request $request)
    {        
        if($this->getVar('mode') == 1){        
        return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_conversations', array(), RouterInterface::ABSOLUTE_URL));
        }else{
        return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_inbox', array(), RouterInterface::ABSOLUTE_URL));            
        }   
    }
    
    /**
     * @Route("/inbox")
     *
     * @return Response symfony response object
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
    */     
    public function inboxAction(Request $request)
    {
        // Permission check
        if (!$this->get('zikulaintercommodule.access_manager')->hasPermission()) { throw new AccessDeniedException();}       
        
        $filter = array('page' => $request->query->get('page', null),
			            'limit' => $request->query->get('limit', 25),
			            'sortorder' => $request->query->get('sortorder', 'DESC'),
			            'sortby' => $request->query->get('sortby','send'),
			            'recipient' => \UserUtil::getVar('uid'),
			            'deleted' => 'byrecipient'
        );

        $messages = new Messages();     
        $messages->load($filter);        
        
        $request->attributes->set('_legacy', true); // forces template to render inside old theme        
        return $this->render('ZikulaIntercomModule:User:inbox.html.twig', array(
            'messages'  => $messages->getmessages(),
            'limit'    => $filter['limit'],
            'sortorder' => $filter['sortorder'],
            'sortby'    => $filter['sortby']            
        ));         
    }

    /**
     * @Route("/outbox")
     *
     * @return Response symfony response object
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function outboxAction(Request $request)
    {
    	// Permission check
        if (!$this->get('zikulaintercommodule.access_manager')->hasPermission()) { throw new AccessDeniedException();}        
        
        $filter = array('page' => $request->query->get('page', null),
			            'limit' => $request->query->get('limit', 25),
			            'sortorder' => $request->query->get('sortorder', 'DESC'),
			            'sortby' => $request->query->get('sortby','send'),
			            'recipient' => \UserUtil::getVar('uid'),
			            'deleted' => 'bysender'
        );

        $messages = new Messages();     
        $messages->load($filter);        
        
        $request->attributes->set('_legacy', true); // forces template to render inside old theme        
        return $this->render('ZikulaIntercomModule:User:outbox.html.twig', array(
            'messages'  => $messages->getmessages(),
            'limit'    => $filter['limit'],
            'sortorder' => $filter['sortorder'],
            'sortby'    => $filter['sortby']            
        ));         
    }

    /**
     * @Route("/archive")
     *
     * @return Response symfony response object
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function archiveAction(Request $request)
    {
		// Permission check
        if (!$this->get('zikulaintercommodule.access_manager')->hasPermission()) { throw new AccessDeniedException();}     
        
        $filter = array('page' => $request->query->get('page', null),
			            'limit' => $request->query->get('limit', 25),
			            'sortorder' => $request->query->get('sortorder', 'DESC'),
			            'sortby' => $request->query->get('sortby','send'),
			            'recipient' => \UserUtil::getVar('uid'),
			            'deleted' => 'byrecipient'
        );

        $messages = new Messages();     
        $messages->load($filter);        
        
        $request->attributes->set('_legacy', true); // forces template to render inside old theme        
        return $this->render('ZikulaIntercomModule:User:archive.html.twig', array(
            'messages'  => $messages->getmessages(),
            'limit'    => $filter['limit'],
            'sortorder' => $filter['sortorder'],
            'sortby'    => $filter['sortby']            
        ));         
    }

    /**
     * @Route("/message/{mode}" , defaults={"mode" = "new"})
     *
     * @return Response symfony response object
     * 
     * @todo this is too long
     * 
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     
    public function messageAction(Request $request, $mode)
    {
        // Permission check
        if (!Access::checkAccess()) {throw new AccessDeniedException();}
        
        $a = array();
        //id is comming both ways
        $a['id'] = $request->query->get('id');
        // save post data
        if ($request->isMethod('Post')){
                $this->checkCsrfToken();
                $action =           $request->request->get('action',    false);                  
                $a['id'] =          $request->request->get('id',        $a['id']);                              
                $a['sender'] =      $request->request->get('sender',    false);
                $a['recipients'] =   $request->request->get('recipients', false);
//                $a['group'] =       $request->request->get('group',     false);
                $a['subject'] =     $request->request->get('subject',   false);
                $a['text'] =        $request->request->get('text',      false);              
        }       
            $message = new Message();      
        switch($mode){
            case "read":
                if(!$a['id']){
                    $this->request->getSession()->getFlashbag()->add('error', $this->__('Sorry. Message not found missing id'));
                    // Redirect
                    return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_index', array(), RouterInterface::ABSOLUTE_URL));                     
                }     
                $message->load($a);//no post a so only id
                if(!$message->exist()){
                    $this->request->getSession()->getFlashbag()->add('error', $this->__('Sorry. Message not found'));
                    // Redirect
                    return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_index', array(), RouterInterface::ABSOLUTE_URL));         
                }
                $this->view->assign('mode',       $mode);
                $this->view->assign('currentuid', UserUtil::getVar('uid'));
                $this->view->assign('message',    $message->toArray());
                $this->view->assign('ictitle',    $this->__('Read'));
                $message->setSeen();
                return new Response($this->view->fetch('User/readpm.tpl'));                
                break;
            case "new":
                if (!Access::checkAccess(ACCESS_COMMENT)) {throw new AccessDeniedException();}
                $recipients['uid'] = $request->query->get('uid');
                $recipients['names'] = UserUtil::getVar('uname', $recipients['uid']);
                $this->view->assign('recipients',      $recipients);
                $this->view->assign('ictitle',    $this->__('New'));
                $this->view->assign('action',      false);
                $this->view->assign('mode',       'new');
                $this->view->assign('currentuid', UserUtil::getVar('uid'));                  
                if ($request->isMethod('Post')){
                $this->checkCsrfToken();
                $message->setNewData($a);
                if (!$message->isValid()){
                $this->view->assign($message->getNewData());
                $this->view->assign('errors' ,$message->getErrors());                  
                return new Response($this->view->fetch('User/pm.tpl'));            
                }
                if ($action == "send"){
                if ($message->isMultiple()){
                $message->sendMultiple()
                ? $this->request->getSession()->getFlashbag()->add('status', $this->__('Messages send'))
                : $this->request->getSession()->getFlashbag()->add('error', $this->__('Messages not send'));        
                return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_outbox', array(), RouterInterface::ABSOLUTE_URL));                    
                }else{
                $message->send()
                ? $this->request->getSession()->getFlashbag()->add('status', $this->__('Message send'))
                : $this->request->getSession()->getFlashbag()->add('error', $this->__('Message not send'));        
                return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_outbox', array(), RouterInterface::ABSOLUTE_URL));                     
                }    
                }
                if ($action == "preview"){
                $this->view->assign('action',      $action);                
                $this->view->assign($message->getNewData());
                $this->view->assign('ictitle',    $this->__('Edit'));   
                }
                }
                $this->view->assign('mode',       'new');              
                return new Response($this->view->fetch('User/pm.tpl'));                
                break;                
            case "reply":
                if (!Access::checkAccess(ACCESS_COMMENT)) {throw new AccessDeniedException();}
                if(!$a['id']){
                    $this->request->getSession()->getFlashbag()->add('error', $this->__('Sorry. Message not found missing id'));
                    return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_index', array(), RouterInterface::ABSOLUTE_URL));                     
                }
                $message->load(array('id'=>$a['id']));
                if(!$message->exist()){
                    $this->request->getSession()->getFlashbag()->add('error', $this->__('Sorry. Message not found'));
                    return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_index', array(), RouterInterface::ABSOLUTE_URL));         
                }                
                $this->view->assign('ictitle',    $this->__('Reply'));
                $this->view->assign('action',      false);
                $this->view->assign('mode',       'reply');
                $this->view->assign('id', $a['id']);
                $this->view->assign('currentuid', UserUtil::getVar('uid')); 
                if ($request->isMethod('Post')){
                $this->checkCsrfToken();
                $message->setNewData($a);
                if (!$message->isValid()){
                $this->view->assign($message->getNewData());
                $this->view->assign('errors' ,$message->getErrors());                  
                return new Response($this->view->fetch('User/pm.tpl'));            
                }
                if ($action == "send"){
                $message->reply()
                ? $this->request->getSession()->getFlashbag()->add('status', $this->__('Reply send'))
                : $this->request->getSession()->getFlashbag()->add('error', $this->__('Reply not send'));        
                return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_outbox', array(), RouterInterface::ABSOLUTE_URL));       
                }
                if ($action == "preview"){
                $this->view->assign('action',      $action);                
                $this->view->assign($message->getNewData());
                $this->view->assign('ictitle',    $this->__('Edit'));
                return new Response($this->view->fetch('User/pm.tpl'));
                }
                }
                $this->view->assign('mode',       'reply');
                $this->view->assign($message->prepareForReply());                               
                return new Response($this->view->fetch('User/pm.tpl'));                 
                break;
            case "forward":
                if (!Access::checkAccess(ACCESS_COMMENT)) {throw new AccessDeniedException();}
                if(!$a['id']){
                    $this->request->getSession()->getFlashbag()->add('error', $this->__('Sorry. Message not found missing id'));
                    return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_index', array(), RouterInterface::ABSOLUTE_URL));                     
                }
                $message->load(array('id'=>$a['id']));
                if(!$message->exist()){
                    $this->request->getSession()->getFlashbag()->add('error', $this->__('Sorry. Message not found'));
                    return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_index', array(), RouterInterface::ABSOLUTE_URL));         
                }                
                $this->view->assign('ictitle',    $this->__('Forward'));
                $this->view->assign('action',      false);
                $this->view->assign('mode',       'forward');
                $this->view->assign('id', $a['id']);
                $this->view->assign('currentuid', UserUtil::getVar('uid')); 
                //post only
                if ($request->isMethod('Post')){
                $this->checkCsrfToken();
                $message->setNewData($a);
                if (!$message->isValid()){
                $this->view->assign($message->getNewData());
                $this->view->assign('errors' ,$message->getErrors());                  
                return new Response($this->view->fetch('User/pm.tpl'));            
                }
                if ($action == "send"){
                $message->forward()
                ? $this->request->getSession()->getFlashbag()->add('status', $this->__('Message forwarded'))
                : $this->request->getSession()->getFlashbag()->add('error', $this->__('Message not forwarded'));        
                return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_outbox', array(), RouterInterface::ABSOLUTE_URL));         
                }
                if ($action == "preview"){
                $this->view->assign('action',      $action);                
                $this->view->assign($message->getNewData());
                $this->view->assign('ictitle',    $this->__('Edit'));
                return new Response($this->view->fetch('User/pm.tpl'));               
                }
                }
                $this->view->assign('mode',       'forward');
                $this->view->assign($message->prepareForForward());                               
                return new Response($this->view->fetch('User/pm.tpl'));
                break;                
            case "save":
                if (!Access::checkAccess(ACCESS_COMMENT)) {throw new AccessDeniedException();}
                if(!$a['id']){
                    $this->request->getSession()->getFlashbag()->add('error', $this->__('Sorry. Message not found missing id'));
                    return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_index', array(), RouterInterface::ABSOLUTE_URL));                     
                }
                $message->load(array('id'=>$a['id']));
                if(!$message->exist()){
                    $this->request->getSession()->getFlashbag()->add('error', $this->__('Sorry. Message not found'));
                    return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_index', array(), RouterInterface::ABSOLUTE_URL));         
                }                
                $message->store()
                ? $this->request->getSession()->getFlashbag()->add('status', $this->__('Message stored'))
                : $this->request->getSession()->getFlashbag()->add('error', $this->__('Message not stored'));        
                return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_archive', array(), RouterInterface::ABSOLUTE_URL));        
               
                break;
            case "delete":
                if (!Access::checkAccess(ACCESS_COMMENT)) {throw new AccessDeniedException();}
                if(!$a['id']){
                    $this->request->getSession()->getFlashbag()->add('error', $this->__('Sorry. Message not found missing id'));
                    return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_index', array(), RouterInterface::ABSOLUTE_URL));                     
                }
                $message->load(array('id'=>$a['id']));
                if(!$message->exist()){
                    $this->request->getSession()->getFlashbag()->add('error', $this->__('Sorry. Message not found'));
                    return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_index', array(), RouterInterface::ABSOLUTE_URL));         
                }
                $message->delete()
                ? $this->request->getSession()->getFlashbag()->add('status', $this->__('Message deleted'))
                : $this->request->getSession()->getFlashbag()->add('error', $this->__('Message not deleted'));        
                return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_inbox', array(), RouterInterface::ABSOLUTE_URL));              
                break;
            default :                
                break;
        }       
    }
*/
    /**
     * @Route("/preferences")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function preferencesAction(Request $request)
    {    
            // Permission check
        if (!$this->get('zikulaintercommodule.access_manager')->hasPermission()) { throw new AccessDeniedException();}   
    
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
}