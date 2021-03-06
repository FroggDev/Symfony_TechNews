<?php

namespace App\Controller;

use App\Service\LocaleService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;


/**
 * Class LocaleController
 * @package App\Controller
 */
class LocaleController extends Controller
{
    /**
     * @Route("/{_locale}/changelocale.html",name="change_locale")
     * @return Response
     */
    public function changeLocale(Request $request)
    {
        $localService = new LocaleService($request, null);
        # Return current route changed to other lang
        # $localService->changeSelectedLocale()
        return $this->redirect($localService->changeSelectedLocale(),Response::HTTP_MOVED_PERMANENTLY);
    }
}