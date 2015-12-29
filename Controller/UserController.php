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

class UserController extends AbstractController {

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
     * @Route("/usagestatus/{box}", options={"expose"=true}, defaults={"box"="inbox"})
     *
     * @return Response symfony response object
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function usagestatusAction(Request $request, $box) {
        // Permission check
        if (!$this->get('zikula_intercom_module.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $uid = \UserUtil::getVar('uid');
        
        switch ($box){
            case 'inbox':
            $filter = ['deleted' => 'byrecipient', 'recipient' => $uid];               
            break;
            case 'send':
            $filter = ['deleted' => 'bysender', 'sender' => $uid];               
            break;
            case 'saved':
            $filter = ['stored' => 'all', 'recipient' => $uid, 'sender' => $uid];               
            break;        
        default :
            $filter = ['deleted' => 'byrecipient', 'recipient' => $uid];
            break;
        }
          
        $manager = $this->get('zikula_intercom_module.manager.messages')->load($filter);
        
        if ($request->isXmlHttpRequest()) {
            $response = new JsonResponse();
            $response->setData(array(
                'box' => $box,
                'total' => $manager->count(),
                'settings' => $this->getVars(),
                'html' => $this->renderView('ZikulaIntercomModule:User:usagestatus.html.twig', array(
                    'box' => $box,
                    'total' => $manager->count(),
                    'settings' => $this->getVars(),
                ))
            ));

            return $response;
        }

        $request->attributes->set('_legacy', true); // forces template to render inside old theme        
        return $this->render('ZikulaIntercomModule:User:usagestatus.html.twig', array(
                    'box' => $box,
                    'total' => $manager->count(),
                    'settings' => $this->getVars()
        ));
    }

}
