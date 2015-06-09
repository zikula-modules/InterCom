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
use Symfony\Component\Validator\Constraints as Assert;

use Zikula\IntercomModule\Util\Tools;
use Zikula\IntercomModule\Util\Settings;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{ 
    public function postInitialize()
    {
        $this->view->setCaching(false);
    }

    /**
     * Route not needed here because this is a legacy-only method
     * 
     * The default entrypoint.
     *
     * @return RedirectResponse
     */
    public function mainAction()
    {
        return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_admin_view', array(), RouterInterface::ABSOLUTE_URL));
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
        // Security check
        if (!SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
        
        $inbox = $this->get('doctrine.entitymanager')->getRepository('Zikula\IntercomModule\Entity\MessageEntity')
                    ->getAll(array('deleted' => 'bysender'));  
        $outbox  = $this->get('doctrine.entitymanager')->getRepository('Zikula\IntercomModule\Entity\MessageEntity')
                    ->getAll(array('deleted' => 'byrecipient'));
        $archive = $this->get('doctrine.entitymanager')->getRepository('Zikula\IntercomModule\Entity\MessageEntity')
                    ->getAll(array('stored' => 'bysender'));
        
        
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        
        return $this->render('ZikulaIntercomModule:Admin:index.html.twig', array(
            'inbox'              => $inbox->count(),
            'outbox'             => $outbox->count(),
            'archive'            => $archive->count()));
    }
    
    
    /**
     * @Route("/preferences")
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function preferencesAction(Request $request)
    {
        
        // Security check
        if (!SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }        
        
        $form = $this->createFormBuilder(\ModUtil::getVar('ZikulaIntercomModule'))                
                //general settings
                ->add('active', 'choice', array('choices' => array('0' => $this->__('Off'), '1' => $this->__('On')),
                                                'multiple' => false,
                                                'expanded' => true,
                                                'required' => true))
                ->add('maintain', 'text', array('required' => false))
                ->add('allowhtml', 'choice', array('choices' => array('0' => $this->__('Off'), '1' => $this->__('On')),
                                                'multiple' => false,
                                                'expanded' => true,
                                                'required' => true))
                ->add('allowsmilies', 'choice', array('choices' => array('0' => $this->__('Off'), '1' => $this->__('On')),
                                                'multiple' => false,
                                                'expanded' => true,
                                                'required' => true))
                ->add('disable_ajax', 'choice', array('choices' => array('0' => $this->__('Off'), '1' => $this->__('On')),
                                                'multiple' => false,
                                                'expanded' => true,
                                                'required' => true))
                //Limitations
                ->add('limitarchive', 'number', array(
                'constraints' => array(
                    new Assert\GreaterThan(array('value' => 0)),
                )))
                ->add('limitoutbox', 'number', array(
                'constraints' => array(
                    new Assert\GreaterThan(array('value' => 0)),
                )))
                ->add('limitinbox', 'number', array(
                'constraints' => array(
                    new Assert\GreaterThan(array('value' => 0)),
                )))
                ->add('perpage', 'number', array(
                'constraints' => array(
                    new Assert\GreaterThan(array('value' => 0)),
                )))
                //protection
                ->add('protection_on', 'choice', array('choices' => array('0' => $this->__('Off'), '1' => $this->__('On')),
                                                'multiple' => false,
                                                'expanded' => true,
                                                'required' => true))
                ->add('protection_time', 'text', array('required' => false))
                ->add('protection_amount', 'text', array('required' => false))
                ->add('protection_mail', 'choice', array('choices' => array('0' => $this->__('Off'), '1' => $this->__('On')),
                                                'multiple' => false,
                                                'expanded' => true,
                                                'required' => true))
                //user prompt
                ->add('userprompt', 'text', array('required' => false))
                ->add('userprompt_display', 'choice', array('choices' => array('0' => $this->__('Off'), '1' => $this->__('On')),
                                                'multiple' => false,
                                                'expanded' => true,
                                                'required' => true))
                //Welcome
                ->add('welcomemessage_send', 'choice', array('choices' => array('0' => $this->__('Off'), '1' => $this->__('On')),
                                                'multiple' => false,
                                                'expanded' => true,
                                                'required' => true))
                ->add('welcomemessagesender', 'text', array('required' => false))
                ->add('welcomemessagesubject', 'text', array('required' => false))
                ->add('welcomemessage', 'text', array('required' => false))
                ->add('intlwelcomemessage', 'text', array('required' => false))
                ->add('savewelcomemessage', 'choice', array('choices' => array('0' => $this->__('Off'), '1' => $this->__('On')),
                                                'multiple' => false,
                                                'expanded' => true,
                                                'required' => true))
                //Email
                ->add('allow_emailnotification', 'choice', array('choices' => array('0' => $this->__('Off'), '1' => $this->__('On')),
                                                'multiple' => false,
                                                'expanded' => true,
                                                'required' => true))
                ->add('force_emailnotification', 'choice', array('choices' => array('0' => $this->__('Off'), '1' => $this->__('On')),
                                                'multiple' => false,
                                                'expanded' => true,
                                                'required' => true))
                ->add('mailsender', 'text', array('required' => false))
                ->add('mailsubject', 'text', array('required' => false))
                ->add('fromname', 'text', array('required' => false))
                ->add('from_email', 'email', array('required' => false))
                //Autoreply
                ->add('allow_autoreply', 'choice', array('choices' => array('0' => $this->__('Off'), '1' => $this->__('On')),
                                                'multiple' => false,
                                                'expanded' => true,
                                                'required' => true))
                //Mode
                ->add('mode', 'choice', array('choices' => array('0' => $this->__('Classic'), '1' => $this->__('Conversation')),
                                                'multiple' => false,
                                                'expanded' => true,
                                                'required' => true))
                ->add('save', 'submit')
                ->add('cancel', 'submit')                                
                ->getForm();
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($form->get('save')->isClicked()) {
                    \ModUtil::setVars('ZikulaIntercomModule', $form->getData());
                    $this->addFlash('status', $this->__('Done! Module configuration updated.'));
                }
                if ($form->get('cancel')->isClicked()) {
                    $this->addFlash('status', $this->__('Operation cancelled.'));
                }
                return $this->redirect($this->generateUrl('zikulaintercommodule_admin_preferences'));
            }
            $request->attributes->set('_legacy', true); // forces template to render inside old theme
            return $this->render('ZikulaIntercomModule:Admin:preferences.html.twig', array(
                'form' => $form->createView(),
            ));
    }
    
    /**
     * @Route("/tools/{operation}", defaults={"operation" = "status"})
     *
     * @return Response symfony response object
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function toolsAction(Request $request, $operation)
    {
        // Security check
        if (!SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }        
        $tools = new Tools();

        switch ($operation) {
            case "fix_integrity_users":
                if ($tools->fixIntegrityUsers()) {                   
                    $this->addFlash('status', $this->__('Done! users integrity fixed.'));
                } else {
                    $this->addFlash('error', $this->__('Error! Could not fix users data integrity.'));
                }
                break;
            case "fix_integrity_inbox":
                if ($tools->fixIntegrityInbox()) {
                    $this->addFlash('status', $this->__('Done! inboxes integrity fixed.'));
                } else {
                    $this->addFlash('error', $this->__('Error! Could not fix inbox data integrity..'));
                }
                break;
            case "fix_integrity_outbox":
                if ($tools->fixIntegrityOutbox()) {
                    $this->addFlash('status', $this->__('Done! outboxes integrity fixed.'));
                } else {
                    $this->addFlash('error', $this->__('Error! Could not fix outbox data integrity.'));
                }
                break;
            case "fix_integrity_archive":
                if ($tools->fixIntegrityArchive()) {
                    $this->addFlash('status', $this->__('Done! archives integrity fixed.'));
                } else {
                    $this->addFlash('error', $this->__('Error! Could not fix users archive integrity.'));
                }
                break;
            case "reset_to_defaults":
                if ($tools->resetSettings()) {
                    $this->addFlash('status', $this->__('Done! Reset settings to default values.'));  
                } else {
                    $this->addFlash('error', $this->__('Error! Could not reset settings to default values.'));             
                }                
                break;
            case "delete_inboxes":
                if ($tools->deleteInboxes()) {
                    $this->addFlash('status', $this->__('Done! Emptied inboxes.'));
                } else {
                    $this->addFlash('error', $this->__('Error! Could not empty inboxes.'));
                }              
                break;                
            case "delete_outboxes":
                if ($tools->deleteOutboxes()) {
                    $this->addFlash('status', $this->__('Done! Emptied outboxes.'));
                } else {
                    $this->addFlash('error', $this->__('Error! Could not empty outboxes.'));
                }               
                break;
            case "delete_archive":
                if ($tools->deleteArchive()) {
                    $this->addFlash('status', $this->__('Done! Emptied archives.'));
                } else {
                    $this->addFlash('error', $this->__('Error! Could not empty archives.'));
                }
                break;
            case "delete_all":
                if ($tools->deleteAll()) {
                    $this->addFlash('status', $this->__('Done! Deleted all messages.'));
                } else {
                    $this->addFlash('error', $this->__('Error! Could not delete all messages.'));
                }
                break;
            default:
                break;
       }
        
       $request->attributes->set('_legacy', true); // forces template to render inside old theme
       return $this->render('ZikulaIntercomModule:Admin:tools.html.twig', array(
                    'users_check' => $tools->checkIntegrityUsers(),
                    'orphaned' => $tools->checkIntegrityOrphaned(),
                    'inboxes' => $tools->checkIntegrityInbox(),
                    'outboxes' => $tools->checkIntegrityOutbox(),
                    'archives' => $tools->checkIntegrityArchive(),
      ));   
    }
}