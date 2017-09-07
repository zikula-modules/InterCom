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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Core\Controller\AbstractController;
use Zikula\Core\Response\PlainResponse;
use Zikula\UsersModule\Constant as UsersConstant;

/**
 * @Route("/messages/user")
 */
class UserController extends AbstractController
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
     * Called from form to populate a recipient search.
     *
     * @Route("/getrecipients", options={"expose"=true})
     *
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getRecipientsAction(Request $request)
    {
        if (!$this->hasPermission('ZikulaIntercomModule', '::', ACCESS_MODERATE)) {
            return new PlainResponse('');
        }

        $fragment = $request->request->get('fragment');
        $filter = [
            'activated' => ['operator' => 'notIn', 'operand' => [
                UsersConstant::ACTIVATED_PENDING_REG,
                UsersConstant::ACTIVATED_PENDING_DELETE,
            ]],
            'uname' => ['operator' => 'like', 'operand' => "$fragment%"],
        ];
        $users = $this->get('zikula_users_module.user_repository')->query($filter);
        // @todo add groups
        $recipients = [];
        foreach ($users as $k => $user) {
            $recipients[$k] = ['id' => $user->getUid(), 'uname' => $user->getUname()];
        }

        return new Response(json_encode(['recipients' => $recipients]));
    }
}
