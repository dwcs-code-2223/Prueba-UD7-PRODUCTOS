<?php 

namespace App\Exception;

class StockException extends \Exception
{

   
	 // Redefine the exception so message isn't optional
     public function __construct($message, private int $prodId, private int $cantidad, private int $oldStock) {
        // some code

        // make sure everything is assigned properly
        parent::__construct($message);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [Producto: {$this->prodId}, Antiguo Stock: {$this->oldStock}, Cantidad solicitad para modificación: {$this->cantidad}]: Mensaje: {$this->message}\n ";
    }

  
    
}
