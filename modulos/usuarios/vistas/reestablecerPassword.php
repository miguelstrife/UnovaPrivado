<?php
require_once('layout/headers/headInicio.php');
require_once('layout/headers/headCambiarPassword.php');
require_once('layout/headers/headCierre.php');
?>
<div class="row-fluid">
    <div class="well span8 offset2">
        <div class="row-fluid">
            <legend>
                <h4 class="blue">Cambiar contraseña</h4>
            </legend>
        </div>
        <?php
        if (isset($msgForma)) {
            ?>
            <div class="row-fluid">
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>¡Error! </strong> <?php echo $msgForma; ?>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="row-fluid">
            <form method="post" id="customForm" action="/usuarios/usuario/reestablecerPasswordSubmit" class="form-horizontal">
                <input type="hidden" name="uuid" value="<?php echo $uuid; ?>">
                <div class="control-group">
                    <label class="control-label" for="inputPass1">Contraseña:</label>
                    <div class="controls">
                        <input class="span6" id="inputPass1" name="pass1" type="password"/>  
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPass2">Repetir contraseña:</label>
                    <div class="controls">
                        <input class="span6" id="inputPass2" name="pass2" type="password"/> 
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-primary"> Aceptar </button>  
                        <a class="btn offset1" href="/"> Cancelar </a>  
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
require_once('layout/foot.php');
?>