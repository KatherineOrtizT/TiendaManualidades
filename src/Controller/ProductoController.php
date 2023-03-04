<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Form\ProductoType;
use App\Repository\ProductoRepository;
use App\Service\ProductoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/producto')]
class ProductoController extends AbstractController
{

    #[Route('/catalogo', name: 'app_producto_catalogo', methods: ['GET'])]
    public function catalogo(ProductoRepository $productoRepository): Response
    {
        return $this->render('producto/lista_productos.html.twig', [
            'productos' => $productoRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_producto_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductoManager $productoManager): Response
    {
        $producto = new Producto();
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imagen')->getData();
            
            $productoManager->crear($producto, $imageFile);

            return $this->redirectToRoute('app_producto_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('producto/new.html.twig', [
            'producto' => $producto,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_producto_show', methods: ['GET', 'POST'])]
    public function show(Producto $producto): Response
    {
        $preguntas = $producto->getPreguntas();
        return $this->render('producto/show.html.twig', [
            'producto' => $producto,
            'preguntas' => $preguntas,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_producto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Producto $producto, ProductoRepository $productoRepository): Response
    {
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productoRepository->save($producto, true);

            return $this->redirectToRoute('app_homepage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('producto/edit.html.twig', [
            'producto' => $producto,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/delete', name: 'app_producto_delete', methods: ['POST'])]
    public function delete(Request $request, Producto $producto, ProductoRepository $productoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$producto->getId(), $request->request->get('_token'))) {
            $productoRepository->remove($producto, true);
        }

        return $this->redirectToRoute('app_homepage_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/search', name: 'app_producto_search', methods: ['POST', 'GET'])]
    public function buscarProductos(Request $request, ProductoRepository $productoRepository): Response
    {
        $busqueda = $request->query->get('busqueda');
        
        $resultadoBusqueda = $productoRepository->search($busqueda);

        return $this->render('producto/lista_productos.html.twig', [
            'productos' => $resultadoBusqueda,
        ]);
    }

}
