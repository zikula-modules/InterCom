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

use ModUtil;
use System;
use SecurityUtil;
use ServiceUtil;
use UserUtil;
use Zikula\Core\Controller\AbstractController;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // used in annotations - do not remove
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; // used in annotations - do not remove
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

use Zikula\IntercomModule\Util\Messages;
use Zikula\IntercomModule\Util\Message;
use Zikula\IntercomModule\Util\Access;


class UserController extends \Zikula_AbstractController
{
  
    public function postInitialize()
    {
        $this->view->setCaching(false);
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
        // Permission check
        if (!Access::checkAccess()) {
            throw new AccessDeniedException();
        }
        return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_inbox', array(), RouterInterface::ABSOLUTE_URL));         
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
        if (!Access::checkAccess()) {
            throw new AccessDeniedException();
        }       
        $uid = UserUtil::getVar('uid');
        $autoreply = 0;
        if ($this->getVar('allow_autoreply') == 1) {
            // and read the user data incl. the attributes
            $autoreply = UserUtil::getVar('ic_ar'); 
        }
        $this->view->assign('autoreply',        $autoreply);
        $a = array();
        // Get startnum and perpage parameter for pager
        $a['startnum'] = $request->query->get('startnum',null);
        $a['perpage'] = $this->getVar('perpage', 25);
        // Get parameters from whatever input we need.
        $a['sortorder'] = $request->query->get('sortorder', 'DESC');
        $a['sortby'] = $request->query->get('sortby','send');       
        $messages = new Messages();
        // Get the amount of messages within each box
        $totalarray = $messages->getmessagecount();
        $a['inbox'] = 1;
        $a['recipient'] = $uid;        
        $messagearray = $messages->getmessages($a);            
        $this->view->assign('boxtype',          'inbox');
        $this->view->assign('currentuid',       UserUtil::getVar('uid'));
        $this->view->assign('messagearray',     $messagearray);
        $this->view->assign('getmessagecount',  $totalarray);
        $this->view->assign('indicatorbar',     $totalarray['indicatorbarin']);        
        $this->view->assign('sortbar_target',   'inbox');
        $this->view->assign('messagesperpage',  $a['perpage']);
        $this->view->assign('sortorder',        $a['sortorder']);
        $this->view->assign('sortby',           $a['sortby']);        
        $this->view->assign('ictitle',          $this->__('Inbox'));
        // Return output object
        return new Response($this->view->fetch('User/view.tpl'));
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
       if (!Access::checkAccess()) {
           throw new AccessDeniedException();
       }
        $uid = UserUtil::getVar('uid');
        $a = array();
        // Get startnum and perpage parameter for pager
        $a['startnum'] = $request->query->get('startnum',null);
        $a['perpage'] = $this->getVar('perpage', 25);
        // Get parameters from whatever input we need.
        $a['sortorder'] = $request->query->get('sortorder', 'DESC');
        $a['sortby'] = $request->query->get('sortby','send');       
        $messages = new Messages();
        // Get the amount of messages within each box
        $totalarray = $messages->getmessagecount();
        $a['outbox'] = 1;
        $a['sender'] = $uid;
        $messagearray = $messages->getmessages($a);
        $this->view->assign('boxtype',          'outbox');
        $this->view->assign('currentuid',       $uid);
        $this->view->assign('messagearray',     $messagearray);
        $this->view->assign('getmessagecount',  $totalarray);
        $this->view->assign('indicatorbar',     $totalarray['indicatorbarout']);          
        $this->view->assign('sortbar_target',   'outbox');
        $this->view->assign('messagesperpage',  $a['perpage']);
        $this->view->assign('sortorder',        $a['sortorder']);
        $this->view->assign('sortby',           $a['sortby']);        
        $this->view->assign('ictitle',          $this->__('Outbox'));
        // Return output object
        return new Response($this->view->fetch('User/view.tpl'));
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
        if (!Access::checkAccess()) {
           throw new AccessDeniedException();
        }
        $uid = UserUtil::getVar('uid');
        $autoreply = 0;
        if ($this->getVar('allow_autoreply') == 1) {
            // and read the user data incl. the attributes
            $autoreply = UserUtil::getVar('ic_ar'); 
        }
        $this->view->assign('autoreply',        $autoreply);
        $a = array();
        // Get startnum and perpage parameter for pager
        $a['startnum'] = $request->query->get('startnum',null);
        $a['perpage'] = $this->getVar('perpage', 25);
        // Get parameters from whatever input we need.
        $a['sortorder'] = $request->query->get('sortorder', 'DESC');
        $a['sortby'] = $request->query->get('sortby','send');      
        $messages = new Messages();
        // Get the amount of messages within each box
        $totalarray = $messages->getmessagecount();
        $a['stored'] = 1;
        $a['recipient'] = $uid;
        $messagearray = $messages->getmessages($a);                
        $this->view->assign('boxtype',          'archive');
        $this->view->assign('currentuid',       UserUtil::getVar('uid'));
        $this->view->assign('messagearray',     $messagearray);
        $this->view->assign('getmessagecount',  $totalarray);
        $this->view->assign('indicatorbar',     $totalarray['indicatorbararchive']);   
        $this->view->assign('sortbar_target',   'archive');
        $this->view->assign('messagesperpage',  $a['perpage']);
        $this->view->assign('sortorder',        $a['sortorder']);
        $this->view->assign('sortby',           $a['sortby']);        
        $this->view->assign('ictitle',          $this->__('Archive'));
        // Return output object
        return new Response($this->view->fetch('User/view.tpl'));
    }

    /**
     * @Route("/message/{mode}")
     *
     * @return Response symfony response object
     * 
     * @todo this is too long
     * 
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function messageAction(Request $request, $mode)
    {
        // Permission check
        if (!Access::checkAccess()) {throw new AccessDeniedException();}
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
            case "store":
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

    /**
     * @Route("/preferences")
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     * 
     * @return Response
     */
    public function preferencesAction(Request $request)
    {
       // Permission check
        if (!Access::checkAccess()) {
           throw new AccessDeniedException();
        }
        $uid = UserUtil::getVar('uid');
        if ($request->isMethod('Post')){
            $this->checkCsrfToken();
            UserUtil::setVar('ic_note', $request->request->get('ic_note',false), $uid);
            UserUtil::setVar('ic_ar', $request->request->get('ic_ar',false), $uid);
            UserUtil::setVar('ic_art', $request->request->get('ic_art',false), $uid);       
            $this->request->getSession()->getFlashbag()->add('status', $this->__('Preferences saved'));             
        }     
        $data['ic_note'] = UserUtil::getVar('ic_note',$uid);
        $data['ic_ar'] = UserUtil::getVar('ic_ar',$uid);
        $data['ic_art'] = UserUtil::getVar('ic_art',$uid);       
        $this->view->assign($data);       
        return new Response($this->view->fetch('User/prefs.tpl'));
    }
}