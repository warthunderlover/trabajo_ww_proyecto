<?php

namespace Controllers\Mantenimientos;

//use Controllers\AdminController;
use Controllers\PrivateController;
use Utilities\Site;
use Utilities\Validators;
use Views\Renderer;
use Dao\Usuario\Usuario as DAOUsuario;
use Exception;

const UsuarioList = "index.php?page=Mantenimientos-Usuarios";
const UsuarioView = "mantenimientos/usuarios/form";

class Usuario extends PrivateController
{
    private $modes = [
        "INS" => "Nuevo Usuario",
        "UPD" => "Editando usuario %s",
        "DSP" => "Detalle del usuario %s",
        "DEL" => "Eliminando usuario %s"
    ];

    private string $mode = '';
    private int $usercod = 0;
    private string $useremail = '';
    private string $username = '';
    private string $userpswd = '';
    private string $userfching = '';
    private string $userpswdest = '';
    private string $userpswdexp = '';
    private string $userest = '';
    private string $useractcod = '';
    private string $userpswdchg = '';
    private string $usertipo = '';

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
                                $affectedRows = DAOUsuario::crearUsuario(
                                    $this->useremail,
                                    $this->username,
                                    $this->userpswd,
                                    $this->userfching,
                                    $this->userpswdest,
                                    $this->userpswdexp,
                                    $this->userest,
                                    $this->useractcod,
                                    $this->userpswdchg,
                                    $this->usertipo
                                );
                                if ($affectedRows > 0) {
                                    // obtener id generado a partir del email y procesar roles
                                    $newUser = DAOUsuario::obtenerUsuarioPorEmail($this->useremail);
                                    if (isset($newUser["usercod"])) {
                                        $this->usercod = $newUser["usercod"];
                                    }
                                    $this->procesarRolesPost();
                                    Site::redirectToWithMsg(UsuarioList, "Usuario creado satisfactoriamente.");
                                }
                                break;
                            case "UPD":
                                $affectedRows = DAOUsuario::actualizarUsuario(
                                    $this->usercod,
                                    $this->useremail,
                                    $this->username,
                                    $this->userpswd,
                                    $this->userfching,
                                    $this->userpswdest,
                                    $this->userpswdexp,
                                    $this->userest,
                                    $this->useractcod,
                                    $this->userpswdchg,
                                    $this->usertipo
                                );
                                if ($affectedRows > 0) {
                                    $this->procesarRolesPost();
                                    Site::redirectToWithMsg(UsuarioList, "Usuario actualizado satisfactoriamente.");
                                }
                                break;
                            case "DEL":
                                $affectedRows = DAOUsuario::eliminarUsuario($this->usercod);
                                if ($affectedRows > 0) {
                                    Site::redirectToWithMsg(UsuarioList, "Usuario eliminado satisfactoriamente.");
                                }
                                break;
                        }
                    } catch (Exception $err) {
                        error_log($err);
                        $this->errores[] = $err->getMessage();
                    }
                }
            }
            Renderer::render(UsuarioView, $this->preparar_datos_vista());
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            Site::redirectToWithMsg(UsuarioList, "Sucedió un problema. Reintente nuevamente.");
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
                $tmpId = intval($_GET["id"]);
                $tmpUsuario = DAOUsuario::obtenerUsuarioPorId($tmpId);
                if (count($tmpUsuario) === 0) {
                    throw new Exception("No se encontró el registro");
                }
                $this->usercod = intval($tmpUsuario["usercod"] ?? 0);
                $this->useremail = $tmpUsuario["useremail"] ?? '';
                $this->username = $tmpUsuario["username"] ?? '';
                $this->userpswd = $tmpUsuario["userpswd"] ?? '';
                $this->userfching = $tmpUsuario["userfching"] ?? '';
                $this->userpswdest = $tmpUsuario["userpswdest"] ?? '';
                $this->userpswdexp = $tmpUsuario["userpswdexp"] ?? '';
                $this->userest = $tmpUsuario["userest"] ?? '';
                $this->useractcod = $tmpUsuario["useractcod"] ?? '';
                $this->userpswdchg = $tmpUsuario["userpswdchg"] ?? '';
                $this->usertipo = $tmpUsuario["usertipo"] ?? '';
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

        $this->usercod = intval($_POST["usercod"] ?? '0');
        $this->useremail = $_POST["useremail"] ?? '';
        $this->username = $_POST["username"] ?? '';
        $this->userpswd = $_POST["userpswd"] ?? '';
        $rawUserFching = $_POST["userfching"] ?? '';
        if (!empty($rawUserFching)) {
            $dt = \DateTime::createFromFormat('Y-m-d\\TH:i', $rawUserFching);
            if ($dt !== false) {
                $this->userfching = $dt->format('Y-m-d H:i:s');
            } else {
                $this->userfching = $rawUserFching;
            }
        } else {
            $this->userfching = '';
        }
        $this->userpswdest = $_POST["userpswdest"] ?? '';
        $rawUserPswdExp = $_POST["userpswdexp"] ?? '';
        if (!empty($rawUserPswdExp)) {
            $dt2 = \DateTime::createFromFormat('Y-m-d\\TH:i', $rawUserPswdExp);
            if ($dt2 !== false) {
                $this->userpswdexp = $dt2->format('Y-m-d H:i:s');
            } else {
                $this->userpswdexp = $rawUserPswdExp;
            }
        } else {
            $this->userpswdexp = '';
        }
        $this->userest = $_POST["userest"] ?? '';
        $this->useractcod = $_POST["useractcod"] ?? '';
        $this->userpswdchg = $_POST["userpswdchg"] ?? '';
        $this->usertipo = $_POST["usertipo"] ?? '';

        if (Validators::IsEmpty($this->useremail)) {
            $errors[] = "El correo electrónico no puede ir vacío.";
        }

        if (!in_array($this->userest, ["ACT", "INA", "BLQ"])) {
            $errors[] = "Estado de usuario inválido.";
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
            $viewData["modeDsc"] = sprintf($viewData["modeDsc"], $this->username);
        }

        $viewData["usercod"] = $this->usercod;
        $viewData["useremail"] = $this->useremail;
        $viewData["username"] = $this->username;
        $viewData["userpswd"] = $this->userpswd;
        // convert DB datetime (Y-m-d H:i:s) to HTML5 datetime-local (Y-m-d\TH:i)
        if (!empty($this->userfching)) {
            $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $this->userfching);
            if ($dt !== false) {
                $viewData["userfching"] = $dt->format('Y-m-d\\TH:i');
            } else {
                $viewData["userfching"] = $this->userfching;
            }
        } else {
            $viewData["userfching"] = '';
        }
        $viewData["userpswdest"] = $this->userpswdest;
        $viewData["userpswdexp"] = $this->userpswdexp;
        $viewData["userest"] = $this->userest;
        $viewData["useractcod"] = $this->useractcod;
        $viewData["userpswdchg"] = $this->userpswdchg;
        $viewData["usertipo"] = $this->usertipo;

        $tipoMap = [
            "CLI" => "Cliente",
            "CON" => "Consultor",
            "NOR" => "Normal",
            "ADM" => "Administrador"
        ];
        $tipoOptions = [];
        foreach ($tipoMap as $code => $label) {
            $tipoOptions[] = [
                "code" => $code,
                "label" => $label,
                "selected" => ($this->usertipo === $code) ? "selected" : ""
            ];
        }
        $viewData["usertipoOptions"] = $tipoOptions;

        $this->generarTokenDeValidacion();
        $viewData["token"] = $this->validationToken;

        $viewData["errores"] = $this->errores;
        $viewData["hasErrores"] = count($this->errores) > 0;

        $viewData["idReadonly"] = $this->mode !== "INS" ? "readonly" : "";
        $viewData["readonly"] = in_array($this->mode, ["DSP", "DEL"]) ? "readonly" : "";
        $viewData["isDisplay"] = $this->mode === "DSP";
        $viewData["selected" . $this->userest] = "selected";
        $allRoles = \Dao\Roles\Roles::obtenerRoles();
        // Mostrar sólo roles activos como seleccionables
        $allRoles = array_filter($allRoles, function($r) {
            return isset($r['rolesest']) && $r['rolesest'] === 'ACT';
        });
        $assigned = [];
        if ($this->mode !== "INS") {
            $asig = DAOUsuario::obtenerRolesPorUsuario($this->usercod);
            foreach ($asig as $a) {
                $assigned[] = $a["rolescod"];
            }
        }
        $rolesMarked = [];
        foreach ($allRoles as $r) {
            $r["checked"] = in_array($r["rolescod"], $assigned) ? "checked" : "";
            $rolesMarked[] = $r;
        }
        $viewData["roles"] = $rolesMarked;
        $viewData["userRoles"] = $assigned;
        $viewData["codigoINS"] = $this->mode ==="INS"?"style='display:none;'":"";
        
        return $viewData;
    }

    private function procesarRolesPost()
    {
        $roles = $_POST["roles"] ?? [];
        $usercod = $this->usercod;
        if ($this->mode === "INS") {
            if ($usercod === 0) return;
        }
        DAOUsuario::eliminarRolesPorUsuario($usercod);
        if (is_array($roles) && count($roles) > 0) {
            foreach ($roles as $r) {
                DAOUsuario::asignarRolAUsuario($usercod, $r);
            }
        }
    }
}
