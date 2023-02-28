<?php

namespace App\Service;

use App\Entity\Pregunta;
use App\Entity\Respuesta;
use App\Entity\Producto;
use App\Entity\User;
use App\Repository\PreguntaRepository;
use App\Repository\RespuestaRepository;


class Preguntas_RespuestasManager
{
    private $preguntaRepository;
    private $respuestaRepository;


    public function __construct(PreguntaRepository $preguntaRepository, RespuestaRepository $respuestaRepository)
    {
        $this->preguntaRepository = $preguntaRepository;
        $this->respuestaRepository = $respuestaRepository;
    }


    public function crearNuevaPregunta(string $texto, Producto $producto, User $usuario): mixed
    { 
        $pregunta = new Pregunta();

        $fecha = new \DateTime('@'.strtotime('now'));
        $pregunta->setUser($usuario);
        $pregunta->setTexto($texto);
        $pregunta->setFecha($fecha);
        $pregunta->setProducto($producto);

        $this->preguntaRepository->save($pregunta, true);

        return $pregunta->jsonSerialize();
    }


    public function crearNuevaRespuesta(string $texto, Pregunta $pregunta, User $usuario): mixed
    { 
        $respuesta = new Respuesta;

        $fecha = new \DateTime('@'.strtotime('now'));
        $respuesta->setUser($usuario);
        $respuesta->setTexto($texto);
        $respuesta->setFecha($fecha);
        $respuesta->setPregunta($pregunta);

        $this->respuestaRepository->save($respuesta, true);

        return $respuesta->jsonSerialize();
    }


}