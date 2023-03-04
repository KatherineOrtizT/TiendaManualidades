<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class IdiomaController extends AbstractController
{

    #[Route('/idioma/{_locale}', name: 'cambiar_idioma')]
    public function cambiarIdioma(Request $request): Response
    {
        $ruta = $request->request->get('ruta');
        $idioma = $request->getLocale();
        if ($ruta) {
            return $this->redirectToRoute($ruta, ['_locale' => $idioma]);
        }
        return $this->redirectToRoute('app_homepage_index', ['_locale' => $idioma]);
    }

}
