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
}
