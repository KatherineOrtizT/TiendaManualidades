<?php

namespace App\Service;

use App\Entity\Compras;
use App\Entity\Pedidos;
use App\Entity\Producto;
use App\Entity\User;
use App\Repository\ComprasRepository;
use App\Repository\PedidosRepository;
use App\Repository\ProductoRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class ComprasManager
{
    private $requestStack;
    private $pedidosRepository;
    private $comprasRepository;
    private $productoRepository;

    public function __construct(RequestStack $requestStack, PedidosRepository $pedidosRepository, ProductoRepository $productoRepository, ComprasRepository $comprasRepository)
    {
        $this->requestStack = $requestStack;
        $this->pedidosRepository = $pedidosRepository;
        $this->comprasRepository = $comprasRepository;
        $this->productoRepository = $productoRepository;
    }


    public function crearNuevoPedido(Pedidos $pedido, User $usuario): Pedidos
    { 
        $pedido = new Pedidos();
        $fecha = new \DateTime('@'.strtotime('now'));
        $pedido->setDireccion("direccion default");
        $pedido->setFecha($fecha);
        $pedido->setIdUsuario($usuario);
        $this->pedidosRepository->save($pedido, true);

        return $pedido;
    }

    
    public function registrarCompras_Pedido(Pedidos $pedido): void
    {
        $session= $this->requestStack->getSession();

        foreach($session->get('carrito') as $product){
            $producto = $this->productoRepository->findOneBy(['id' => $product['producto']->getId()]);
            $compra = new Compras();
            $compra->setIdPedido($pedido);
            $compra->setIdProducto($producto);
            $compra->setUnidades($product['cantidad']);
            $compra->setPrecioCompra($product['producto']->getPrecio());
            $this->comprasRepository->save($compra, true);
        }

        $session->remove('carrito');
    }


}