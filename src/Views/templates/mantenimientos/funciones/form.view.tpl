<section>
  <style>
    /* === CONTENEDOR === */
    .form-wrapper {
      max-width: 1000px;
      margin: 0 auto;
    }

    /* === TARJETA === */
    .card-form {
      border: 1px solid #000;
      border-radius: 16px;
      overflow: hidden;
      background: #fff;
      box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    }

    .card-form .card-body {
      padding: 1.4rem;
    }

    .card-form .card-footer {
      padding: 1rem 1.4rem;
      border-top: 1px solid rgba(0,0,0,0.15);
      background: #fff;
    }

    /* === TITULO === */
    .form-title {
      font-weight: 800;
      margin: 0;
      color: #000;
    }

    /* === GRID === */
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
      font-weight: 800;
      margin-bottom: 0.3rem;
      color: #000;
    }

    /* === INPUTS / SELECT === */
    input,
    select {
      padding: 0.65rem 0.6rem;
      font-size: 1rem;
      border-radius: 10px;

      background-color: #ffffff;
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

    /* === ERRORES === */
    ul.error {
      background-color: #f2b5b5;
      color: #4a1d1d;
      padding: 0.75rem 1rem;
      border-radius: 12px;
      margin-bottom: 1rem;
      border: 1px solid #e79b9b;
    }

    ul.error li {
      margin-left: 1rem;
    }

    /* === BOTONES === */
    .form-actions {
      display: flex;
      gap: 0.75rem;
      justify-content: flex-end;
      flex-wrap: wrap;
    }

    #btnCancelar {
      background-color: #f3b6b6;
      color: #4a1d1d;
      padding: 0.65rem 1.3rem;
      font-size: 1rem;
      font-weight: 800;
      border-radius: 12px;
      text-decoration: none;
      display: inline-block;
      border: 1px solid #000;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    #btnCancelar:hover {
      background-color: #ee9e9e;
      transform: translateY(-1px);
    }

    #btnConfirmar {
      background-color: #bfe8d1;
      color: #12412b;
      padding: 0.65rem 1.3rem;
      font-size: 1rem;
      font-weight: 800;
      border-radius: 12px;
      border: 1px solid #000;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    #btnConfirmar:hover {
      background-color: #a7dfc1;
      transform: translateY(-1px);
    }

    /* === RESPONSIVE === */
    @media (max-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr;
      }

      .form-actions {
        justify-content: stretch;
      }

      #btnCancelar,
      #btnConfirmar {
        width: 100%;
        text-align: center;
      }
    }
  </style>

  <section class="container py-4 form-wrapper">
    <div class="card card-form">
      <div class="card-body">
        <h2 class="form-title">{{modeDsc}}</h2>

        {{if hasErrores}}
          <div class="mt-3">
            <ul class="error">
              {{foreach errores}}
                <li>{{this}}</li>
              {{endfor errores}}
            </ul>
          </div>
        {{endif hasErrores}}

        <form class="form-section"
              action="index.php?page=Mantenimientos-Funcion&mode={{mode}}&id={{fncod}}"
              method="POST">

          <input type="hidden" name="vlt" value="{{token}}" />

          <div class="form-grid">
            <div class="form-field">
              <label for="fncod">Código</label>
              <input type="text" name="fncod" id="fncod" value="{{fncod}}" {{idReadonly}} />
            </div>

            <div class="form-field">
              <label for="fndsc">Descripción</label>
              <input type="text" name="fndsc" id="fndsc" value="{{fndsc}}" {{readonly}} />
            </div>

            <div class="form-field">
              <label for="fnest">Estado</label>

              {{ifnot readonly}}
                <select name="fnest" id="fnest">
                  <option value="ACT" {{selectedACT}}>Activo</option>
                  <option value="INA" {{selectedINA}}>Inactivo</option>
                  <option value="BLQ" {{selectedBLQ}}>Bloqueado</option>
                </select>
              {{endifnot readonly}}

              {{if readonly}}
                <input type="text" name="fnest" id="fnest" value="{{fnest}}" {{readonly}} />
              {{endif readonly}}
            </div>

            <div class="form-field">
              <label for="fntyp">Tipo</label>
              <input type="text" name="fntyp" id="fntyp" value="{{fntyp}}" {{readonly}} />
            </div>
          </div>

          <div class="card-footer mt-4">
            <div class="form-actions">
              <a href="index.php?page=Mantenimientos-Funciones"
                 data-back="index.php?page=Mantenimientos-Funciones"
                 id="btnCancelar">
                Cancelar
              </a>

              {{ifnot isDisplay}}
                <button id="btnConfirmar" type="submit">
                  Confirmar
                </button>
              {{endifnot isDisplay}}
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</section>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("btnCancelar").addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();
      window.location.assign("index.php?page=Mantenimientos-Funciones");
    });
  });
</script>
