<?php

namespace App\Controller;

use App\Repository\ProductoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class HomepageController extends AbstractController
{

    #[Route('/homepage/{_locale}', name: 'app_homepage_index', methods: ['GET'], requirements: ['_locale' => 'en|es'])]
    public function index(ProductoRepository $productoRepository): Response
    {
        $productos[] = $productoRepository->findAll_limit8();
        $productos[] = $productoRepository->findProductsByNovelty();
        $productos[] = $productoRepository->findProductsByDiscount();
        $productos[] = $productoRepository->findTopSellingProducts();

        return $this->render('/index.html.twig', [
            'productos' => $productos,
        ]);
    }


    #[Route('/contacto/{_locale}', name: 'app_contacto_panel', methods: ['GET'], requirements: ['_locale' => 'en|es'])]
    public function contacto()
    {
        return $this->render('vistas/contacto.html.twig');
    }


    #[Route('/aboutUs/{_locale}', name: 'app_SobreNosotros_panel', methods: ['GET'], requirements: ['_locale' => 'en|es'])]
    public function sobreNosotros()
    {
        return $this->render('vistas/sobre-nosotras.html.twig');
    }


}
