<section class="py-4 px-4 depth-2">
    <h2>Listado de Roles</h2>
</section>

<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Funciones</th>
                <th>Estado</th>
                <th>
                    <a href="index.php?page=Mantenimientos-Rol&mode=INS">Nuevo Rol</a>
                </th>
            </tr>
        </thead>

        <tbody>
            {{foreach roles}}
            <tr>
                <td>{{rolescod}}</td>
                <td>{{rolesdsc}}</td>

                <td>
                    {{if funciones}}
                        {{foreach funciones}}
                            <span class="badge-role">{{fndsc}}</span>
                        {{endfor funciones}}
                    {{endif funciones}}

                    {{ifnot funciones}}
                        -
                    {{endifnot funciones}}
                </td>

                <td>
                    <span class="badge-role">{{rolesest}}</span>
                </td>

                <td>
                    <a href="index.php?page=Mantenimientos-Rol&mode=UPD&id={{rolescod}}">Editar</a>
                    &nbsp;
                    <a data-confirm="true" data-entity="el rol {{rolesdsc}}"
                       href="index.php?page=Mantenimientos-Rol&mode=DEL&id={{rolescod}}">Eliminar</a>
                    &nbsp;
                    <a href="index.php?page=Mantenimientos-Rol&mode=DSP&id={{rolescod}}">Ver</a>
                </td>
            </tr>
            {{endfor roles}}
        </tbody>

        <tfoot>
            <tr>
                <td colspan="6" class="right">
                    Registros: {{total}}
                </td>
            </tr>
        </tfoot>
    </table>
</section>
