<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mad Market - Sistema de Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/administrador-controlpersonal/datos.css">
    <link rel="stylesheet" href="css/administrador-estilo.css">
</head>
<body>
    <?php
    $conexion=pg_connect("host=localhost dbname=sistemainventario user=postgres password=root");
    if(!$conexion){
        echo "Un error de conexión ocurrió.";
    }

    $result1=pg_query($conexion,"SELECT COUNT(cod_empleado) AS cantidad_empleado FROM empleado");
    if(!$result1){
        echo "Error al contar los empleados.";
    }

    $result2=pg_query($conexion,"SELECT e.cod_empleado AS codigo_empleado,e.nombre AS empleado_nombre,e.apellido AS apellido,e.dni AS dni,e.fecha_nacimiento AS fec_nac,
                                 e.telefono AS telefono,r.nombre AS rol_nombre FROM empleado e 
                                 JOIN rol r ON e.cod_rol=r.cod_rol");
    if(!$result2){
        echo "Error al seleccionar los empleados.";
    }

    $result3=pg_query($conexion,"SELECT nombre FROM rol");
    if(!$result3){
        echo "Error al seleccionar el rol.";
    }

    $result4=pg_query($conexion,"SELECT u.cod_usuario AS codigo_usuario,u.cod_empleado AS codigo_empleado,u.usuario AS usuario,u.clave AS clave,eu.nombre AS 
                      estadousuario_nombre FROM usuario u
                      JOIN estadousuario eu ON u.cod_estadousuario=eu.cod_estadousuario");
    if(!$result4){
        echo "Error al seleccionar el usuario.";
    }

    if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['accion']==='insertar')){
            $codemp=$_POST['codigoEmpleado'] ?? '';
            $nomeemp=$_POST['nombreEmpleado'] ?? '';
            $apelemp=$_POST['apellidoEmpleado'] ?? '';
            $fecnacemp=$_POST['fechaNacEmpleado'] ?? '';
            $dniemp=$_POST['dniEmpleado'] ?? '';
            $telemp=$_POST['telefonoEmpleado'] ?? '';
            $rolemp=$_POST['rolEmpleado'] ?? '';

            $sql1="INSERT INTO empleado(cod_empleado,nombre,telefono,direccion) VALUES ($1,$2,$3,$4)";
            $result5=pg_query_params($conexion,$sql1,array($codprove,$nombre,$telefono,$direccion));

            if(!$result5){
                echo "Un error de conexión ocurrió.";
                exit;
            }

            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit;
        }
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0"><i class="fas fa-users me-2"></i>Control de Personal</h1>
                    <div>
                        <button class="btn btn-mad" data-bs-toggle="modal" data-bs-target="#modalEmpleado">
                            <i class="fas fa-plus me-2"></i>Nuevo Empleado
                        </button>
                    <div class="text-muted d-inline-block ms-3">Mes: Diciembre 2024</div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <?php
                            while($row1=pg_fetch_assoc($result1)){
                                echo "
                                <h3 class='text-primary mb-1'>$row1[cantidad_empleado]</h3>
                                ";

                                if($row1['cantidad_empleado']=1){
                                    echo "<div><small class='text-muted'>empleado</small></div>";
                                } else {
                                    echo "<div><small class='text-muted'>empleados</small></div>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3 class="text-success mb-1">156</h3>
                            <small class="text-muted">Horas Trabajadas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3 class="text-warning mb-1">12.5</h3>
                            <small class="text-muted">Horas Extras</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3 class="text-info mb-1">6</h3>
                            <small class="text-muted">Usuarios Sistema</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Empleado</th>
                                    <th>DNI</th>
                                    <th>Rol</th>
                                    <th>Teléfono</th>
                                    <th>Fecha de Nacimiento</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while($row2=pg_fetch_assoc($result2)){
                                    echo "
                                    <tr>
                                        <td>$row2[codigo_empleado]</td>
                                        <td>$row2[empleado_nombre]";
                                    
                                    echo " $row2[apellido]</td>";
                                        

                                    echo "    
                                        <td>$row2[dni]</td>
                                        <td>$row2[rol_nombre]</td>
                                        <td>$row2[telefono]</td>
                                        <td>$row2[fec_nac]</td>
                                        <td>$row2</td>
                                    </tr>
                                    ";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Código de Usuario</th>
                                    <th>Código de Empleado</th>
                                    <th>Usuario</th>
                                    <th>Clave</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while($row4=pg_fetch_assoc($result4)){
                                    echo "
                                    <td>$row4[codigo_usuario]</td>
                                    <td>$row4[codigo_empleado]</td>
                                    <td>$row4[usuario]</td>
                                    <td>$row4[clave]</td>
                                    <td>$row4[estadousuario_nombre]</td>
                                    ";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalEmpleado" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Nuevo Empleado</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formEmpleado">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label" for="">Código</label>
                                        <input type="text" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Apellido</label>
                                        <input type="text" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">DNI</label>
                                        <input type="number" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Teléfono</label>
                                        <input type="tel" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fecha Nacimiento</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Cargo</label>
                                    <select class="form-select" required>
                                        <option value="">Seleccionar cargo...</option>
                                        <?php
                                        while($row3=pg_fetch_assoc($result3)){
                                            echo "
                                            <option>$row3[nombre]</option>
                                            ";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-mad" onclick="guardarEmpleado()">
                                <i class="fas fa-save me-2"></i>Guardar Empleado
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>