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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Core\Controller\AbstractController;
use Zikula\IntercomModule\Form\Type\PreferencesType;

/**
 * @Route("messages/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/status")
     *
     * the main administration function
     *
     * @return RedirectResponse
     */
    public function statusAction(Request $request)
    {
        if (!$this->hasPermission($this->name.'::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }

//        $inbox = $this->get('doctrine.entitymanager')->getRepository('Zikula\IntercomModule\Entity\MessageEntity')
//        ->getAll(['deleted' => 'bysender']);
//        $outbox = $this->get('doctrine.entitymanager')->getRepository('Zikula\IntercomModule\Entity\MessageEntity')
//        ->getAll(['deleted' => 'byrecipient']);
//        $archive = $this->get('doctrine.entitymanager')->getRepository('Zikula\IntercomModule\Entity\MessageEntity')
//        ->getAll(['stored' => 'bysender']);

        return $this->render('ZikulaIntercomModule:Admin:index.html.twig', [
//            'inbox' => $inbox->count(),
//            'outbox' => $outbox->count(),
//            'archive' => $archive->count()
        ]);
    }

    /**
     * @Route("/preferences")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function preferencesAction(Request $request)
    {
        if (!$this->hasPermission($this->name.'::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(PreferencesType::class, $this->getVars(), []);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('save')->isClicked()) {
                $this->setVars($form->getData());
                $this->addFlash('status', $this->__('Done! Module configuration updated.'));
            }
            if ($form->get('cancel')->isClicked()) {
                $this->addFlash('status', $this->__('Operation cancelled.'));
            }

            return $this->redirect($this->generateUrl('zikulaintercommodule_admin_preferences'));
        }

        return $this->render('ZikulaIntercomModule:Admin:preferences.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/import")
     *
     * the main administration function
     *
     * @return RedirectResponse
     */
    public function importAction(Request $request)
    {
        if (!$this->hasPermission($this->name.'::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }

        $importHelper = $this->get('zikula_intercom_module.import_helper');

        return $this->render('ZikulaIntercomModule:Admin:import.html.twig', [
            'importHelper' => $importHelper,
        ]);
    }
}
