<?php

namespace Dao\Usuario;

use Dao\Table;

class Usuario extends Table
{
    public static function obtenerUsuarios(): array
    {
        $sqlstr = "SELECT * FROM usuario;";
        return self::obtenerRegistros($sqlstr, []);
    }

    public static function obtenerUsuarioPorId(int $usercod): array
    {
        $sqlstr = "SELECT * FROM usuario WHERE usercod = :usercod;";
        return self::obtenerUnRegistro($sqlstr, ["usercod" => $usercod]);
    }

    public static function obtenerUsuarioPorEmail(string $useremail): array
    {
        $sqlstr = "SELECT * FROM usuario WHERE useremail = :useremail;";
        return self::obtenerUnRegistro($sqlstr, ["useremail" => $useremail]);
    }

    public static function crearUsuario(
        string $useremail,
        string $username,
        string $userpswd,
        string $userfching,
        string $userpswdest,
        string $userpswdexp,
        string $userest,
        string $useractcod,
        string $userpswdchg,
        string $usertipo
    ) {
        $insSql = "INSERT INTO usuario 
                   (useremail, username, userpswd, userfching, userpswdest, userpswdexp, userest, useractcod, userpswdchg, usertipo)
                   VALUES 
                   (:useremail, :username, :userpswd, :userfching, :userpswdest, :userpswdexp, :userest, :useractcod, :userpswdchg, :usertipo);";

        $newInsertData = [
            "useremail"    => $useremail,
            "username"     => $username,
            "userpswd"     => $userpswd,
            "userfching"   => ($userfching === '' ? null : $userfching),
            "userpswdest"  => $userpswdest,
            "userpswdexp"  => ($userpswdexp === '' ? null : $userpswdexp),
            "userest"      => $userest,
            "useractcod"   => $useractcod,
            "userpswdchg"  => $userpswdchg,
            "usertipo"     => $usertipo
        ];

        return self::executeNonQuery($insSql, $newInsertData);
    }

    public static function actualizarUsuario(
        int $usercod,
        string $useremail,
        string $username,
        string $userpswd,
        string $userfching,
        string $userpswdest,
        string $userpswdexp,
        string $userest,
        string $useractcod,
        string $userpswdchg,
        string $usertipo
    ) {
        // Si viene contraseña vacía, no actualizar ese campo
        $actualizarPassword = !empty($userpswd);
        $finalPassword = $userpswd;
        
        if ($actualizarPassword) {
            // Si la contraseña no es un hash ya (bcrypt tiene 60 caracteres), hashearla
            if (strlen($userpswd) < 60) {
                $finalPassword = self::_hashPassword($userpswd);
            }
        }

        // Construir SQL dinámicamente según si se actualiza contraseña o no
        if ($actualizarPassword) {
            $updSql = "UPDATE usuario SET 
                       useremail = :useremail,
                       username = :username,
                       userpswd = :userpswd,
                       userfching = :userfching,
                       userpswdest = :userpswdest,
                       userpswdexp = :userpswdexp,
                       userest = :userest,
                       useractcod = :useractcod,
                       userpswdchg = :userpswdchg,
                       usertipo = :usertipo
                       WHERE usercod = :usercod;";

            $newUpdateData = [
                "usercod"      => $usercod,
                "useremail"    => $useremail,
                "username"     => $username,
                "userpswd"     => $finalPassword,
                "userfching"   => ($userfching === '' ? null : $userfching),
                "userpswdest"  => $userpswdest,
                "userpswdexp"  => ($userpswdexp === '' ? null : $userpswdexp),
                "userest"      => $userest,
                "useractcod"   => $useractcod,
                "userpswdchg"  => $userpswdchg,
                "usertipo"     => $usertipo
            ];
        } else {
            // No actualizar el campo userpswd
            $updSql = "UPDATE usuario SET 
                       useremail = :useremail,
                       username = :username,
                       userfching = :userfching,
                       userpswdest = :userpswdest,
                       userpswdexp = :userpswdexp,
                       userest = :userest,
                       useractcod = :useractcod,
                       userpswdchg = :userpswdchg,
                       usertipo = :usertipo
                       WHERE usercod = :usercod;";

            $newUpdateData = [
                "usercod"      => $usercod,
                "useremail"    => $useremail,
                "username"     => $username,
                "userfching"   => ($userfching === '' ? null : $userfching),
                "userpswdest"  => $userpswdest,
                "userpswdexp"  => ($userpswdexp === '' ? null : $userpswdexp),
                "userest"      => $userest,
                "useractcod"   => $useractcod,
                "userpswdchg"  => $userpswdchg,
                "usertipo"     => $usertipo
            ];
        }

        return self::executeNonQuery($updSql, $newUpdateData);
    }

    public static function eliminarUsuario(int $usercod)
    {
        $delSql = "DELETE FROM usuario WHERE usercod = :usercod;";
        $delParams = ["usercod" => $usercod];
        return self::executeNonQuery($delSql, $delParams);
    }

    public static function obtenerRolesPorUsuario(int $usercod): array
    {
        $sqlstr = "SELECT r.rolescod, r.rolesdsc, ru.roleuserest FROM roles_usuarios ru JOIN roles r ON ru.rolescod = r.rolescod WHERE ru.usercod = :usercod;";
        return self::obtenerRegistros($sqlstr, ["usercod" => $usercod]);
    }

    public static function eliminarRolesPorUsuario(int $usercod)
    {
        $delSql = "DELETE FROM roles_usuarios WHERE usercod = :usercod;";
        return self::executeNonQuery($delSql, ["usercod" => $usercod]);
    }

    public static function asignarRolAUsuario(int $usercod, string $rolescod, string $roleuserest = 'ACT')
    {
        $insSql = "INSERT INTO roles_usuarios (usercod, rolescod, roleuserest, roleuserfch, roleuserexp) VALUES (:usercod, :rolescod, :roleuserest, NOW(), NULL);";
        return self::executeNonQuery($insSql, ["usercod" => $usercod, "rolescod" => $rolescod, "roleuserest" => $roleuserest]);
    }

    private static function _saltPassword($password)
    {
        return hash_hmac(
            "sha256",
            $password,
            \Utilities\Context::getContextByKey("PWD_HASH")
        );
    }

    private static function _hashPassword($password)
    {
        return password_hash(self::_saltPassword($password), defined('PASSWORD_ALGORITHM') ? PASSWORD_ALGORITHM : PASSWORD_DEFAULT);
    }
}
