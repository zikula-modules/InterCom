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
        if (!$this->get('zikulaintercommodule.access_manager')->hasPermission()) {
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

}
