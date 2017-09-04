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

use Zikula\IntercomModule\Form\Type\PreferencesType;
use Zikula\Core\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // used in annotations - do not remove
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; // used in annotations - do not remove

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

        if (!$this->hasPermission($this->name . '::', '::', ACCESS_ADMIN)) {
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function preferencesAction(Request $request)
    {

        if (!$this->hasPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(new PreferencesType, $this->getVars(), []);
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
     * @Route("/tools/{operation}", defaults={"operation" = "status"})
     *
     * @return Response symfony response object
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function toolsAction(Request $request, $operation)
    {

        if (!$this->hasPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }

//        $tools = new Tools();
//
//        switch ($operation) {
//            case "fix_integrity_users":
//                if ($tools->fixIntegrityUsers()) {
//                    $this->addFlash('status', $this->__('Done! users integrity fixed.'));
//                } else {
//                    $this->addFlash('error', $this->__('Error! Could not fix users data integrity.'));
//                }
//                break;
//            case "fix_integrity_inbox":
//                if ($tools->fixIntegrityInbox()) {
//                    $this->addFlash('status', $this->__('Done! inboxes integrity fixed.'));
//                } else {
//                    $this->addFlash('error', $this->__('Error! Could not fix inbox data integrity..'));
//                }
//                break;
//            case "fix_integrity_outbox":
//                if ($tools->fixIntegrityOutbox()) {
//                    $this->addFlash('status', $this->__('Done! outboxes integrity fixed.'));
//                } else {
//                    $this->addFlash('error', $this->__('Error! Could not fix outbox data integrity.'));
//                }
//                break;
//            case "fix_integrity_archive":
//                if ($tools->fixIntegrityArchive()) {
//                    $this->addFlash('status', $this->__('Done! archives integrity fixed.'));
//                } else {
//                    $this->addFlash('error', $this->__('Error! Could not fix users archive integrity.'));
//                }
//                break;
//            case "reset_to_defaults":
//                if ($tools->resetSettings()) {
//                    $this->addFlash('status', $this->__('Done! Reset settings to default values.'));
//                } else {
//                    $this->addFlash('error', $this->__('Error! Could not reset settings to default values.'));
//                }
//                break;
//            case "delete_inboxes":
//                if ($tools->deleteInboxes()) {
//                    $this->addFlash('status', $this->__('Done! Emptied inboxes.'));
//                } else {
//                    $this->addFlash('error', $this->__('Error! Could not empty inboxes.'));
//                }
//                break;
//            case "delete_outboxes":
//                if ($tools->deleteOutboxes()) {
//                    $this->addFlash('status', $this->__('Done! Emptied outboxes.'));
//                } else {
//                    $this->addFlash('error', $this->__('Error! Could not empty outboxes.'));
//                }
//                break;
//            case "delete_archive":
//                if ($tools->deleteArchive()) {
//                    $this->addFlash('status', $this->__('Done! Emptied archives.'));
//                } else {
//                    $this->addFlash('error', $this->__('Error! Could not empty archives.'));
//                }
//                break;
//            case "delete_all":
//                if ($tools->deleteAll()) {
//                    $this->addFlash('status', $this->__('Done! Deleted all messages.'));
//                } else {
//                    $this->addFlash('error', $this->__('Error! Could not delete all messages.'));
//                }
//                break;
//            default:
//                break;
//        }

        return $this->render('ZikulaIntercomModule:Admin:tools.html.twig', [
//            'users_check' => $tools->checkIntegrityUsers(),
//            'orphaned' => $tools->checkIntegrityOrphaned(),
//            'inboxes' => $tools->checkIntegrityInbox(),
//            'outboxes' => $tools->checkIntegrityOutbox(),
//            'archives' => $tools->checkIntegrityArchive(),
        ]);
    }

}
