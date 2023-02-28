<?php

namespace App\Controller;

use App\Entity\Compras;
use App\Entity\Pedidos;
use App\Entity\Pregunta;
use App\Entity\Producto;
use App\Entity\Respuesta;
use App\Entity\User;
use App\Form\UserType;
use App\Service\FileUploader;
use App\Service\ComprasManager;
use App\Repository\ComprasRepository;
use App\Repository\PedidosRepository;
use App\Repository\PreguntaRepository;
use App\Repository\RespuestaRepository;
use App\Repository\ProductoRepository;
use App\Service\Preguntas_RespuestasManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/user')]
class UserController extends AbstractController
{
    private $em;
    private $security;

    /**
     * @param $em
     */
    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }


    #[Route('/admin', name: 'app_admin_panel')]
    public function panelAdmin(ComprasRepository $comprasRepository, PedidosRepository $pedidosRepository): Response
    {
        $n_ventasHoy = $comprasRepository->getVentasHoy();
        $n_ventasTotal = $comprasRepository->getTotalVentas();
        $n_ingresosHoy = $comprasRepository->getIngresosHoy();
        $n_ingresosTotal = $comprasRepository->getTotalIngresos();
        
        return $this->render('admin/panel_admin.html.twig', [
            'n_ventasHoy' => $n_ventasHoy,
            'n_ventasTotal' => $n_ventasTotal,
            'n_ingresosHoy' => $n_ingresosHoy,
            'n_ingresosTotal' => $n_ingresosTotal,
            'ultimos_pedidos' => $pedidosRepository->ultimosCincoPedidos(),
        ]);
    }


    #[Route('/admin/catalogo', name: 'app_admin_catalogo')]
    public function catalogoAdmin(ProductoRepository $productoRepository): Response
    {
        return $this->render('admin/catalogo_admin.html.twig', [
            'productos' => $productoRepository->findAll(),
        ]);
    }


    #[Route('/admin/editar', name: 'app_admin_editar')]
    public function editarAdmin(ProductoRepository $productoRepository): Response
    {
        return $this->render('admin/edit_admin.html.twig', [
            'productos' => $productoRepository->findAll(),
        ]);
    }
    

    #[Route('/registration', name: 'userRegistration')]
    public function userRegistration(Request $request, UserPasswordHasherInterface $passwordHasher, FileUploader $fileUploader): Response
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

            $imageFile = $registration_form->get('photo')->getData();

            /*'imagen' field is not required. The image file must be processed only when 
            a file is uploaded, not every time is edited*/
            if ($imageFile) {
                $imageFile = $fileUploader->upload($imageFile);

                // updates the 'imagen' property of Producto entity to store the imagen name (not the file)
                $user->setPhoto($imageFile);
            }

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


    #[Route('/personal', name: 'app_user_personal', methods: ['GET'])]
    public function ir_areaPersonal(): Response
    {  
        $user= $this->getUser();
        return $this->render('user/show.html.twig', ['user'=>$user]);
    }


    #[Route('/comprar', name: 'app_user_comprar', methods: ['GET'])]
    public function finalizarCompra(ComprasManager $comprasManager): Response
    {
        $pedido = $comprasManager->crearNuevoPedido(new Pedidos(), $this->getUser());
        $comprasManager->registrarCompras_Pedido($pedido);

        return $this->redirectToRoute('app_homepage_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/devolver/{compra}', name: 'app_user_devolver', methods: ['GET'])]
    public function devolverCompra(Compras $compra, ComprasRepository $comprasRepository): Response
    {
        $comprasRepository->remove($compra, true);

        return $this->redirectToRoute('app_user_personal', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/publicarP/{producto}', name: 'app_user_publicarP', methods: ['POST', 'GET'])]
    public function publicarPregunta(Request $request, Producto $producto, Preguntas_RespuestasManager $p_rManager): Response
    {
        $preguntaJSON = $p_rManager->crearNuevaPregunta($request->request->get('textoPregunta', null), $producto, $this->getUser());
        
        return new JsonResponse($preguntaJSON);
    }

    
    #[Route('/publicarR/{pregunta}', name: 'app_user_publicarR', methods: ['POST', 'GET'])]
    public function publicarRespuesta(Request $request, Pregunta $pregunta, Preguntas_RespuestasManager $p_rManager): Response
    {
        $respuestaJSON = $p_rManager->crearNuevaRespuesta($request->request->get('textoRespuesta', null), $pregunta, $this->getUser());
        
        return new JsonResponse($respuestaJSON);
    }


    #[Route('/borrarP/{pregunta}', name: 'app_user_borrarP', methods: ['POST', 'GET'])]
    public function borrarPregunta(Pregunta $pregunta, PreguntaRepository $preguntaRepository): Response
    {
        if (!$this->security->isGranted('ROLE_ADMIN') && $pregunta->getUser() !== $this->security->getUser()) {
            return $this->render('errores/error403.html.twig', []);
        }
        $preguntaRepository->remove($pregunta, true);
        
        return new JsonResponse();
    }


    #[Route('/editarP/{pregunta}', name: 'app_user_editarP', methods: ['POST', 'GET'])]
    public function editarPregunta(Request $request, Pregunta $pregunta, PreguntaRepository $preguntaRepository): Response
    {
        if (!$this->security->isGranted('ROLE_ADMIN') && $pregunta->getUser() !== $this->security->getUser()) {
            return $this->render('errores/error403.html.twig', []);
        }
        $texto = $request->request->get('textoPregunta', null);
        $pregunta->setTexto($texto);

        $preguntaRepository->save($pregunta, true);
        
        return new JsonResponse(['nuevoTexto' => $pregunta->getTexto()]);
    }


    #[Route('/borrarR/{respuesta}', name: 'app_user_borrarR', methods: ['POST', 'GET'])]
    public function borrarRespuesta(Respuesta $respuesta, RespuestaRepository $respuestaRepository): Response
    {
        if (!$this->security->isGranted('ROLE_ADMIN') && $respuesta->getUser() !== $this->security->getUser()) {
            return $this->render('errores/error403.html.twig', []);
        }
        $respuestaRepository->remove($respuesta, true);
        
        return new JsonResponse();
    }


    #[Route('/editarR/{respuesta}', name: 'app_user_editarR', methods: ['POST', 'GET'])]
    public function editarRespuesta(Request $request, Respuesta $respuesta, RespuestaRepository $respuestaRepository): Response
    {   
        if (!$this->security->isGranted('ROLE_ADMIN') && $respuesta->getUser() !== $this->security->getUser()) {
            return $this->render('errores/error403.html.twig', []);
        }
        $texto = $request->request->get('textoRespuesta', null);
        $respuesta->setTexto($texto);

        $respuestaRepository->save($respuesta, true);
        
        return new JsonResponse(['nuevoTexto' => $respuesta->getTexto()]);
    }


}
