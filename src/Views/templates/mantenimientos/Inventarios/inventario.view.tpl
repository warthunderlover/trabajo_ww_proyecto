<section class="py-4 px-4 depth-2">
    <h2>Listado de Productos del inventario</h2>
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
                <th>Stock Actual</th>
                <th>
                {{if product_INS}}
                    <a href="index.php?page=Mantenimientos-Cinventario&mode=INS">Nuevo</a>
                {{endif product_INS}}
                </th>
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
                <td>{{stock_actual}}</td>
                <td>
                    <!-- Aquí se muestran los enlaces de acción según los permisos del usuario, recordar usar el "~" a la hora de llamar a las funciones, ya que asi php sabe que no es de la raiz de la base de datos si no que de otro lado -->
                    {{if ~product_UPD}}
                    <a href="index.php?page=Mantenimientos-Cinventario&mode=UPD&id_prod={{id_producto}}">Editar</a>
                    {{endif ~product_UPD}}
                    &nbsp;
                    {{if ~product_DEL}}
                    <a href="index.php?page=Mantenimientos-Cinventario&mode=DEL&id_prod={{id_producto}}">Eliminar</a>
                    {{endif ~product_DEL}}
                    &nbsp;
                    {{if ~product_DSP}}
                    <a href="index.php?page=Mantenimientos-Cinventario&mode=DSP&id_prod={{id_producto}}">Ver</a>
                    {{endif ~product_DSP}}
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