<section class="container">
    <section class="deplth-2">
        <h2>{{modeDsc}}</h2>
    </section>
    {{if hasErrores}}
        <ul class="error">
        {{foreach errores}}
            <li>{{this}}</li>
        {{endfor errores}}
        </ul>
    {{endif hasErrores}}
    <form action="index.php?page=Mantenimientos-Cinventario&mode={{mode}}&id_prod={{id_prod}}" method="POST">
        <div {{codigoINS}}>
            <label for="id_prod">Código</label>
            <input type="text" name="id_prod" id="id_prod" value="{{id_prod}}" {{codigoReadonly}}/>
            <input type="hidden" name="vlt" value="{{token}}" />
        </div>
        <div>
            <label for="prod_nombre">Nombre</label>
            <input type="text" name="prod_nombre" id="prod_nombre" value="{{prod_nombre}}" {{readonly}}/>
        </div>
        <div>
            <label for="prod_cod_barra">Codigo de barras</label>
            <input type="text" name="prod_cod_barra" id="prod_cod_barra" value="{{pro_cod_barra}}" {{readonly}} />
        </div>
        <div>
            <label for="prod_descripcion">Descripción</label>
            <input type="text" name="prod_descripcion" id="prod_descripcion" value="{{prod_descripcion}}" {{readonly}} />
        </div>
        <div>
            <label for="prod_precio_compra">Precio Compra</label>
            <input type="text" name="pro_cod_barra" id="pro_cod_barra" value="{{pro_cod_barra}}" {{readonly}}/>
        </div>
        <div>
            <label for="prod_precio_venta">Precio Venta</label>
            <input type="text" name="prod_precio_venta" id="prod_precio_venta" value="{{prod_precio_venta}}" {{readonly}}/>
        </div>
        
        <div>
            <label for="prod_cant">Cantidad</label>
            <input type="text" name="prod_cant" id="prod_cant" value="{{prod_cant}}" {{readonly}}/>
        </div>
        
        <div>
            <label for="estado">Estado</label>
            {{ifnot readonly}}
                <select name="estado" id="estado">
                    <option value="ACT" {{selectedACT}}>Activo</option>
                    <option value="INA" {{selectedINA}}>Inactivo</option>
                </select>
            {{endifnot readonly}}
            {{if readonly}}
                <input type="text" name="estado" id="estado" value="{{estado}}" {{readonly}}/>
            {{endif readonly}}
        </div>
        
        <div>
            <button id="btnCancelar">Cancelar</button>
            {{ifnot isDisplay}}
                <button id="btnConfirmar" type="submit">Confirmar</button>
            {{endifnot isDisplay}}
        </div>
    </form>
</section>
<script>
    document.addEventListener("DOMContentLoaded", ()=>{
        document.getElementById("btnCancelar").addEventListener("click", (e)=>{
            e.preventDefault();
            e.stopPropagation();
            window.location.assign("index.php?page=Mantenimientos-Inventario");
        })
    });
</script>