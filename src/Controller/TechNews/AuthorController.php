<?php
namespace App\Controller\TechNews;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
{

    /**
     * @Route("/author/{firstname}-{lastname}_{id}.html",
     *      name="index_author",
     *      methods={"GET"},
     *      requirements={"id" : "\d+"})
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $id
     * @return Response
     * @TODO create template and test values
     */

    public function index($id,$firstName,$lastName)
    {
        return new Response("TODO $firstName $lastName $id");
    }

}