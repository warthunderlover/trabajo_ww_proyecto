<?php 

// este es para el controlador del inventario, o sea, ver que todo este bien y en si el mvc 

namespace Controllers\Mantenimientos;

use Controllers\PublicController;
//use controllers\AdminController;
use Controllers\PrivateController;
use Utilities\Site;
use Utilities\Validators;
use Views\Renderer;
use Dao\Inventarios\Inventario as DaoInventario;
use Exception;

const Inventario_Path = "mantenimientos/Inventarios/inventarioForm";
const inventario_total_path = "index.php?page=Mantenimientos_Inventario";

class Cinventario extends PrivateController
{
    private $modes = 
    [
        "INS"=>"Insertando producto nuevo",
        "UPD"=>"Actulizando producto %s",
        "DSP"=>"Viendo producto %s",
        "DEL"=>"Eliminando producto %s"
    ];

    private string $mode = "";
    private int $id_prod = 0;
    private string $prod_cod_barra="";
    private string $prod_nombre ="";
    private string $prod_descripcion ="";
    private float $prod_precio_compra = 0.0;
    private float $prod_precio_venta = 0.0;
    private float $prod_ganancia = 0.0;
    private int $prod_cant=0;
    private bool $prod_est = true;
    private string $validationToken ="";
    private array $errores =[];

    private string $categoria_combo ="";


    //aqui en si lo que estoy haciendo es el metodo para maanejar la paagina y sus eventos, o sea se, que si es un insert haga tal cosa y así
    public function run(): void
    {
        try
        {
            $this->page_init();

            if($this->isPostBack())
            {
                $this->errores = $this->validarPostData();

                if(count($this->errores)===0)
                {
                    try
                    {
                        switch($this->mode)
                        {
                            case "INS":
                                //aqui se manda a llamar el error
                                $errores = $this->validarProductoINS();
                                
                                //aqui se hace una validacion, si hay un error no se continua con el insert, pero si no hay errores se continua ejecutando el programa como se debe.
                                if (count($errores) > 0) {
                                    $this->errores = array_merge($this->errores, $errores);
                                    break;
                                }

                                $affectedRows = DaoInventario::CrearProducto(
                                    $this->prod_nombre, 
                                    $this->prod_descripcion,
                                    $this->prod_cod_barra,
                                    $this->prod_precio_compra,
                                    $this->prod_precio_venta,
                                    $this->prod_cant
                                );
                                if($affectedRows>0)
                                {
                                    Site::redirectToWithMsg(
                                        inventario_total_path,
                                        "¡Producto creado exitosamente!"
                                    );
                                }
                            break;
                        }
                    }catch(Exception $err)
                    {
                        error_log($err);
                        $this->errores[] = $err->getMessage();
                    }
                }
            }
            $viewData = $this->preparar_datos_vista();
            Renderer::render(Inventario_Path, $viewData);

        }catch(Exception $ex){
            error_log($ex->getMessage());
            Site::redirectToWithMsg(inventario_total_path, "Sucedió un problema. Reintente nuevamente.");
        }
    }


    //para poder tener varias validaciones en vez de tener que hacer más de 8 ifs, lo que se hace es que se crea una funcion que devuelve un array, dentro de este array lo que hay es un conjunto de condiciones y mensaje, cuando una condicion se cumple (cuando se se llama a la funcion run), se muestra el error que le corresponde.
    private function validarProductoINS(): array
    {
        $errores = [];

        //aqui tenemos que usar las variables para agarrar los valores y poder hacer las validaciones, como se hace en c# con el nombre de la variable del modelado
        $prod_nombre       = trim($_POST["prod_nombre"] ?? "");
        $prod_descripcion  = trim($_POST["prod_descripcion"] ?? "");
        $prod_precio_compra = floatval($_POST["prod_precio_compra"] ?? 0);
        $prod_precio_venta  = floatval($_POST["prod_precio_venta"] ?? 0);
        $prod_cod_barra     = trim($_POST["prod_cod_barra"] ?? "");
        $prod_cant          = intval($_POST["prod_cant"] ?? 0);

        //reglasde validaciones, misma forma que se usa en c#
        $reglas = [
            [
                "condicion" => fn() => \Utilities\Validators::IsEmpty($prod_nombre),
                "mensaje" => "El nombre del producto no puede estar vacío."
            ],
            [
                "condicion" => fn() => strlen($prod_nombre) < 3,
                "mensaje" => "El nombre del producto debe tener al menos 3 caracteres."
            ],
            [
                "condicion" => fn() => \Utilities\Validators::IsEmpty($prod_descripcion),
                "mensaje" => "La descripción del producto no puede estar vacía."
            ],
            [
                "condicion" => fn() => $prod_precio_compra > $prod_precio_venta,
                "mensaje" => "El precio de compra no puede ser mayor al precio de venta."
            ],
            [
                "condicion" => fn() => \Utilities\Validators::IsEmpty($prod_cod_barra),
                "mensaje" => "El código de barra no puede estar vacío."
            ],
            [
                "condicion" => fn() => !\Utilities\Validators::IsValidBarcode($prod_cod_barra),
                "mensaje" => "El código de barra debe tener exactamente 13 dígitos."
            ],
            [
                "condicion" => fn() => $prod_cant < 0,
                "mensaje" => "La cantidad no puede ser negativa."
            ],
        ];

        foreach ($reglas as $regla) {
            //lo que este foreach hace es que si la condicion se comple, se muestra el mansaje de error
            if ($regla["condicion"]()) {
                $errores[] = $regla["mensaje"];
                throw new Exception($regla["mensaje"], 1);   
            }
        }

        return $errores;
    }

    //aquí inicializare la pagina, para poder manejar los datos que vienen del formulario y todo eso
    private function page_init()
    { //los nombres de las variables tienen que ser iguales a los nombres del 
    //foreach de mi lista de productos, o sea, el nombre del campo en la base de datos, para que así se puedan cargar los datos a la vista sin problemas
        if(isset($_GET["mode"]) && isset($this->modes[$_GET["mode"]]))
        {

            $this->mode = $_GET["mode"];
            
            if(!$this->isFeatureAutorized("product_".$this->mode)){
                throw new Exception("No tiene permisos para realizar esta operación");
            }

            

            if($this->mode !== "INS")
            {
                $tmpCodiProd='';

                if (isset($_GET["id_prod"])) 
                {
                    $tmpCodiProd = $_GET["id_prod"];
                }
                else
                {
                    throw new Exception("Codigo no es valido");
                }

                //extraer producto por codigo 
                $tmpProd = DaoInventario::obtenerPorCodigo($tmpCodiProd);

                if(count($tmpProd)===0)
                {
                    throw new Exception("No se encontró ningun producto con este id");
                }
                $this->id_prod = $tmpProd["id_producto"];
                $this->prod_cod_barra = $tmpProd["codigo_barra_producto"];
                $this->prod_nombre = $tmpProd["nombre_producto"];
                $this->prod_descripcion = $tmpProd["descripcion_producto"];
                $this->prod_precio_compra = $tmpProd["precio_compra"];
                $this->prod_precio_venta = $tmpProd["precio_venta"];
                $this->prod_ganancia = floatval($tmpProd["ganancia"]);
                $this->prod_cant = $tmpProd["stock_actual"];
            }
        }
        else
        {
            throw new Exception("Modo no es valido");
        }
    }

    private function validarPostData(): array
    {
        $errores = [];
        $this->validationToken = $_POST["vlt"] ?? '';

        if(isset($_SESSION[$this->name . "_token"]) && $_SESSION[$this->name . "_token"] !== $this->validationToken)
        {
            throw new Exception("Error de validación de token");
        }

        $this->id_prod = intval($_POST["id_prod"] ?? 0);
        $this->prod_cod_barra = $_POST["prod_cod_barra"] ?? '';
        $this->prod_nombre = $_POST["prod_nombre"] ?? '';
        $this->prod_descripcion = $_POST["prod_descripcion"] ?? '';
        $this->prod_precio_compra = floatval($_POST["prod_precio_compra"] ?? 0.0);
        $this->prod_precio_venta = floatval($_POST["prod_precio_venta"]) ?? 0.0;
        $this->prod_cant = intval($_POST["prod_cant"] ?? 0);

        return $errores;
    }

    private function generarTokenDeValidacion()
    {
        $this->validationToken = md5(gettimeofday(true) . $this->name . rand(1000, 9999));
        $_SESSION[$this->name . "_token"] = $this->validationToken;
    }

    //aqui cargamos los datos a la vista

    private function preparar_datos_vista()
    {
        $viewData=[];
        $viewData["modeDsc"]=$this->modes[$this->mode];
    
        if($this->mode !== "INS")
        {
            $viewData["modeDsc"] = sprintf($viewData["modeDsc"],$this->prod_nombre);
        }

        $viewData["id_prod"] = $this->id_prod;
        $viewData["prod_cod_barra"] = $this->prod_cod_barra;
        $viewData["prod_nombre"] = $this->prod_nombre;
        $viewData["prod_descripcion"] = $this->prod_descripcion;
        $viewData["prod_precio_compra"] = $this->prod_precio_compra;
        $viewData["prod_precio_venta"] = $this->prod_precio_venta;
        $viewData["prod_cant"] = $this->prod_cant;
        $this->generarTokenDeValidacion();
        $viewData["token"] = $this->validationToken;
        $viewData["errores"] = $this->errores;
        $viewData["hasErrores"] = count($this->errores) > 0;

        $viewData["codigoReadonly"] = $this->mode !== "INS" ? "readonly" : "";

        $viewData["readonly"] = in_array($this->mode, ["DSP", "DEL"]) ? "readonly" : "";

        $viewData["codigoINS"] = $this->mode ==="INS"?"hidden":"";

        $viewData["isDisplay"] = $this->mode === "DSP";

        $viewData["selected" . $this->prod_est] = "selected";

        $viewData["precioMinimmo"] = $this->mode === "INS" ?"0.01" : "0.00";
        
        
        return $viewData;
    }
}

