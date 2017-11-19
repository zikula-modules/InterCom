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
//use Zikula\IntercomModule\Form\Type\PreferencesType;
use Zikula\ThemeModule\Engine\Annotation\Theme;

/**
 * @Route("messages/admin/import")
 */
class ImportController extends AbstractController
{
    /**
     * @Route("/status", options={"expose"=true})
     *
     * @Theme("admin")
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

        $importHelper = $this->get('zikula_intercom_module.import_helper');

        if (0 === strpos($request->headers->get('Accept'), 'application/json')) {
            return new Response(json_encode([]));
        }

        return $this->render('ZikulaIntercomModule:Import:index.html.twig', [
            'importHelper' => $importHelper,
        ]);
    }

    /**
     * @Route("/import", options={"expose"=true})
     *
     * @Theme("admin")
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

        $content = $request->getContent();
        if (!empty($content)) {
            $data = json_decode($content, true); // 2nd param to get as array
        }

        $importHelper = $this->get('zikula_intercom_module.import_helper');
        $data = $importHelper->importData($data);

        return new Response(json_encode($data));
    }
}
