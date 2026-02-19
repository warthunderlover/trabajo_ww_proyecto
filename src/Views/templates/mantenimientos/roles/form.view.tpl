<section class="form-wrapper">
  <style>
    /* === CONTENEDOR === */
    .form-wrapper {
      max-width: 1000px;
      margin: 0 auto;
      padding: 1rem;
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
      box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.20);
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
      padding: 0.65rem 1.3rem;
      font-size: 1rem;
      font-weight: 700;
      border-radius: 10px;
      text-decoration: none;
      display: inline-block;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
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

    /* === FUNCIONES (CHECKBOXES) === */
    .roles-funciones {
      border: 1px solid #000;
      border-radius: 10px;
      padding: 0.75rem;
      background-color: #f7f7f7;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 0.5rem 1rem;
    }

    .roles-funciones > div {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .roles-funciones input[type="checkbox"] {
      width: 18px;
      height: 18px;
      accent-color: #000;
      cursor: pointer;
    }

    /* === RESPONSIVE === */
    @media (max-width: 900px) {
      .roles-funciones {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr;
      }

      .roles-funciones {
        grid-template-columns: 1fr;
      }
    }
  </style>

  <section class="depth-2">
    <h2>{{modeDsc}}</h2>
  </section>

  {{if hasErrores}}
    <div>
      <ul class="error">
        {{foreach errores}}
          <li>{{this}}</li>
        {{endfor errores}}
      </ul>
    </div>
  {{endif hasErrores}}

  <form class="form-section" action="index.php?page=Mantenimientos-Rol&mode={{mode}}&id={{rolescod}}" method="POST">
    <input type="hidden" name="vlt" value="{{token}}" />

    <div class="form-grid">
      <div class="form-field">
        <label for="rolescod">Código</label>
        <input type="text" name="rolescod" id="rolescod" value="{{rolescod}}" {{idReadonly}} />
      </div>

      <div class="form-field">
        <label for="rolesdsc">Descripción</label>
        <input type="text" name="rolesdsc" id="rolesdsc" value="{{rolesdsc}}" {{readonly}} />
      </div>

      <div class="form-field full">
        <label>Funciones asignables</label>

        {{ifnot readonly}}
          <div class="roles-funciones">
            {{foreach funciones}}
              <div>
                <input type="checkbox" name="funciones[]" id="fn{{fncod}}" value="{{fncod}}" {{checked}} />
                <label for="fn{{fncod}}">{{fndsc}}</label>
              </div>
            {{endfor funciones}}
          </div>
        {{endifnot readonly}}

        {{if readonly}}
          <div>
            {{foreach funciones}}
              {{if checked}}
                <div class="badge-role">{{fndsc}}</div>
              {{endif checked}}
            {{endfor funciones}}
          </div>
        {{endif readonly}}
      </div>

      <div class="form-field">
        <label for="rolesest">Estado</label>

        {{ifnot readonly}}
          <select name="rolesest" id="rolesest">
            <option value="ACT" {{selectedACT}}>Activo</option>
            <option value="INA" {{selectedINA}}>Inactivo</option>
            <option value="BLQ" {{selectedBLQ}}>Bloqueado</option>
          </select>
        {{endifnot readonly}}

        {{if readonly}}
          <input type="text" name="rolesest" id="rolesest" value="{{rolesest}}" {{readonly}} />
        {{endif readonly}}
      </div>

      <div class="form-field full">
        <div class="form-actions">
          <a href="index.php?page=Mantenimientos-Roles" data-back="index.php?page=Mantenimientos-Roles" id="btnCancelar">Cancelar</a>

          {{ifnot isDisplay}}
            <button id="btnConfirmar" type="submit">Confirmar</button>
          {{endifnot isDisplay}}
        </div>
      </div>
    </div>
  </form>
</section>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("btnCancelar").addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();
      window.location.assign("index.php?page=Mantenimientos-Roles");
    });
  });
</script>
