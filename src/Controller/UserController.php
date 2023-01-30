<?php

namespace App\Controller;

use App\Entity\Compras;
use App\Entity\Pedidos;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\ComprasRepository;
use App\Repository\PedidosRepository;
use App\Repository\ProductoRepository;
use Doctrine\DBAL\Schema\Identifier;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    private $em;

    /**
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/registration', name: 'userRegistration')]
    public function userRegistration(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user= new User();
        $registration_form=$this->createForm(UserType::class, $user);
        $registration_form->handleRequest($request);
        if($registration_form->isSubmitted()&&$registration_form->isValid()){
            $plaintextPassword =$registration_form->get('password')->getData();
            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_USER']);
            $this->em->persist($user);
            $this->em->flush();
            return $this->redirectToRoute('userRegistration');
        }
        return $this->render('comunes/_header.html.twig', [
            'registration_form' => $registration_form->createView()
        ]);
    }

    #[Route('/carrito', name: 'app_user_carrito', methods: ['POST', 'GET'])]
    public function mostrarCarritoAction(Request $request, ProductoRepository $productoRepository): Response
    {
        $idProductosCarrito = $request->request->get('productos',null);
        $productosCarrito= array();
        if($idProductosCarrito){
            foreach(json_decode($idProductosCarrito) as $idProducto){
                $productosCarrito[] = $productoRepository->find($idProducto);
            }
           
            return $this->render('producto/carrito.html.twig', [
                'productos' => $productosCarrito,
            ]);

        }
        
        return $this->render('producto/carrito.html.twig', []);   
    }

    #[Route('/personal', name: 'app_user_personal', methods: ['GET'])]
    public function ir_areaPersonal(): Response
    {
        $user= $this->getUser();
        return $this->render('user/show.html.twig', ['user'=>$user]);
    }

    #[Route('/comprar', name: 'app_user_comprar', methods: ['GET'])]
    public function finalizarCompra(Request $request, PedidosRepository $pedidosRepository, ComprasRepository $comprasRepository): Response
    {
        $pedido = new Pedidos();
        $fecha = new \DateTime('@'.strtotime('now'));
        $pedido->setFecha($fecha);
        $idUsuario = ($this->getUser()->get_current_user)->getId();
        $pedido->setIdUsuario($idUsuario);
        $pedidosRepository->save($pedido, true);

        $session= $request->getSession();
        foreach($session->get('carrito') as $producto){
            $compra = new Compras();
            $compra->setIdPedido($pedido->getId());
            $compra->setIdProducto($producto->getId());
            $comprasRepository->save($compra, true);
        }

        return $this->redirectToRoute('app_homepage_index', [], Response::HTTP_SEE_OTHER);
    }


    /* #[Route('/{id}/pedidos', name: 'app_user_pedidos', methods: ['GET'])]
    public function listarPedidos(Request $request, Pedidos $pedidos): Response
    {
        return $this->render('user/show.html.twig', [
            'pedidos' => $pedidos
        ]);
    } */


   /*  #[Route('/{id_pedido}/compras', name: 'app_user_compras', methods: ['GET'])]
    public function listarComprasPorPedido(int $id_pedido, Request $request, ComprasRepository $comprasRepository): Response
    {
        /* $pedido = $pedidosRepository->findOneById($id_pedido);
        if($pedido === null){
            throw $this->createNotFoundException();
        }

        $compras = $comprasRepository->findBy(array('idPedido' => $id_pedido));
        return $this->render('user/show.html.twig', [
            'compras' => $compras
        ]);
    } */

}
