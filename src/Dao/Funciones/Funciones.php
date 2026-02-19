<?php

namespace Dao\Funciones;

use Dao\Table;

class Funciones extends Table
{
    public static function obtenerFunciones(): array
    {
        $sqlstr = "SELECT * FROM funciones;";
        return self::obtenerRegistros($sqlstr, []);
    }

    public static function obtenerFuncionPorCodigo(string $fncod): array
    {
        $sqlstr = "SELECT * FROM funciones WHERE fncod = :fncod;";
        return self::obtenerUnRegistro($sqlstr, ["fncod" => $fncod]);
    }

    public static function crearFuncion(
        string $fncod,
        string $fndsc,
        string $fnest,
        string $fntyp
    ) {
        $insSql = "INSERT INTO funciones 
                   (fncod, fndsc, fnest, fntyp)
                   VALUES 
                   (:fncod, :fndsc, :fnest, :fntyp);";

        $params = [
            "fncod" => $fncod,
            "fndsc" => $fndsc,
            "fnest" => $fnest,
            "fntyp" => $fntyp
        ];

        return self::executeNonQuery($insSql, $params);
    }

    public static function actualizarFuncion(
        string $fncod,
        string $fndsc,
        string $fnest,
        string $fntyp
    ) {
        // If attempting to inactivate or block a function, ensure no active role has this function assigned
        if ($fnest !== 'ACT') {
            $checkSql = "SELECT COUNT(*) as cnt FROM funciones_roles WHERE fncod = :fncod AND fnrolest = 'ACT';";
            $res = self::obtenerUnRegistro($checkSql, ["fncod" => $fncod]);
            $cnt = isset($res['cnt']) ? intval($res['cnt']) : 0;
            if ($cnt > 0) {
                throw new \Exception("No puede inactivar/bloquear la función porque está asignada a roles activos ({$cnt}).");
            }
        }

        $updSql = "UPDATE funciones SET 
                   fndsc = :fndsc,
                   fnest = :fnest,
                   fntyp = :fntyp
                   WHERE fncod = :fncod;";

        $params = [
            "fncod" => $fncod,
            "fndsc" => $fndsc,
            "fnest" => $fnest,
            "fntyp" => $fntyp
        ];

        return self::executeNonQuery($updSql, $params);
    }

    public static function eliminarFuncion(string $fncod)
    {
        // Check for existing role assignments that would block deletion
        $checkSql = "SELECT fr.rolescod, r.rolesdsc, fr.fnrolest
                     FROM funciones_roles fr
                     LEFT JOIN roles r ON fr.rolescod = r.rolescod
                     WHERE fr.fncod = :fncod";
        $deps = self::obtenerRegistros($checkSql, ["fncod" => $fncod]);
        if (is_array($deps) && count($deps) > 0) {
            // build a human readable list of blocking roles
            $parts = array_map(function ($d) {
                $code = $d['rolescod'] ?? '(sin codigo)';
                $label = $d['rolesdsc'] ?? '';
                $st = $d['fnrolest'] ?? '';
                return trim("{$code} ({$label}) [estado: {$st}]");
            }, $deps);
            $msg = "No puede eliminar la función porque está asignada a roles: " . implode(", ", $parts);
            throw new \Exception($msg);
        }

        $delSql = "DELETE FROM funciones WHERE fncod = :fncod;";
        return self::executeNonQuery($delSql, ["fncod" => $fncod]);
    }
}
