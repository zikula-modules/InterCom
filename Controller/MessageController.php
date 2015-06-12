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

use Zikula\IntercomModule\Entity\MessageEntity as Message;
use Zikula\IntercomModule\Util\Access;

/**
 * @Route("/message")
 */
class MessageController extends AbstractController
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
        // Permission check
        if (!Access::checkAccess()) {
            throw new AccessDeniedException();
        }
        
        if(ModUtil::getVar($this->name, 'mode') == 1){        
        return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_conversations', array(), RouterInterface::ABSOLUTE_URL));
        }else{
        return new RedirectResponse($this->get('router')->generate('zikulaintercommodule_user_inbox', array(), RouterInterface::ABSOLUTE_URL));            
        }   
    }
    
    /**
     * @Route("/new/")
     *
     * @return Response symfony response object
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
    */     
    public function newAction(Request $request)
    {
        // Permission check
        if (!Access::checkAccess()) { throw new AccessDeniedException();}       

        $message = new Message();
        
        $form = $this->createForm('messageform', $message);
        
        $form->handleRequest($request);
        
        /**
         *
         * @var \Doctrine\ORM\EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();
        if ($form->isValid()) {
            $em->persist($message);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('status', "Page saved!");
            
            return $this->redirect($this->generateUrl('zikulaintercommodule_user_outbox', array(
                'id' => $message->getId()
            )));
        }
        
        $request->attributes->set('_legacy', true); // forces template to render inside old theme
        return $this->render('ZikulaIntercomModule:Message:new.html.twig', array(
            'form' => $form->createView(),
            'message' => $message
        ));       
    }   
}