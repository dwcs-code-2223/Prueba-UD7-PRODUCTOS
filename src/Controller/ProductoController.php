<?php

namespace App\Controller;

use App\Exception\StockException;
use App\Service\ProductoService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductoController extends AbstractController
{
    #[Route('/producto', name: 'app_producto')]
    public function index(ProductoService $productoService): Response
    {
        $productos = $productoService->list();
        return $this->render('producto/index.html.twig', [
            'productos' => $productos,
            'min_stock' => ProductoService::MIN_STOCK
        ]);
    }
    #[Route('/producto/stock/{prodId<([1-9]+\d*)>}/{cantidad<([1-9]+\d*)>}', name: 'app_producto_stock_unidades')]
    public function vender(ProductoService $productoService, LoggerInterface $logger, int $prodId, int $cantidad=1)
    {

        try {

            $producto = $productoService->vender($prodId, $cantidad);
            $this->addFlash("success", "Se han vendido $cantidad unidades. Quedan disponibles". $producto->getStock());
        } catch (StockException $se) {
            $this->addFlash("danger", "La cantidad introducida no permite su venta de acuerdo al stock actual");
          
        } catch (\Exception $e) {
            $this->addFlash("danger", "Ha ocurrido un error y no se ha podido vender $cantidad unidades");
            $logger->error("Ha ocurrido una exception:". $e->getMessage());
        }

        return $this->redirectToRoute("app_producto");
    }
}
