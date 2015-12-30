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
 * @Route("/message")
 */
class MessageController extends AbstractController {

    /**
     * @Route("/new" , options={"expose"=true})
     *
     * @return Response symfony response object
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function newAction(Request $request) {
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
     * @Route("/markasread/{id}" , options={"expose"=true}, requirements={"id" = "\d*"})
     * mark a message as read
     *
     */
    public function markmsgreadAction($id) {

        return true;
    }

}
