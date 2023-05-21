<?php

namespace App\Service;

use App\Entity\Producto;
use App\Exception\StockException;
use App\Repository\ProductoRepository;


class ProductoService

{
    public const MIN_STOCK = 50;
    public function __construct(private ProductoRepository $productoRepository)
    {
    }


    public function list(): array
    {
        return $this->productoRepository->findAll();
    }

    public function vender(int $prodId, int $cantidad): Producto
    {

        $producto = $this->productoRepository->find($prodId);
        if ($producto != null) {

            $oldStock = $producto->getStock();
            if ($cantidad > 0) {

                if ($oldStock < ($cantidad)) {
                    throw new StockException("La cantidad de modificación no está permitida", $prodId, $cantidad, $oldStock);
                }
                $producto->setStock($oldStock - $cantidad);
                $this->productoRepository->save($producto, true);
            }
          
        }
        return $producto;
    }
}
