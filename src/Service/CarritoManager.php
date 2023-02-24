<?php

namespace App\Service;

use App\Entity\Producto;
use Symfony\Component\HttpFoundation\RequestStack;

class CarritoManager
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }


    public function añadirA_Carrito(Producto $producto, int $cantidad = 1): void
    {
        $session = $this->requestStack->getSession();
        $carrito = $session->get('carrito', []);
        $carrito[]=['producto'=>$producto, 'cantidad'=>$cantidad];
        $session->set('carrito', $carrito);
        //$session->remove('carrito');
        //$session->clear();
    }

    public function eliminarDel_Carrito($id): void
    {
        $session = $this->requestStack->getSession();
        $carrito = $session->get('carrito');
        foreach($carrito as $index => $elemento){
            if($elemento['producto']->getId() == $id){
                unset($carrito[$index]);
                break;
            }
        }

        // Actualizar el carrito en la sesión
        $session->set('carrito', $carrito);
    }


}