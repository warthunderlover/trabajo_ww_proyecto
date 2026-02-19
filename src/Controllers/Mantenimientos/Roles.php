<?php

namespace Controllers\Mantenimientos;

use Controllers\PublicController;
use Views\Renderer;
use Dao\Roles\Roles as RolesDAO;

class Roles extends PublicController
{
    public function run(): void
    {
        $viewData = [];
        $tmpRoles = RolesDAO::obtenerRoles();
        $viewData["roles"] = [];

        foreach ($tmpRoles as $rol) {
            // obtener funciones asignadas al rol
            $funcs = RolesDAO::obtenerFuncionesPorRol($rol["rolescod"]);
            $rol["funciones"] = is_array($funcs) ? $funcs : [];
            $viewData["roles"][] = $rol;
        }

        $viewData["total"] = count($viewData["roles"]);
        Renderer::render("mantenimientos/roles/roles", $viewData);
    }
}
