<?php
$usuarioHead = getUsuarioActual();
if (isset($usuarioHead)) {
    ?>
    <div class="element right ease3">
        <a  class="link" >
            <div id="menuPerfilLink">
                <span class="left">
                    <?php echo substr($usuarioHead->nombreUsuario, 0, 14); ?>
                </span>
                <div id="flechaPerfil" class="flechaAbajo left"></div>
            </div>
        </a>
        <div id="perfil_menu"> 
            <div id="flechitaPerfil"></div>
            <a href="/usuario/<?php echo $usuarioHead->uniqueUrl; ?>">
                <div id="perfil_image">
                    <img src="<?php echo $usuarioHead->avatar; ?>" class="img-polaroid">
                    <span><?php echo substr($usuarioHead->nombreUsuario, 0, 14); ?></span>
                    <br><br>
                    <span style="font-size: smaller">Editar perfil</span>
                </div>
            </a>
            <div id="perfil_links">
                <a href="/login/login/logout"><span>Cerrar Sesión</span></a><br>
            </div>
        </div>
    </div>
    <div class="element right ease3">
        <a  class="link" >
            <div id="menuCursosLink">
                <span class="left">Mis cursos</span>  
                <div id="flechaCursos" class="flechaAbajo left"></div>
            </div>
        </a>         
        <div id="cursos_menu">
            <div id="flechitaCursos"></div>

            <?php
            if (isset($_SESSION['cursos'])) {
                $cursosSession = $_SESSION['cursos'];
                ?>
                <div class="cursosMenuHeader">
                    Cursos que estoy tomando
                </div>                
                <?php
                foreach ($cursosSession as $cursoSess) {
                    ?>
                    <a href="/curso/<?php echo $cursoSess->uniqueUrl; ?>">
                        <div class="cursoMenuElement">
                            <div class="row-fluid">
                                <div class="span3">
                                    <img class="img-polaroid" src="<?php echo $cursoSess->imagen; ?>"/>
                                </div>
                                <div class="span9">
                                    <span><?php echo $cursoSess->titulo; ?></span>                                    
                                    <?php
                                    if ($cursoSess->numeroDeClases == 0)
                                        $porcentaje = 0;
                                    else
                                        $porcentaje = intval($cursoSess->numeroDeTomadas / $cursoSess->numeroDeClases * 100);
                                    ?>
                                    <div class="span11" style="height: 10px;min-height: 10px;">
                                        <div class="progress" style="height: 10px;">
                                            <div class="bar" style="width: <?php echo $porcentaje . '%'; ?>;"></div>
                                        </div>                                        
                                    </div>
                                    <br>
                                    <?php
                                    echo $porcentaje . "% completado";
                                    ?>                               
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php
                }
                ?>
                <a href="/usuarios/cursos/inscrito">
                    <div class="cusosMenuVerMas">
                        Ver todos >>
                    </div>
                </a>
                <?php
            } else {
                ?>                
                <div class="cursosMenuHeader">
                    No estás inscrito a ningún curso
                </div>
            <?php } ?>
        </div>
    </div>
    <?php
}
?>