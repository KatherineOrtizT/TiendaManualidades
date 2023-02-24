<?php

namespace App\Service;

use App\Entity\Producto;
use App\Repository\ProductoRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductoManager
{
    private $productoRepository;
    private $requestStack;
    private $fileUploader;

    public function __construct(ProductoRepository $productoRepository, RequestStack $requestStack, FileUploader $fileUploader)
    {
        $this->productoRepository = $productoRepository;
        $this->requestStack = $requestStack;
        $this->fileUploader = $fileUploader;
    }

    public function crear(Producto $producto, UploadedFile $uploadedFile): void
    {
            /*'imagen' field is not required. The image file must be processed only when 
            a file is uploaded, not every time is edited*/
            if ($uploadedFile) {
                $imageFile = $this->fileUploader->upload($uploadedFile);

                // updates the 'imagen' property of Producto entity to store the imagen name (not the file)
                $producto->setImagen($imageFile);
            }
            $producto->setEstado();
            $this->productoRepository->save($producto, true);

    }


}