<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class IndexController extends AbstractController
{
    #[Route('')]
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            'api_endpoint' => $this->getParameter('app.api_endpoint')
        ]);
    }
}
