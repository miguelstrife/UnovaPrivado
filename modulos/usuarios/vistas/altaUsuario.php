<?php
require_once('layout/headers/headInicio.php');
require_once('layout/headers/headBootstrap.php');
require_once('layout/headers/headAsignarUsuarioCurso.php');
require_once('layout/headers/headCierre.php');
?>
<div class="container">
    <div class="contenido">
        <div class="row-fluid">
            <div class="span12">
                <div class="well well-large">
                    <form class="form-horizontal" action="/alumnos/usuario/altaUsuariosSubmit" method="post">
                        <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">
                        <?php
                        switch ($tipo) {
                            case "altaAlumno":
                                echo '<legend>Agregar alumnos</legend>';
                                break;
                        }
                        ?>
                        <div class="control-group">
                            <label class="control-label">Emails</label>
                            <div class="controls">
                                <?php
                                switch ($tipo) {
                                    case "altaAlumno":
                                        echo '<textarea name="usuarios" class="span10" rows="6" placeholder="Introduce los emails de los alumnos separados por comas"></textarea>';
                                        break;
                                }
                                ?>

                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button type="submit" class="btn">Aceptar</button>
                            </div>
                        </div>
                    </form>
                    <form class="form-horizontal">
                        <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">
                        <?php
                        switch ($tipo) {
                            case "altaAlumno":
                                echo '<legend>Agregar alumnos con un archivo .csv</legend>';
                                echo '';
                                break;
                        }
                        ?>
                        <div class="control-group">
                            <label class="control-label">Seleccionar archivo .cvs</label>
                            <div class="controls">
                                <input type="file" name="archivo">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button type="submit" class="btn">
                                    Aceptar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span3">
            <a class="btn btn-inverse btn-small" href="/alumnos">
                <i class="icon-white icon-arrow-left"></i>
                Regresar
            </a>
        </div>
    </div>
</div>



<?php
require_once('layout/foot.php');
?>
            