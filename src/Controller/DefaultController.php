<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return new Response(
            '<html><body>Hello there... I am an <strong>index page</strong></body></html>'
        );
    }

    /**
     * @Route("/hello/{string}")
     * @param $string
     * @return Response
     */
    public function hello($string)
    {
        return $this->render('hello.html.twig', array(
            'string' => $string,
        ));
    }
}