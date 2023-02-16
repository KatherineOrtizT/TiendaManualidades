<?php

namespace App\Controller;

use App\Entity\Compras;
use App\Entity\Pedidos;
use App\Entity\Producto;
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

    #[Route('/admin', name: 'app_admin_panel')]
    public function redireccionarpanel()
    {
        return $this->render('admin/panel-admin.html.twig');
    }
    #[Route('/', name: 'app_SobreNosotros_panel')]
    public function sobreNosotros()
    {
        return $this->render('vistas/sobre-nosotras.html.twig');
    }

    #[Route('/contacto', name: 'app_contacto_panel')]
    public function contacto()
    {
        return $this->render('vistas/contacto.html.twig');
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
            return $this->redirectToRoute('app_homepage_index');
        }
        return $this->render('user/index.html.twig', [
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
        $pedido->setDireccion("direccion default");
        $pedido->setFecha($fecha);
        $usuario = $this->getUser();
        $pedido->setIdUsuario($usuario);
        $pedidosRepository->save($pedido, true);

        $session= $request->getSession();
        foreach($session->get('carrito') as $product){
            $producto = $this->em->getRepository(Producto::class)->findOneBy(['id' => $product['producto']->getId()]);
            $compra = new Compras();
            $compra->setIdPedido($pedido);
            $compra->setIdProducto($producto);
            $comprasRepository->save($compra, true);
        }

        $request->getSession()->remove('carrito');

        return $this->redirectToRoute('app_homepage_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/devolver/{compra}', name: 'app_user_devolver', methods: ['GET'])]
    public function devolverCompra(Compras $compra, ComprasRepository $comprasRepository): Response
    {
        //$pedido->removeCompra($compra);
        $comprasRepository->remove($compra, true);

        return $this->redirectToRoute('app_user_personal', [], Response::HTTP_SEE_OTHER);
    }

}
