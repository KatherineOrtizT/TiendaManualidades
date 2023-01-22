<?php

namespace App\Controller;

use App\Repository\ProductoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/homepage')]
class HomepageController extends AbstractController
{

    #[Route('/', name: 'app_homepage_index', methods: ['GET'])]
    public function index(ProductoRepository $productoRepository): Response
    {
        return $this->render('/index.html.twig', [
            'productos' => $productoRepository->findAll(),
        ]);
    }

}
