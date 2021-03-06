<?php
require_once('layout/headers/headInicio.php');
require_once('layout/headers/headPerfil.php');
require_once('layout/headers/headCierre.php');
?>
<div class="row-fluid">
    <div class="span12 well">
        <div class="span3">
            <div class="row-fluid">
                <img class="hidden-phone span12 img-polaroid"src="<?php echo $usuarioPerfil->avatar; ?>" >
                <img class="visible-phone imageSmallPhone span12 img-polaroid"src="<?php echo $usuarioPerfil->avatar; ?>" >
            </div>
            <?php
            if ($miPerfil) {
                ?>
                <div class="row-fluid">
                    <a href="/usuarios/usuario/cambiarImagen" class="span12 centerText">Cambiar imagen</a>
                </div>
                <?
            }
            ?>
        </div>
        <div class="span9">
            <legend class="break-words">
                <h3><?php echo $usuarioPerfil->nombreUsuario; ?></h3>
            </legend>
            <div class="row-fluid">
                <div class="span8">
                    <div class="row-fluid">
                        <h4 class="black">
                            <?php echo $usuarioPerfil->tituloPersonal; ?>
                        </h4>
                    </div>
                    <div class="row-fluid">
                        <strong>
                            <?php
                            if ($numCursos > 0) {
                                if ($numCursos == 1)
                                    echo "Enseñando en " . $numCursos . " curso";
                                else
                                    echo "Enseñando en " . $numCursos . " cursos";
                            }
                            ?>
                        </strong>
                    </div>
                    <div class="row-fluid">
                        <strong>
                            <?php
                            if ($numTomados == 1)
                                echo "Tomando " . $numTomados . " curso";
                            else
                                echo "Tomando " . $numTomados . " cursos";
                            ?>
                        </strong>
                    </div>
                </div>
                <div class="span4">
                    <?php
                    if ($miPerfil) {
                        ?>
                        <div class="row-fluid">
                            <div class="span12">
                                <a  href="/usuarios/usuario/editarInformacion/<?php echo $usuarioPerfil->idUsuario; ?>">
                                    <div class="btn span12">
                                        <i class=" icon-pencil"></i>
                                        Editar mi información
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="row-fluid"><h4></h4></div>
                        <div class="row-fluid">
                            <div class="span12">
                                <a href="/usuarios/usuario/cambiarPassword">
                                    <div class="btn span12">
                                        <i class=" icon-cog"></i>
                                        Cambiar mi contraseña
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span12 well ">
        <legend><h3>Biografía</h3></legend>
        <div class="mostrarListas">
            <?php
            if (isset($usuarioPerfil->bio)) {
                echo $usuarioPerfil->bio;
            }
            ?>
        </div>
    </div>
</div>
<?php
require_once('layout/foot.php');
?>
