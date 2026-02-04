<section class="py-4 px-4 depth-2">
    <h2>Listado de Clientes</h2>
</section>
<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Codigo de Barras</th>
                <th>Precio compra</th>
                <th>Precio Venta</th>
                <th>Ganancia</th>
                <th><a href="index.php?page=Mantenimientos-Cinventario&mode=INS">Nuevo</a></th>
            </tr>
        </thead>
        <tbody>
            {{foreach inventario}}
            <tr>
                <td>{{id_producto}}</td>
                <td>{{nombre_producto}}</td>
                <td>{{descripcion_producto}}</td>
                <td>{{codigo_barra_producto}}</td>
                <td>{{precio_compra}}</td>
                <td>{{precio_venta}}</td>
                <td>{{ganancia}} %</td>
                <td>
                    <a href="index.php?page=Mantenimientos-Cinventario&mode=UPD&id_prod={{id_producto}}">Editar</a>&nbsp;
                    <a href="index.php?page=Mantenimientos-Cinventario&mode=DEL&id_prod={{id_producto}}">Eliminar</a>&nbsp;
                    <a href="index.php?page=Mantenimientos-Cinventario&mode=DSP&id_prod={{id_producto}}">Ver</a>
                </td>
            </tr>
            {{endfor inventario}}
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="right">
                    Registros: {{total_productos}}
                </td>
            </tr>
        </tfoot>
    </table>
</section>