<?php 
namespace Dao\Inventarios;

use Dao\Table;

class Inventario extends Table
{
    public static function ObtenerTodos(): array
    {
        $sqlstr = 'SELECT * FROM inventario;';
        return self::obtenerRegistros($sqlstr,[]);
    }

    public static function CrearProducto(
        string $prod_nombre,
        string $prod_descripcion,
        string $prod_cod_barra,
        float $prod_precio_compra,
        float $precio_venta,
        bool $prod_est
    )
    {
        $sqlstr = "INSERT INTO inventario(nombre_producto, descripcion_producto, codigo_barra_producto,precio_compra,precio_venta,estado_producto) Values(:prod_nombre,:prod_descripcion,:prod_cod_barra,:prod_precio_compra,:precio_venta,:prod_est);";
        return self::executeNonQuery(
            $sqlstr,
            [
                ":prod_nombre"=>$prod_nombre,
                ":prod_descripcion"=>$prod_descripcion,
                ":prod_cod_barra"=>$prod_cod_barra,
                ":prod_precio_compra"=>$prod_precio_compra,
                ":precio_venta"=>$precio_venta,
                ":prod_est"=>$prod_est
            ]
        );
    }
}