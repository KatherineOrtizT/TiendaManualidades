<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Service\CarritoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

class CarritoController extends AbstractController
{

    #[Route('/{id}/aniadirCarrito', name: 'app_carrito_aniadirProducto', methods: ['POST', 'GET'])]
    public function añadirProductoAction(Request $request, Producto $producto, CarritoManager $carritoManager): Response
    {
        $cantidad = $request->request->get('cantidad',null);

        $carritoManager->añadirA_Carrito($producto, $cantidad);

        return new JsonResponse(['suscess' => true]);
    
    }


    #[Route('/{id}/eliminarCarrito', name: 'app_carrito_eliminarProducto', methods: ['POST', 'GET'])]
    public function eliminarProductoAction($id, CarritoManager $carritoManager): Response
    {
        $carritoManager->eliminarDel_Carrito($id);

        return $this->redirectToRoute('app_carrito', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/carrito', name: 'app_carrito', methods: ['POST', 'GET'])]
    public function mostrarCarrito(): Response
    {        
        return $this->render('producto/carrito.html.twig', []);   
    }


}
