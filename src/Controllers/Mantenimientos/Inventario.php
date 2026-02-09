<?php 

namespace Controllers\Mantenimientos;

use Controllers\PublicController;
use Controllers\PrivateController;
use Views\Renderer;
use Dao\Inventarios\Inventario as InventarioDao;

class Inventario extends PrivateController 
{
    private $partialName ="";

    private $product_DSP = false;
    private $product_DEL = false;
    private $product_UPD = false;
    private $product_INS = false;

    public function run() :void
    {
        $this->viewData = [];
        $this->getParamsFromContext();
        $tmpProducto = InventarioDao::ObtenerTodos();
        $this->viewData["inventario"] = [];
        $this->viewData["total_productos"] = count($tmpProducto);
        
        foreach($tmpProducto as $producto)
        {
            $this->viewData["inventario"][] = $producto;
        }
                
        $this->setParamsToDataView();

        Renderer::render("mantenimientos/Inventarios/inventario", $this->viewData);
        
    }  

    private function getParamsFromContext(): void
    {
        $this->product_DSP = $this->isFeatureAutorized("product_DSP");
        $this->product_DEL = $this->isFeatureAutorized("product_DEL");
        $this->product_UPD = $this->isFeatureAutorized("product_UPD");
        $this->product_INS = $this->isFeatureAutorized("product_INS");  

    }

    private function setParamsToDataView(): void
    {
        $this->viewData["product_DSP"] = $this->product_DSP;
        $this->viewData["product_DEL"] = $this->product_DEL;
        $this->viewData["product_UPD"] = $this->product_UPD;
        $this->viewData["product_INS"] = $this->product_INS;

    }

     
    
}