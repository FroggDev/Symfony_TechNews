<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

/**
 * Class AccessDeniedHandler
 * @package App\Security
 */
class AccessDeniedHandler implements AccessDeniedHandlerInterface
{

    /**
     * Handles an access denied failure.
     *
     * @param Request $request
     * @param AccessDeniedException $accessDeniedException
     * @return Response
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException) : Response
    {
        return new Response("THIS ERROR HAS BEEN CATCHED BY THE HANDLER !");
    }
}