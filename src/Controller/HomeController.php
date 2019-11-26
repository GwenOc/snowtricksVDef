<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home")
     * @Template
     */
    public function index(TrickRepository $repository)
    {
    	$tricks = $repository->findBy([], ['id'=>'DESC'], 10);
        return ['tricks' => $tricks,];
    }

     /**
      * @Route("/loadmore", name="loadmore", methods="GET")
      * @param Request $req
      * @return JsonResponse
      */
     public function loadmore( TrickRepository $repository, Request $req)
     {
         $offset = $req->get('offset');
         $tricks = $repository->findMoreTrick($offset);
         $response = [
             'html' => $this->renderView('home/loadmore.html.twig', ['tricks' => $tricks])
         ];

         return $this->json(
             json_encode($response)
         );


     }
}
