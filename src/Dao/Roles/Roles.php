<?php

namespace Dao\Roles;

use Dao\Table;

class Roles extends Table
{
    public static function obtenerRoles(): array
    {
        $sqlstr = "SELECT * FROM roles;";
        return self::obtenerRegistros($sqlstr, []);
    }

    public static function obtenerRolPorCodigo(string $rolescod): array
    {
        $sqlstr = "SELECT * FROM roles WHERE rolescod = :rolescod;";
        return self::obtenerUnRegistro($sqlstr, ["rolescod" => $rolescod]);
    }

    public static function crearRol(
        string $rolescod,
        string $rolesdsc,
        string $rolesest
    ) {
        $insSql = "INSERT INTO roles 
                   (rolescod, rolesdsc, rolesest)
                   VALUES 
                   (:rolescod, :rolesdsc, :rolesest);";

        $params = [
            "rolescod" => $rolescod,
            "rolesdsc" => $rolesdsc,
            "rolesest" => $rolesest
        ];

        return self::executeNonQuery($insSql, $params);
    }

    public static function actualizarRol(
        string $rolescod,
        string $rolesdsc,
        string $rolesest
    ) {
        // If attempting to inactivate or block a role, ensure no active users are assigned
        if ($rolesest !== 'ACT') {
            $checkSql = "SELECT COUNT(*) as cnt FROM roles_usuarios WHERE rolescod = :rolescod AND roleuserest = 'ACT';";
            $res = self::obtenerUnRegistro($checkSql, ["rolescod" => $rolescod]);
            $cnt = isset($res['cnt']) ? intval($res['cnt']) : 0;
            if ($cnt > 0) {
                throw new \Exception("No puede inactivar/bloquear el rol porque está asignado a usuarios activos ({$cnt}).");
            }
        }

        $updSql = "UPDATE roles SET 
                   rolesdsc = :rolesdsc,
                   rolesest = :rolesest
                   WHERE rolescod = :rolescod;";

        $params = [
            "rolescod" => $rolescod,
            "rolesdsc" => $rolesdsc,
            "rolesest" => $rolesest
        ];

        return self::executeNonQuery($updSql, $params);
    }

    public static function eliminarRol(string $rolescod)
    {
        // Check for existing user assignments that would block deletion
        $checkSql = "SELECT ru.usercod, u.useremail, u.username, ru.roleuserest
                     FROM roles_usuarios ru
                     LEFT JOIN usuario u ON ru.usercod = u.usercod
                     WHERE ru.rolescod = :rolescod";
        $deps = self::obtenerRegistros($checkSql, ["rolescod" => $rolescod]);
        if (is_array($deps) && count($deps) > 0) {
            $parts = array_map(function ($d) {
                $code = $d['usercod'] ?? '(sin id)';
                $name = $d['username'] ?? '';
                $st = $d['roleuserest'] ?? '';
                return trim("{$code} ({$name}) [estado: {$st}]");
            }, $deps);
            $msg = "No puede eliminar el rol porque está asignado a usuarios: " . implode(", ", $parts);
            throw new \Exception($msg);
        }

        $delSql = "DELETE FROM roles WHERE rolescod = :rolescod;";
        return self::executeNonQuery($delSql, ["rolescod" => $rolescod]);
    }

    public static function obtenerFuncionesPorRol(string $rolescod): array
    {
        // Only return functions that are active and whose relation is active
        $sqlstr = "SELECT f.fncod, f.fndsc, fr.fnrolest, fr.fnexp
                   FROM funciones_roles fr
                   JOIN funciones f ON fr.fncod = f.fncod
                   WHERE fr.rolescod = :rolescod
                     AND f.fnest = 'ACT'
                     AND fr.fnrolest = 'ACT';";
        return self::obtenerRegistros($sqlstr, ["rolescod" => $rolescod]);
    }

    public static function eliminarFuncionesPorRol(string $rolescod)
    {
        $delSql = "DELETE FROM funciones_roles WHERE rolescod = :rolescod;";
        return self::executeNonQuery($delSql, ["rolescod" => $rolescod]);
    }

    public static function asignarFuncionARol(string $rolescod, string $fncod, string $fnrolest = 'ACT')
    {
        $insSql = "INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES (:rolescod, :fncod, :fnrolest, NULL);";
        return self::executeNonQuery($insSql, ["rolescod" => $rolescod, "fncod" => $fncod, "fnrolest" => $fnrolest]);
    }

    public static function transaccionalAsignacionFunciones(string $rolescod, array $fncods)
    {
        $conn = self::getConn();
        try {
            $conn->beginTransaction();
            // eliminar existentes usando la misma conexión
            $delSql = "DELETE FROM funciones_roles WHERE rolescod = :rolescod;";
            self::executeNonQuery($delSql, ["rolescod" => $rolescod], $conn);

            $insSql = "INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES (:rolescod, :fncod, :fnrolest, NULL);";
            foreach ($fncods as $fn) {
                self::executeNonQuery($insSql, ["rolescod" => $rolescod, "fncod" => $fn, "fnrolest" => 'ACT'], $conn);
            }

            $conn->commit();
            return true;
        } catch (\Exception $ex) {
            $conn->rollBack();
            error_log($ex->getMessage());
            return false;
        }
    }
}
