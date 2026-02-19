<section class="container-fluid py-4 px-4 depth-1 rounded-2">

    <style>
    /* === CONTENEDOR === */
    .form-wrapper {
        max-width: 1000px;
        margin: 0 auto;
    }

    h2 {
        margin-bottom: 1rem;
    }

    /* === GRID SIMPLE === */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.2rem;
        margin-top: 1rem;
    }

    .form-field {
        display: flex;
        flex-direction: column;
    }

    .form-field.full {
        grid-column: 1 / -1;
    }

    /* === LABELS === */
    label {
    font-weight: 700;
    margin-bottom: 0.3rem;
    color: #000;
}

    /* === INPUTS / SELECT === */
    input,
select {
    padding: 0.65rem 0.6rem;
    font-size: 1rem;
    border-radius: 8px;

    /* BLANCO PURO */
    background-color: #ffffff;

    /* BORDES NEGROS */
    border: 1px solid #000;
    outline: none;

    transition: all 0.2s ease;
}

input:focus,
select:focus {
    border-color: #000;
    box-shadow: 0 0 0 3px rgba(0,0,0,0.20);
}

input[readonly] {
    background-color: #f5f5f5;
    border: 1px solid #000;
    cursor: not-allowed;
    color: #444;
}

    /* === BOTONES === */
    .form-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    button {
        padding: 0.65rem 1.3rem;
        font-size: 1rem;
        font-weight: 700;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    /* Rojo suave */
    #btnCancelar {
        background-color: #f3b6b6;
        color: #4a1d1d;
    }

    #btnCancelar:hover {
        background-color: #ee9e9e;
        transform: translateY(-1px);
    }

    /* Verde suave */
    #btnConfirmar {
        background-color: #bfe8d1;
        color: #12412b;
    }

    #btnConfirmar:hover {
        background-color: #a7dfc1;
        transform: translateY(-1px);
    }

    /* === ERRORES === */
    ul.error {
        background-color: #f2b5b5;
        color: #4a1d1d;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        border: 1px solid #e79b9b;
    }

    ul.error li {
        margin-left: 1rem;
    }

    /* === RESPONSIVE === */
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>


    <section>
        <h2>{{modeDsc}}</h2>
    </section> 

    {{if hasErrores}} 
        <ul class="error"> 
            {{foreach errores}} 
                <li>{{this}}</li> 
            {{endfor errores}} 
        </ul>
    {{endif hasErrores}} 

    <form class="form-wrapper"
          action="index.php?page=Mantenimientos-Cinventario&mode={{mode}}&id_prod={{id_prod}}"
          method="POST">

        <div class="form-grid" {{estadoDSP}}>

            <div class="form-field" {{codigoINS}}>
                <label for="id_prod">Código</label>
                <input type="text"
                       name="id_prod"
                       id="id_prod"
                       value="{{id_prod}}"
                       {{codigoReadonly}} />
                <input type="hidden" name="vlt" value="{{token}}" />
            </div>

            <div class="form-field">
                <label for="prod_nombre">Nombre</label>
                <input type="text"
                       name="prod_nombre"
                       id="prod_nombre"
                       value="{{prod_nombre}}"
                       {{readonly}} />
            </div>

            <div class="form-field">
                <label for="prod_cod_barra">Código de barras</label>
                <input type="text"
                       name="prod_cod_barra"
                       id="prod_cod_barra"
                       value="{{prod_cod_barra}}"
                       {{readonly}} />
            </div>

            <div class="form-field">
                <label for="prod_descripcion">Descripción</label>
                <input type="text"
                       name="prod_descripcion"
                       id="prod_descripcion"
                       value="{{prod_descripcion}}"
                       {{readonly}} />
            </div>

            <div class="form-field">
                <label for="prod_precio_compra">Precio Compra</label>
                <input type="number"
                       name="prod_precio_compra"
                       id="prod_precio_compra"
                       min="0.01"
                       step="0.01"
                       value="{{prod_precio_compra}}"
                       {{readonly}} />
            </div>

            <div class="form-field">
                <label for="prod_precio_venta">Precio Venta</label>
                <input type="number"
                       name="prod_precio_venta"
                       id="prod_precio_venta"
                       min="0.01"
                       step="0.01"
                       value="{{prod_precio_venta}}"
                       {{readonly}} />
            </div>

            <div class="form-field">
                <label for="prod_cant">Cantidad</label>
                <input type="number"
                       name="prod_cant"
                       id="prod_cant"
                       value="{{prod_cant}}"
                       {{readonly}} />
            </div>

            <div class="form-field" {{estadoDEL}}>
                <label for="estado">Estado</label>

                {{ifnot readonly}}
                    <select name="estado" id="estado">
                        <option value="ACT" {{selectedACT}}>Activo</option>
                        <option value="INA" {{selectedINA}}>Inactivo</option>
                    </select>
                {{endifnot readonly}}

                {{if readonly}}
                    <input type="text"
                           name="estado"
                           id="estado"
                           value="{{estado}}"
                           {{readonly}} />
                {{endif readonly}}
            </div>

            <div class="form-actions full">
                <button id="btnCancelar">Cancelar</button>
                {{ifnot isDisplay}}
                    <button id="btnConfirmar" type="submit">Confirmar</button>
                {{endifnot isDisplay}}
            </div>

        </div>
    </form>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("btnCancelar").addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        window.location.assign("index.php?page=Mantenimientos-Inventario");
    });
});
</script>
