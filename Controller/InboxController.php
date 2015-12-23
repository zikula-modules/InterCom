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
use Symfony\Component\HttpFoundation\Response;
use Zikula\IntercomModule\Util\Messages;

/**
 * @Route("/inbox")
 *
 */
class InboxController extends AbstractController {

    /**
     * @Route("/view/{page}/{sortby}/{sortorder}", requirements={"page" = "\d*"}, defaults={"page" = 1,"sortby" = "send", "sortorder" = "DESC"})
     *
     * @return Response symfony response object
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function viewAction(Request $request, $page, $sortby, $sortorder) {
        // Permission check
        if (!$this->get('zikulaintercommodule.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }
        
        $filter = ['page' => $page,
            'limit' => $request->query->get('limit', $this->getVar('messages_perpage')),
            'sortorder' => $sortorder,
            'sortby' => $sortby,
            'recipient' => \UserUtil::getVar('uid'),
            'deleted' => 'byrecipient'
        ];

        $messages = new Messages();
        $messages->load($filter);

        $request->attributes->set('_legacy', true); // forces template to render inside old theme        
        return $this->render('ZikulaIntercomModule:Inbox:index.html.twig', array(
                    'sortorder' => $sortorder,
                    'sortby' => $sortby,
                    'page' => $page,
                    'limit' => $filter['limit'],
                    'thisPage' => $filter['page'],
                    'maxPages' => ceil($messages->getmessages_count() / $filter['limit'])
        ));
    }
    
    /**
     * @Route("/conversations/{page}/{sortby}/{sortorder}", options={"expose"=true}, requirements={"page" = "\d*"}, defaults={"page" = 1,"sortby" = "send", "sortorder" = "DESC"})
     *
     * @return Response symfony response object
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function conversationsAction(Request $request, $page, $sortby, $sortorder) {
        // Permission check
        if (!$this->get('zikulaintercommodule.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $filter = array('page' => $page,
            'limit' => $request->query->get('limit', $this->getVar('messages_perpage')),
            'sortorder' => $sortorder,
            'sortby' => $sortby,
            'recipient' => \UserUtil::getVar('uid'),
            'deleted' => 'byrecipient'
        );

        $messages = new Messages();
        $messages->load($filter);

        $request->attributes->set('_legacy', true); // forces template to render inside old theme        
        return $this->render('ZikulaIntercomModule:Inbox:list.html.twig', array(
                    'messages' => $messages->getmessages()
        ));
    }

}
