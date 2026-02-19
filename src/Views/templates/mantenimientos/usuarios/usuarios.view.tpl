<section class="py-4 px-4 depth-2">
    <h2>Listado de Usuarios</h2>
</section>

<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Correo</th>
                <th>Nombre</th>
                <th>Roles</th>
                <th>Estado</th>
                <th>Tipo</th>
                <th>
                    <a href="index.php?page=Mantenimientos-Usuario&mode=INS">Nuevo Usuario</a>
                </th>
            </tr>
        </thead>

        <tbody>
            {{foreach usuarios}}
            <tr>
                <td>{{usercod}}</td>
                <td>{{useremail}}</td>
                <td>{{username}}</td>

                <td>
                    {{if roles}}
                        {{foreach roles}}
                            <span class="badge-role">{{rolesdsc}}</span>
                        {{endfor roles}}
                    {{endif roles}}

                    {{ifnot roles}}
                        -
                    {{endifnot roles}}
                </td>

                <td>
                    <span class="badge-role">{{userest}}</span>
                </td>

                <td>
                    <span class="badge-role">
                        {{if usertipoLabel}}{{usertipoLabel}}{{endif usertipoLabel}}
                        {{ifnot usertipoLabel}}-{{endifnot usertipoLabel}}
                    </span>
                </td>

                <td>
                    <a href="index.php?page=Mantenimientos-Usuario&mode=UPD&id={{usercod}}">Editar</a>
                    &nbsp;
                    <a data-confirm="true" data-entity="el usuario {{username}}"
                       href="index.php?page=Mantenimientos-Usuario&mode=DEL&id={{usercod}}">Eliminar</a>
                    &nbsp;
                    <a href="index.php?page=Mantenimientos-Usuario&mode=DSP&id={{usercod}}">Ver</a>
                </td>
            </tr>
            {{endfor usuarios}}
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