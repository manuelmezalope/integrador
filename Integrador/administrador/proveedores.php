<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mad Market - Sistema de Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/administrador-estilo.css">
</head>
<body>
    <?php
        $conexion=pg_connect("host=localhost dbname=sistemainventario user=postgres password=root");

        if(!$conexion){
            echo "Un error de conexión ocurrió.";
            exit;
        }

        if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['accion']==='insertar')){
            $codprove=$_POST['codigoProveedor'] ?? '';
            $nombre=$_POST['nombreProveedor'] ?? '';
            $telefono=$_POST['telefonoProveedor'] ?? '';
            $direccion=$_POST['direccionProveedor'] ?? '';

            $sql1="INSERT INTO proveedor(cod_proveedor,nombre,telefono,direccion) VALUES ($1,$2,$3,$4)";
            $result1=pg_query_params($conexion,$sql1,array($codprove,$nombre,$telefono,$direccion));

            if(!$result1){
                echo "Un error de conexión ocurrió.";
                exit;
            }

            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit;
        }

        $result2=pg_query($conexion, "SELECT cod_proveedor,nombre,telefono,direccion FROM proveedor");
        if(!$result2){
            echo "Error al insertar proveedor.";
            exit;
        }

        if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['accion']==='actualizar')){
            $codprove=$_POST['cod_proveedor'];
            $nombre=$_POST['nombre'];
            $telefono=$_POST['telefono'];
            $direccion=$_POST['direccion'];

            $sql3="UPDATE proveedor SET nombre=$2, telefono=$3, direccion=$4 WHERE cod_proveedor=$1";

            $result4=pg_query_params($conexion,$sql3,array($codprove,$nombre,$telefono,$direccion));

            if(!$result4){
                echo "Error al seleccionar proveedor.";
                exit;
            }
        }

        if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['accion']==='eliminar')){
            $cod=$_POST['cod_proveedor'];

            $sqlproducto="DELETE FROM producto WHERE cod_proveedor=$1";
            $borrarproducto=pg_query_params($conexion,$sqlproducto,array($cod));

            if(!$borrarproducto){
                echo "Error al borrar proveedor de producto.";
            }

            $sql2="DELETE FROM proveedor WHERE cod_proveedor=$1";
            $result5=pg_query_params($conexion,$sql2,array($cod));

            if(!$result5){
                echo "Error al borrar proveedor.";
                exit;
            } else if($result5){
                echo "Proveedor eliminado.";
            }

            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit;
        }

        pg_close($conexion)
    ?>

    <div class="grid">
        <main class="principal">
            <button class="boton-menu" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>

    
            <div class="barra-lateral" id="barra-lateral">
                <div class="logo">
                    <h4><i class="fas fa-store"></i> MAD MARKET</h4>
                    <small id="userRole">Administrador</small>
                </div>

        
                <div class="turno-info">
                    <div class="fw-bold">María Alvarez</div>
                    <small>Turno: 08:00 - 16:00</small><br>
                    <small id="tiempoActivoSidebar">0h 0m activo</small>
                </div>

                <div class="nav flex-column mt-3">
                    <a href="dashboard.php" class="nav-link"><ul><i class="fas fa-tachometer-alt"></i>Dashboard</ul></a>
                    <a href="kardexprincipal.php" class="nav-link"><ul><i class="fas fa-boxes"></i>Kardex Principal</ul></a>
                    <a href="proveedores.php" class="nav-link"><ul><i class="fas fa-truck"></i>Proveedores</ul></a>
                    <a href="controlpersonal.php" class="nav-link"><ul><i class="fas fa-truck-loading"></i>Control de Personal</ul></a>
                    <a href="registroventas.php" class="nav-link"><ul><i class="fas fa-arrow-right"></i>Registro de Ventas</ul></a>
                    <a href="configuracion.php" class="nav-link"><ul><i class="fas fa-bell"></i>Configuración</ul></a>
                    <a href="#" class="nav-link"><ul><i class="fas fa-sign-out-alt"></i>Cerrar Sesión</ul></a>
                </div>
            </div>
        </main>

        <div class="secundario">
            <div class="header">
                <div class="caja-busqueda">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" placeholder="Buscar productos, ventas..." id="globalSearch">
                </div>
                
                <div class="usuario-info">
                    <div class="usuario-avatar" id="usuarioAvatar">AP</div>
                    <div>
                        <div class="fw-bold fs-5" id="userName">Admin Principal</div>
                        <small class="text-muted" id="userPosition">Administrador - Turno Activo</small>
                    </div>
                    <button class="btn btn-sm btn-outline-danger ms-3" onclick="cerrarTurno()">
                        <i class="fas fa-sign-out-alt me-1"></i>Cerrar Turno
                    </button>
                </div>
            </div>
            <br>
            <div class="contenedor-proveedores">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1"><i class="fas fa-truck me-2"></i>Gestión de Proveedores</h4>
                        <p class="text-muted mb-0">Administra los proveedores del sistema</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <h5 class="card-title"><i class="fas fa-plus me-2"></i>Insertar proveedor</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="accion" value="insertar">
                                <div class="row g-3">
                                    <div class=col-12>
                                        <label class="form-label" for="codigoProveedor">Código del Proveedor</label>
                                        <input type="text" id="codigoProveedor" name="codigoProveedor" class=form-control>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="nombreProveedor">Nombre del Proveedor</label>
                                        <input type="text" id="nombreProveedor" name="nombreProveedor" class="form-control" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label" for="telefonoProveedor">Teléfono</label>
                                        <input type="tel" id="telefonoProveedor" name="telefonoProveedor" class="form-control" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="direccionProveedor">Dirección</label>
                                        <textarea class="form-control" id="direccionProveedor" name="direccionProveedor" rows="2" required></textarea>
                                    </div>
                                </div>
                                <br>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <input type="submit" id="botonGuardarProveedor" class="btn btn-mad" name="submit">
                                    <input type="hidden" name="accion" value="insertar">
                                </div>
                                
                            </form>

                            <?php
                                if(isset($_POST['submit'])){
                                    $codprove=$_POST['codigoProveedor'];
                                    $nomprove=$_POST['nombreProveedor'];
                                    $telprove=$_POST['telefonoProveedor'];
                                    $dirprove=$_POST['direccionProveedor'];

                                    if(empty($codprove)){
                                        echo "<p>El código no puede estar vacío.</p>";
                                    } else if(strlen($codigoProveedor)>5){
                                        echo "<p class='error'>*El código es muy largo</p>";
                                    }
            
                                    if(empty($nomprove)){
                                        echo "<p>El nombre no puede estar vacío.</p>";
                                    } else if(!preg_match('/^[A-Z][a-zA-ZáéíóúÁÉÍÓÚÑñ\s]+$/', $nombreProveedor)){
                                        echo "<p>El nombre debe empezar con mayúscula.</p>";
                                    }
                                    
                                    if(empty($telprove)){
                                        echo "<p>El teléfono no puede estar vacío.</p>";
                                    } else if(strlen($telefonoProveedor)!=9 && $telefonoProveedor[0]!='9' && !is_numeric($telefonoProveedor)){
                                        echo "<p>El teléfono debe tener 9 números y debe empeza con 9.</p>";
                                    }
            
                                    if(empty($dirprove)){
                                        echo "<p>La dirección no puede estar vacía.</p>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Lista de Proveedores</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Proveedor</th>
                                        <th>Telefono</th> 
                                        <th>Dirección</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while($row1=pg_fetch_assoc($result2)){
                                        echo "
                                        <tr>
                                            <td>$row1[cod_proveedor]</td>
                                            <td><strong>$row1[nombre]</strong></td>
                                            <td><i class='fas fa-phone me-1'></i>$row1[telefono]</td>
                                            <td><i class='fas fa-map-marker me-1'></i>$row1[direccion]</td>
                                            <td>
                                                <div class='btn-group btn-group-sm'>
                                                    <button class='btn btn-outline-primary' title='Editar'>
                                                        <i class='fas fa-edit'></i>
                                                    </button>
                                                    <button class='btn btn-outline-info' title='Ver Productos'>
                                                        <i class='fas fa-boxes'></i>
                                                    </button>

                                                    <form method='POST'>
                                                        <input type='hidden' name='cod_proveedor' value='{$row1['cod_proveedor']}'>
                                                        <button class='btn btn-outline-primary' data-bs-toggle='modal' data-bs-target='#modalActualizarProveedor' tittle='Actualizar' name='accion' value='actualizar'>
                                                            <i class='fas fa-edit'></i>
                                                        </button>
                                                    </form>
                                            
                                                    <form method='POST'>
                                                        <input type='hidden' name='cod_proveedor' value='{$row1['cod_proveedor']}'>
                                                        <button class='btn btn-outline-danger' title='Eliminar' name='accion' value='eliminar'>
                                                            <i class='fas fa-trash'></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="modalActualizarProveedor" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Actualizar Proveedor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <form method='POST' name='accion' value='actualizar'>
                                <div class='row g-3'>
                                    <div class=col-12>
                                        <label class='form-label'>Código del Proveedor</label>
                                        <input type='text' name='cod_proveedor' class='form-control' required>
                                    </div>

                                    <div class='col-12'>
                                        <label class='form-label'>Nombre del Proveedor</label>
                                        <input type='text' name='nombre' class='form-control' required>
                                    </div>
                                    
                                    <div class='col-md-12'>
                                        <label class='form-label'>Teléfono</label>
                                        <input type='number' name='telefono' class='form-control' required>
                                    </div>

                                    <div class='col-12'>
                                        <label class='form-label'>Dirección</label>
                                        <textarea class='form-control' name='direccion' rows='2' required></textarea>
                                    </div>
                                </div>

                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                                    <button type='button' class='btn btn-mad' name='accion' value='actualizar'>Actualizar Proveedor</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/actualizarProveedor.js"></script>
</body>
</html>