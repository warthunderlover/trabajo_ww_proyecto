<?php

namespace Controllers\Mantenimientos;

use Controllers\PrivateController;
use Views\Renderer;
use Dao\Usuario\Usuario as UsuarioDAO;

class Usuarios extends PrivateController
{
    public function run(): void
    {
        $viewData = [];
        $tmpUsuarios = UsuarioDAO::obtenerUsuarios();
        $viewData["usuarios"] = [];

        foreach ($tmpUsuarios as $usuario) {
            $usuarioNormalizado = $usuario;
            // obtener roles asignados (si existen)
            $rolesAsignados = UsuarioDAO::obtenerRolesPorUsuario(intval($usuario["usercod"]));
            $usuarioNormalizado["roles"] = [];
            foreach ($rolesAsignados as $r) {
                $usuarioNormalizado["roles"][] = $r;
            }

            $tipoMap = [
                "CLI" => "Cliente",
                "CON" => "Consultor",
                "NOR" => "Normal",
                "ADM" => "Administrador"
            ];
            $usuarioNormalizado["usertipoLabel"] = isset($tipoMap[$usuario["usertipo"]]) ? $tipoMap[$usuario["usertipo"]] : $usuario["usertipo"];

            $viewData["usuarios"][] = $usuarioNormalizado;
        }

        $viewData["total"] = count($viewData["usuarios"]);
        Renderer::render("mantenimientos/usuarios/usuarios", $viewData);
    }
}
