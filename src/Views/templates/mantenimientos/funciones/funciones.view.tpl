<section class="py-4 px-4 depth-2">
    <h2>Listado de Funciones</h2>
</section>
<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Tipo</th>
                <th>
                    <a href="index.php?page=Mantenimientos-Funcion&mode=INS">Nueva Función</a>
                </th>
            </tr>
        </thead>
        <tbody>
            {{foreach funciones}}
            <tr>
                <td>{{fncod}}</td>
                <td>{{fndsc}}</td>
                <td>{{fnest}}</td>
                <td>{{fntyp}}</td>
                <td>
                    
                    <a href="index.php?page=Mantenimientos-Funcion&mode=UPD&id={{fncod}}">Editar</a>
                    
                    &nbsp;
                    
                    <a href="index.php?page=Mantenimientos-Funcion&mode=DEL&id={{fncod}}">Eliminar</a>
                    
                    &nbsp;
                    
                    <a href="index.php?page=Mantenimientos-Funcion&mode=DSP&id={{fncod}}">Ver</a>
                    
                </td>
            </tr>
            {{endfor funciones}}
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="right">
                    Registros: {{total}}
                </td>
            </tr>
        </tfoot>
    </table>
</section>
