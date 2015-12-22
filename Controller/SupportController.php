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
 * @Route("/support")
 *
 */
class SupportController extends AbstractController {

    /**
     * @Route("/list")
     *
     * @return Response symfony response object
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin access to the module
     */
    public function listAction(Request $request) {
        // Permission check
        if (!$this->get('zikulaintercommodule.access_manager')->hasPermission()) {
            throw new AccessDeniedException();
        }

        $filter = array('page' => $request->query->get('page', null),
            'limit' => $request->query->get('limit', 25),
            'sortorder' => $request->query->get('sortorder', 'DESC'),
            'sortby' => $request->query->get('sortby', 'send'),
            'recipient' => \UserUtil::getVar('uid'),
            'deleted' => 'byrecipient'
        );

        $messages = new Messages();
        $messages->load($filter);

        $request->attributes->set('_legacy', true); // forces template to render inside old theme        
        return $this->render('ZikulaIntercomModule:User:inbox.html.twig', array(
                    'messages' => $messages->getmessages(),
                    'limit' => $filter['limit'],
                    'sortorder' => $filter['sortorder'],
                    'sortby' => $filter['sortby']
        ));
    }

}
