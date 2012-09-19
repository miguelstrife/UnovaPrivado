<?php
require_once('layout/headers/headInicio.php');
require_once('layout/headers/headBootstrap.php');
require_once ('layout/headers/headListaCursos.php');
require_once('layout/headers/headCierre.php');
?>


<div class="contenido">
    <h4 style="text-align: center;">Cursos</h4>
    <div style="background: #F7F7F7;">
        <div class="cursosContainer">
            <ul class="listaCursos">
                <?php
                if (isset($cursos) && !is_null($cursos)) {
                    foreach ($cursos as $curso) {
                        ?>
                        <li class="curso">
                            <a href="/curso/<?php echo $curso->uniqueUrl; ?>">
                                <div class="thumb" style="background: url(<?php echo $curso->imagen; ?>);"></div>
                            </a>                        
                            <div class="detalles">
                                <span class="titulo left">
                                    <?php
                                    echo '<a href="/curso/' . $curso->uniqueUrl . '">' . substr($curso->titulo, 0, 40) . '</a>';
                                    ?>                    
                                </span>
                                <br>
                                <span class="autor left">
                                    Autor: <a href="<?php echo $curso->uniqueUrlUsuario ?>"><?php echo $curso->nombreUsuario; ?></a>
                                </span>
                                <br>
                                <div class="left botones">
                                    <div class="left btn-group">
                                        <a class="btn btn-primary" href="#"><i class="icon-user icon-white"></i> Usuarios</a>
                                        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="/cursos/curso/alumnos/<?php echo $curso->idCurso; ?>"><i class="icon-pencil"></i> Ver inscritos</a></li>
                                            <li><a href="#"><i class="icon-plus"></i> Inscribir usuario(s)</a></li>                                            
                                        </ul>
                                    </div>
                                    <div class="left btn-group">
                                        <a class="btn btn-warning" href="#"><i class="icon-share icon-white"></i> Grupos</a>
                                        <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#"><i class="icon-pencil"></i> Ver asignados</a></li>
                                            <li><a href="#"><i class="icon-plus"></i> Asignar grupo(s)</a></li>                                            
                                        </ul>
                                    </div>                                
                                </div>
                            </div>
                            <div>
                                <div class="numDetalles numAlumnos">   
                                    <?php echo $curso->numeroDeAlumnos; ?>
                                    <span>Alumnos</span>
                                </div>
                                <div class="numDetalles numClases">                                    
                                    <?php echo $curso->numeroDeClases; ?>
                                    <span>Clases</span>
                                </div>   
                            </div>

                            <div class="right botonBorrar">
                                <div class="left btn-group">
                                    <a class="btn btn-danger" href="#"><i class="icon-fire icon-white"></i> Eliminar</a>
                                </div>
                            </div>
                        </li>
                        <?php
                    }
                } else {
                    echo '<li><h2>No hay más cursos</h2></li>';
                }
                ?>
            </ul>
        </div>
        <div class="pagination pagination-centered">
            <ul>
                <?php
                if ($pagina > 1)
                    echo '<li><a href="/cursos?p=' . ($pagina - 1) . '">«</a></li>';
                else
                    echo '<li class="disabled"><a href="#">«</a></li>';

                for ($i = 1; $i <= $maxPagina; $i++) {
                    if ($i == $pagina)
                        echo '<li class="active"><a href="#">' . $i . '</a></li>';
                    else
                        echo '<li><a href="/cursos?p=' . $i . '">' . $i . '</a></li>';
                }

                if ($pagina < $maxPagina)
                    echo '<li><a href="/cursos?p=' . ($pagina + 1) . '">»</a></li>';
                else
                    echo '<li class="disabled"><a href="#">»</a></li>';
                ?>
            </ul>
        </div>
    </div>

</div>



<?php
require_once('layout/foot.php');
?>
            