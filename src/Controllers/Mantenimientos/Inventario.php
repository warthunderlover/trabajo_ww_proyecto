<?php 

namespace Controllers\Mantenimientos;

use Controllers\PublicController;
use Views\Renderer;
use Dao\Inventarios\Inventario as InventarioDao;

class Inventario extends PublicController 
{
    public function run() :void
    {
        $viewData = [];
        $tmpProducto = InventarioDao::ObtenerTodos();
        $viewData["inventario"] = [];

        foreach($tmpProducto as $producto)
        {
            $viewData["inventario"][] = $producto;
        }

        Renderer::render("mantenimientos/Inventarios/inventario", $viewData);
        
    }   
    
}