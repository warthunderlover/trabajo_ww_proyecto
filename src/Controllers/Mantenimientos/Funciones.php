<?php

namespace Controllers\Mantenimientos;

use Controllers\PrivateController;
use Views\Renderer;
use Dao\Funciones\Funciones as FuncionesDAO;

class Funciones extends PrivateController
{
    public function run(): void
    {
        $viewData = [];
        $tmpFunciones = FuncionesDAO::obtenerFunciones();
        $viewData["funciones"] = [];

        foreach ($tmpFunciones as $fn) {
            $viewData["funciones"][] = $fn;
        }

        $viewData["total"] = count($viewData["funciones"]);
        Renderer::render("mantenimientos/funciones/funciones", $viewData);
    }
}
