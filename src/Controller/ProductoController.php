<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Form\ProductoType;
use App\Repository\ProductoRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/producto')]
class ProductoController extends AbstractController
{

    private $em;

    /**
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /* #[Route('/', name: 'app_producto_index', methods: ['GET'])]
    public function index(ProductoRepository $productoRepository): Response
    {
        return $this->render('producto/index.html.twig', [
            'productos' => $productoRepository->findAll(),
        ]);
    } */

    #[Route('/new', name: 'app_producto_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductoRepository $productoRepository, FileUploader $fileUploader): Response
    {
        $producto = new Producto();
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imagen')->getData();

            /*'imagen' field is not required. The image file must be processed only when 
            a file is uploaded, not every time is edited*/
            if ($imageFile) {
                $imageFile = $fileUploader->upload($imageFile);

                // updates the 'imagen' property of Producto entity to store the imagen name (not the file)
                $producto->setImagen($imageFile);
            }
            $producto->setEstado("Disponible");
            $productoRepository->save($producto, true);

            return $this->redirectToRoute('app_homepage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/panel-admin.html.twig', [
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
        $busqueda = $request->query->get('busqueda',null);
        $resultadoBusqueda = $productoRepository->search($busqueda);

        return $this->render('producto/lista_productos.html.twig', [
            /* 'busqueda' => $busqueda, */
            'productos' => $resultadoBusqueda,
        ]);
    }

    #[Route('/', name: 'app_producto_catalogo', methods: ['GET'])]
    public function catalogo(ProductoRepository $productoRepository): Response
    {
        return $this->render('producto/lista_productos.html.twig', [
            'productos' => $productoRepository->findAll(),
        ]);
    }

    #[Route('/{id}/aniadirCarrito', name: 'app_producto_carrito', methods: ['POST', 'GET'])]
    public function añadirProductoAction(Request $request, Producto $producto): Response
    {
        $cantidad = $request->request->get('cantidad',null);
        $session= $request->getSession();
        //$session->remove('carrito');
        //$session->clear();
        $carrito = $session->get('carrito', []);
        $carrito[]=['producto'=>$producto, 'cantidad'=>$cantidad];
        $session->set('carrito', $carrito);

        /* $carrito = $session->get('carrito', []);
        $session->set('carrito', array(
            array_merge($carrito, [$producto]) 
        )); */
        //return $this->redirectToRoute('app_user_carrito', [], Response::HTTP_SEE_OTHER);
        
        if($request->request->get('cantidad')){
            $arr = json_encode($producto->getId());
            return new JsonResponse($arr);
        }
    
    }


    #[Route('/{id}/eliminarCarrito', name: 'app_producto_eliminar_carrito', methods: ['POST', 'GET'])]
    public function eliminarProductoAction(Request $request, Producto $producto): Response
    {
        $session= $request->getSession();
        $carrito = $session->get('carrito');
        foreach($carrito as $index => $elemento){
            $productoArray = $this->em->getRepository(Producto::class)->findOneBy(['id' => $elemento['producto']->getId()]);
            if($productoArray->getId() == $producto->getId()){
                unset($carrito[$index]);
            }
        }

        return $this->redirectToRoute('app_user_carrito', [], Response::HTTP_SEE_OTHER);
    
        /* foreach($elemento as $atributo){
            if($atributo->getId() == $id){
                unset($carrito[$elemento]);
            }
        } */
    }

}
