<?php

namespace Controllers\Mantenimientos;

use Controllers\PublicController;
use Utilities\Site;
use Utilities\Validators;
use Views\Renderer;
use Dao\Funciones\Funciones as DAOFunciones;
use Exception;

const FuncionList = "index.php?page=Mantenimientos-Funciones";
const FuncionView = "mantenimientos/funciones/form";

class Funcion extends PublicController
{
    private $modes = [
        "INS" => "Nueva Función",
        "UPD" => "Editando función %s",
        "DSP" => "Detalle de función %s",
        "DEL" => "Eliminando función %s"
    ];

    private string $mode = '';
    private string $fncod = '';
    private string $fndsc = '';
    private string $fnest = '';
    private string $fntyp = '';

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
                                $affectedRows = DAOFunciones::crearFuncion(
                                    $this->fncod,
                                    $this->fndsc,
                                    $this->fnest,
                                    $this->fntyp
                                );
                                if ($affectedRows > 0) {
                                    Site::redirectToWithMsg(FuncionList, "Función creada satisfactoriamente.");
                                }
                                break;
                            case "UPD":
                                $affectedRows = DAOFunciones::actualizarFuncion(
                                    $this->fncod,
                                    $this->fndsc,
                                    $this->fnest,
                                    $this->fntyp
                                );
                                if ($affectedRows > 0) {
                                    Site::redirectToWithMsg(FuncionList, "Función actualizada satisfactoriamente.");
                                }
                                break;
                            case "DEL":
                                $affectedRows = DAOFunciones::eliminarFuncion($this->fncod);
                                if ($affectedRows > 0) {
                                    Site::redirectToWithMsg(FuncionList, "Función eliminada satisfactoriamente.");
                                }
                                break;
                        }
                    } catch (Exception $err) {
                        error_log($err);
                        $this->errores[] = $err->getMessage();
                    }
                }
            }
            Renderer::render(FuncionView, $this->preparar_datos_vista());
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            Site::redirectToWithMsg(FuncionList, "Sucedió un problema. Reintente nuevamente.");
        }
    }

    private function page_init()
    {
        if (isset($_GET["mode"]) && isset($this->modes[$_GET["mode"]])) {
            $this->mode = $_GET["mode"];
            if ($this->mode !== "INS") {
                if (!isset($_GET["id"])) {
                    throw new Exception("Código no es válido");
                }
                $tmpId = $_GET["id"];
                $tmpFn = DAOFunciones::obtenerFuncionPorCodigo($tmpId);
                if (count($tmpFn) === 0) {
                    throw new Exception("No se encontró el registro");
                }
                $this->fncod = $tmpFn["fncod"];
                $this->fndsc = $tmpFn["fndsc"];
                $this->fnest = $tmpFn["fnest"];
                $this->fntyp = $tmpFn["fntyp"];
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

        $this->fncod = $_POST["fncod"] ?? '';
        $this->fndsc = $_POST["fndsc"] ?? '';
        $this->fnest = $_POST["fnest"] ?? '';
        $this->fntyp = $_POST["fntyp"] ?? '';

        if (Validators::IsEmpty($this->fncod)) {
            $errors[] = "El código de la función no puede ir vacío.";
        }

        if (!in_array($this->fnest, ["ACT", "INA", "BLQ"])) {
            $errors[] = "Estado inválido.";
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
            $viewData["modeDsc"] = sprintf($viewData["modeDsc"], $this->fncod);
        }

        $viewData["fncod"] = $this->fncod;
        $viewData["fndsc"] = $this->fndsc;
        $viewData["fnest"] = $this->fnest;
        $viewData["fntyp"] = $this->fntyp;

        $this->generarTokenDeValidacion();
        $viewData["token"] = $this->validationToken;

        $viewData["errores"] = $this->errores;
        $viewData["hasErrores"] = count($this->errores) > 0;

        $viewData["idReadonly"] = $this->mode !== "INS" ? "readonly" : "";
        $viewData["readonly"] = in_array($this->mode, ["DSP", "DEL"]) ? "readonly" : "";
        $viewData["isDisplay"] = $this->mode === "DSP";
        $viewData["selected" . $this->fnest] = "selected";

        return $viewData;
    }
}
