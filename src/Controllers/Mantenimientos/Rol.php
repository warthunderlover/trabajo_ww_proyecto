<?php

namespace Controllers\Mantenimientos;

use Controllers\PublicController;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;
use Dao\Roles\Roles as RolesDAO;
use Dao\Funciones\Funciones as FuncionesDAO;
use Exception;

const RolList = "index.php?page=Mantenimientos-Roles";
const RolView = "mantenimientos/roles/form";

class Rol extends PublicController
{
    private $modes = [
        "INS" => "Nuevo Rol",
        "UPD" => "Editando rol %s",
        "DSP" => "Detalle del rol %s",
        "DEL" => "Eliminando rol %s"
    ];

    private string $mode = '';
    private string $rolescod = '';
    private string $rolesdsc = '';
    private string $rolesest = 'ACT';

    private string $validationToken = '';
    private array $errores = [];

    public function run(): void
    {
        try {
            $this->page_init();
            if ($this->isPostBack()) {
                $this->errores = $this->validarPostData();
                if (count($this->errores) === 0) {
                    try {
                        switch ($this->mode) {
                            case "INS":
                                $affected = RolesDAO::crearRol($this->rolescod, $this->rolesdsc, $this->rolesest);
                                if ($affected > 0) {
                                    // procesar funciones asignadas
                                    $this->procesarFuncionesPost();
                                    Site::redirectToWithMsg(RolList, "Rol creado satisfactoriamente.");
                                }
                                break;
                            case "UPD":
                                $affected = RolesDAO::actualizarRol($this->rolescod, $this->rolesdsc, $this->rolesest);
                                if ($affected > 0) {
                                    $this->procesarFuncionesPost();
                                    Site::redirectToWithMsg(RolList, "Rol actualizado satisfactoriamente.");
                                }
                                break;
                            case "DEL":
                                $affected = RolesDAO::eliminarRol($this->rolescod);
                                if ($affected > 0) {
                                    Site::redirectToWithMsg(RolList, "Rol eliminado satisfactoriamente.");
                                }
                                break;
                        }
                    } catch (Exception $err) {
                        error_log($err, 0);
                        $this->errores[] = $err->getMessage();
                    }
                }
            }
            Renderer::render(RolView, $this->preparar_datos_vista());
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            Site::redirectToWithMsg(RolList, "Sucedió un problema. Reintente nuevamente.");
        }
    }

    private function page_init()
    {
        if (isset($_GET["mode"]) && isset($this->modes[$_GET["mode"]])) {
            $this->mode = $_GET["mode"];
            if ($this->mode !== "INS") {
                if (!isset($_GET["id"])) {
                    throw new Exception("ID no es válido");
                }
                $tmpId = $_GET["id"];
                $tmpRol = RolesDAO::obtenerRolPorCodigo($tmpId);
                if (count($tmpRol) === 0) {
                    throw new Exception("No se encontró el registro");
                }
                $this->rolescod = $tmpRol["rolescod"];
                $this->rolesdsc = $tmpRol["rolesdsc"];
                $this->rolesest = $tmpRol["rolesest"];
            }
        } else {
            throw new Exception("Valor de Mode no es válido");
        }
    }

    private function validarPostData(): array
    {
        $errors = [];
        $this->validationToken = $_POST["vlt"] ?? '';
        if (isset($_SESSION[$this->name . "_token"]) && $_SESSION[$this->name . "_token"] !== $this->validationToken) {
            throw new Exception("Error de validación de Token");
        }

        $this->rolescod = $_POST["rolescod"] ?? '';
        $this->rolesdsc = $_POST["rolesdsc"] ?? '';
        $this->rolesest = $_POST["rolesest"] ?? '';

        if (Validators::IsEmpty($this->rolescod)) {
            $errors[] = "Código de rol no puede ir vacío.";
        }
        if (!in_array($this->rolesest, ["ACT", "INA", "BLQ"])) {
            $errors[] = "Estado de rol inválido.";
        }

        return $errors;
    }

    private function generarTokenDeValidacion()
    {
        $this->validationToken = md5(gettimeofday(true) . $this->name . rand(1000, 9999));
        $_SESSION[$this->name . "_token"] = $this->validationToken;
    }

    private function preparar_datos_vista()
    {
        $viewData = [];
        $viewData["mode"] = $this->mode;
        $viewData["modeDsc"] = $this->modes[$this->mode];
        if ($this->mode !== "INS") {
            $viewData["modeDsc"] = sprintf($viewData["modeDsc"], $this->rolesdsc);
        }
        $viewData["rolescod"] = $this->rolescod;
        $viewData["rolesdsc"] = $this->rolesdsc;
        $viewData["rolesest"] = $this->rolesest;

        $this->generarTokenDeValidacion();
        $viewData["token"] = $this->validationToken;

        $viewData["errores"] = $this->errores;
        $viewData["hasErrores"] = count($this->errores) > 0;

        $viewData["idReadonly"] = $this->mode !== "INS" ? "readonly" : "";
        $viewData["readonly"] = in_array($this->mode, ["DSP", "DEL"]) ? "readonly" : "";
        $viewData["isDisplay"] = $this->mode === "DSP";
        $viewData["selected" . $this->rolesest] = "selected";

        // funciones disponibles y asignadas
        $allFunciones = FuncionesDAO::obtenerFunciones();
        // Keep only active functions for assignable list
        $allFunciones = array_filter($allFunciones, function($f) {
            return isset($f['fnest']) && $f['fnest'] === 'ACT';
        });
        $assigned = [];
        if ($this->mode !== "INS") {
            $asig = RolesDAO::obtenerFuncionesPorRol($this->rolescod);
            foreach ($asig as $a) {
                $assigned[] = $a["fncod"];
            }
        }
        $funcsMarked = [];
        foreach ($allFunciones as $f) {
            $f["checked"] = in_array($f["fncod"], $assigned) ? "checked" : "";
            $funcsMarked[] = $f;
        }
        $viewData["funciones"] = $funcsMarked;
        $viewData["rolFunciones"] = $assigned;
        $viewData["codigoINS"] = $this->mode ==="INS"?"style='display:none;'":"";


        return $viewData;
    }

    private function procesarFuncionesPost()
    {
        $funciones = $_POST["funciones"] ?? [];
        $rolescod = $this->rolescod;
        if ($this->mode === "INS") {
            // si no viene el id en el formulario, no procesamos aquí
            if (empty($rolescod)) return;
        }
        RolesDAO::eliminarFuncionesPorRol($rolescod);
        if (is_array($funciones) && count($funciones) > 0) {
            // usar método transaccional
            RolesDAO::transaccionalAsignacionFunciones($rolescod, $funciones);
        }
    }
}

