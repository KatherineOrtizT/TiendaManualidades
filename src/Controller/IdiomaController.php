<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\ProductoRepository;


class IdiomaController extends AbstractController
{
    #[Route('/idioma/{_locale}', name: 'app_homepage')] 
    public function index(Request $request,ProductoRepository $productoRepository): Response
    {
        
        // Obtiene el idioma seleccionado por el usuario
        //$idiomaActual =$request->getLocale();
        $ruta=$request->request->get('ruta');
         // Traduce un texto utilizando el servicio de traducción
        //$texto = $this->get('translator')->trans('mi_texto_a_traducir');
        $locale = $request->getLocale();
        $metodo=$request->getMethod();
        if("POST"==$metodo){
            //$ruta=$request->request->get('ruta');
            return $this->redirectToRoute($ruta);
        }
       
         // Renderiza la vista utilizando el idioma seleccionado y el texto traducido
          return $this->render('index.html.twig', [
            'ruta'=>$ruta,
             '_locale' => $locale,
             'productos' => $productoRepository->findAll(),
         ]);
    }
}
