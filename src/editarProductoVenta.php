<?php include_once "includes/header.php";
include("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "nueva_venta";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
  $alert = "";
  if (empty($_POST['codigo']) || empty($_POST['producto']) || empty($_POST['cantidad']) || empty($_POST['total'])) {
    $alert = '<div class="alert alert-primary" role="alert">
              Todo los campos son requeridos
            </div>';
  } else {
    $id = $_GET['id'];
    $cantidad = $_POST['cantidad'];
    $total = $_POST['total'];
    $canti = number_format($cantidad, 2, '.', ',');
    if($total === 0.0 || $total === '0.0'){
        $alert = '<div class="alert alert-primary" role="alert">
              El valor total debe ser mayor a cero (0)
            </div>';
    }else if($cantidad == 0.00){
        $alert = '<div class="alert alert-primary" role="alert">
              La cantidad de productos debe ser mayor a cero (0)
            </div>';
    }else{
        $query_update = mysqli_query($conexion, "UPDATE detalle_temp SET  cantidad = '$cantidad', total= '$total' WHERE id = $id");
        if ($query_update) {
        $alert = '<div class="alert alert-primary" role="alert">
                Producto Modificado
                </div>';
        } else {
        $alert = '<div class="alert alert-primary" role="alert">
                    Error al Modificar
                </div>';
        }
    }
  }
}

// Validar producto

if (empty($_REQUEST['id'])) {
  header("Location: clientes.php");
} else {
  $id_producto = $_GET['id'];
  if (!is_numeric($id_producto)) {
    header("Location: productos.php");
  }
  $sql = "SELECT d.*, p.codproducto, p.descripcion 
            FROM detalle_temp d 
            INNER JOIN producto p 
                ON d.id_producto = p.codproducto
            WHERE d.id = $id_producto";

  $query_producto = mysqli_query($conexion, $sql);
  $result_producto = mysqli_num_rows($query_producto);

  if ($result_producto > 0) {
    $data_producto = mysqli_fetch_assoc($query_producto);
  } else {
    header("Location: usuarios.php");
  }
}
?>
<div class="row">
  <div class="col-lg-6 m-auto">

    <div class="card">
      <div class="card-header bg-primary text-white">
        Modificar producto
      </div>
      <div class="card-body">
        <form action="" method="post">
          <?php echo isset($alert) ? $alert : ''; 
            $id_p = $data_producto['id'];
            $proc = $data_producto['descripcion'];
            $cant = $data_producto['cantidad'];
            $parcial = $data_producto['precio_venta'];
            $tot = $data_producto['total'];
          ?>
          <spam style="color:red;">Los campos a modificar son los marcados con el ( * )</spam>
          <div class="form-group">
            <label for="codigo">Id producto</label>
            <input type="text" placeholder="Ingrese id del producto" name="codigo" id="codigo" class="form-control" value="<?php echo $id_p ?>">
          </div>
          <div class="form-group">
            <label for="producto">Producto</label>
            <input type="text" class="form-control" placeholder="Ingrese nombre del producto" name="producto" id="producto" value="<?php echo $proc ?>">

          </div>
          <div class="form-group">
            <label for="cantidad">Valor del producto</label>
            <input type="text" placeholder="Precio base" class="form-control" name="precio_base" id="precio_base" value="<?php echo $parcial ?>">

          </div>
          <div class="form-group">
            <label for="cantidad">Cantidad <spam style="color:red;">*</spam></label>
            <input type="text" placeholder="Cantidad de productos" class="form-control" name="cantidad" id="cantidad_edit" value="<?php echo $cant ?>">

          </div>
          <div class="form-group">
            <label for="total">Total <spam style="color:red;">*</spam></label>
            <input type="text" placeholder="Precio total" class="form-control" name="total" id="total_edit" value="<?php echo $tot ?>">

          </div>
          <input type="submit" value="Actualizar Producto" class="btn btn-primary">
          <a href="ventas.php" class="btn btn-danger">Atras</a>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once "includes/footer.php"; ?>