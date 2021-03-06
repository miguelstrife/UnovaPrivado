<?php
require_once('layout/headers/headInicio.php');
require_once('layout/headers/headCierre.php');
?>
<div class="row">
    <div class="well span6 offset3">
        <div class="row-fluid">
            <legend>
                <h4 class="blue">Iniciar sesión</h4>
            </legend>
        </div>
        <?php
        if (isset($_GET['e'])) {
            switch ($_GET['e']) {
                case '1':
                    $msgLogin = "<strong>Alguien utilizó tus datos para iniciar sesión.</strong><br> Te recomendamos iniciar sesión y cambiar tu contraseña";
                    break;
                default:
                    $msgLogin = "Ocurrió un error con tu sesión";
                    break;
            }
        }
        if (isset($msgLogin)) {
            ?>
            <div class="row-fluid">                    
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>¡Error!</strong> <?php echo $msgLogin ?>
                </div>

            </div>
            <?php
        }
        ?>
        <div class="row-fluid">
            <form action="/login/login/loginSubmit" method="post" class="form-horizontal">
                <div class="control-group">
                    <label class="control-label" for="inputEmail">Correo Electrónico</label>
                    <div class="controls">
                        <input type="email" id="inputEmail" placeholder="Correo electrónico" name="email">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPassword">Contraseña</label>
                    <div class="controls">
                        <input type="password" id="inputPassword" placeholder="Contraseña" name="password">
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <label class="checkbox">
                            <input type="checkbox" name="recuerdame"> 
                            <?php
                            switch (getTipoLayout()) {
                                case 'desktop':
                                    echo 'Recordar mis datos en esta computadora';
                                    break;
                                case 'tablet':
                                case 'mobile':
                                    echo 'Recordar mis datos en este dispositivo';
                                    break;
                            }
                            ?>
                        </label>                        
                    </div>
                </div>
                <div class="contro-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </div>
                <?php
                if (isset($pagina))
                    echo '<input type="hidden" name="pagina" value="' . $pagina . '">';
                ?>
            </form>
        </div>
        <div class="row-fluid">
            <div class="span12 rightText">
                <a href="/usuarios/usuario/recuperarPassword">¿Olvidaste tu contraseña? Da click aquí</a>
            </div>
        </div>
    </div>
</div>
</div>


<?php
require_once('layout/foot.php');
?>