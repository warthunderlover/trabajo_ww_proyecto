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

    public static function obtenerPorCodigo($id_prod)
    {
        $sqlstr = "SELECT * from inventario where id_producto=:id_prod;";

        $params = [
            "id_prod"=>$id_prod
        ];

        return self::obtenerUnRegistro($sqlstr, $params);
    }

    public static function CrearProducto(
        string $prod_nombre,
        string $prod_descripcion,
        string $prod_cod_barra,
        float $prod_precio_compra,
        float $prod_precio_venta,
        int $prod_cantidad
    )
    {
        $sqlstr = "INSERT INTO inventario(
        nombre_producto,
        descripcion_producto, 
        codigo_barra_producto,
        precio_compra,
        precio_venta,
        stock_actual) 
        
        Values(
        :prod_nombre,
        :prod_descripcion,
        :prod_cod_barra,
        :prod_precio_compra,
        :prod_precio_venta,
        :prod_cantidad);";
        
        $params =  [
                "prod_nombre"=>$prod_nombre,
                "prod_descripcion"=>$prod_descripcion,
                "prod_cod_barra"=>$prod_cod_barra,
                "prod_precio_compra"=>$prod_precio_compra,
                "prod_precio_venta"=>$prod_precio_venta,
                "prod_cantidad"=>$prod_cantidad
            ];

        return self::executeNonQuery($sqlstr,$params);
    }

}