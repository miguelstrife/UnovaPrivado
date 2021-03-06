<?php
require_once('layout/headers/headInicio.php');
require_once('layout/headers/headListaUsuarios.php');
require_once('layout/headers/headCierre.php');
?>
<div class="row-fluid">
    <div class="span12 well well-small">
        <legend>
            <?php
            switch ($tipo) {
                case "altaAlumno":
                    $urlRegreso = "/alumnos";
                    $urlAlta = "/alumnos/usuario/altaAlumnos";
                    $msgAlta = " alumnos ";
                    echo "<h4 class='blue'>Alta de alumnos</h4>";
                    break;
                case "altaProfesor":
                    $urlRegreso = "/profesores";
                    $urlAlta = "/profesores/usuario/altaProfesores";
                    $msgAlta = " profesores ";
                    echo "<h4 class='blue'>Alta de profesores</h4>";
                    break;
                case "altaAdministrador":
                    $urlRegreso = "/administradores";
                    $urlAlta = "/alumnos/usuario/altaAdministradores";
                    $msgAlta = " administradores ";
                    echo "<h4 class='blue'>Alta de administradores</h4>";
                    break;
            }
            ?>
        </legend>
        <div class="row-fluid">
            <div class="span6 well well-small whiteBackground">
                <legend class="warning">
                    <span class="badge badge-success" style="position: relative; top: -3px;"><?php echo $numAltas; ?></span>
                    Altas satisfactorias
                </legend>
                <?php
                foreach ($usuarios as $usuario) {
                    ?>
                    <div class="row-fluid">
                        <div class="span1">
                            <span class="label label-success">
                                <i class="icon-white icon-ok-circle"></i>
                            </span>
                        </div>
                        <div class="span5">
                            <?php
                            echo '<a href="/usuario/' . $usuario->uniqueUrl . '">' . $usuario->nombreUsuario . '</a>';
                            ?>
                        </div>
                        <div class="span5">
                            <?php
                            echo $usuario->email;
                            ?>
                        </div>
                    </div>

                    <?php
                }
                ?>
            </div>
            <div class="span6 well well-small whiteBackground">
                <legend>
                    <span class="badge badge-important" style="position: relative; top: -3px;"><?php echo $numFallos; ?></span>
                    Errores
                </legend>
                <?php
                foreach ($fallos as $fallo) {
                    ?>
                    <div class="row-fluid">
                        <div class="span1">
                            <span class="label label-important">
                                <i class="icon-white icon-warning-sign"></i>
                            </span>
                        </div>
                        <div class="span5">
                            <?php
                            echo $fallo['email'];
                            ?>
                        </div>
                        <div class="span5">
                            <?php
                            echo $fallo['mensaje'];
                            ?>
                        </div>
                    </div>
                    <div class="row-fluid"><div class="span12"></div></div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="row-fluid">
            <h6></h6>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <a class="btn btn-inverse" href="<?php echo $urlRegreso; ?>">
                    <i class="icon-white icon-arrow-left"></i>
                    Regresar a la lista de <?php echo$msgAlta; ?>
                </a>
            </div>
            <div class="span3">
                <a class="btn btn-primary" href="<?php echo $urlAlta; ?>">
                    <i class="icon-white icon-plus"></i>
                    Dar de alta más <?php echo $msgAlta; ?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php
$noBreadCrumbs = true;
require_once('layout/foot.php');
?>
            