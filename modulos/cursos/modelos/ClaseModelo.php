<?php

require_once 'modulos/cursos/clases/Clase.php';

function altaClase($clase) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("INSERT INTO clase (idTema, titulo, idTipoClase, archivo, transformado, usoDeDisco, duracion, orden)
                             VALUES(:idTema, :titulo, :tipoClase, :archivo, :transformado, :usoDeDisco, :duracion, :orden)");
    $stmt->bindParam(':idTema', $clase->idTema);
    $stmt->bindParam(':titulo', $clase->titulo);
    $stmt->bindParam(':tipoClase', $clase->idTipoClase);
    $stmt->bindParam(':archivo', $clase->archivo);
    $stmt->bindParam(':transformado', $clase->transformado);
    $stmt->bindParam(':usoDeDisco', $clase->usoDeDisco);
    $stmt->bindParam(':duracion', $clase->duracion);
    $stmt->bindParam(':orden', $clase->orden);
    $id = -1;
    if ($stmt->execute())
        $id = $conex->lastInsertId();
    else {
        //print_r($stmt->errorInfo());
    }
    return $id;
}

function bajaClase($idClase) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("DELETE FROM clase WHERE idClase = :id");
    $stmt->bindParam(':id', $idClase);
    $stmt->execute();
    return $stmt->rowCount();
}

function actualizaInformacionClase($clase) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("UPDATE clase 
                            SET titulo = :titulo, descripcion = :descripcion
                            WHERE idClase = :idClase");
    $stmt->bindParam(':titulo', $clase->titulo);
    $stmt->bindParam(':descripcion', $clase->descripcion);
    $stmt->bindParam(':idClase', $clase->idClase);
    return $stmt->execute();
}

function actualizaDuracionClase($idClase, $duration) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("UPDATE clase SET duracion = :duracion
                            WHERE idClase = :idClase");
    $stmt->bindParam(':duracion', $duration);
    $stmt->bindParam(':idClase', $idClase);
    return $stmt->execute();
}

function actualizaCodigoClase($idClase, $codigo) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("UPDATE clase SET codigo = :codigo
                            WHERE idClase = :idClase");
    $stmt->bindParam(':codigo', $codigo);
    $stmt->bindParam(':idClase', $idClase);
    return $stmt->execute();
}

function actualizaOrdenClase($idClase, $idTema, $orden) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("UPDATE clase 
                            SET orden = :orden, idTema = :idTema
                            WHERE idClase = :idClase");
    $stmt->bindParam(':orden', $orden);
    $stmt->bindParam(':idTema', $idTema);
    $stmt->bindParam(':idClase', $idClase);
    return $stmt->execute();
}

function actualizaArchivosDespuesTransformacion($idClase, $archivo, $archivo2, $usoDeDisco, $duration) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("UPDATE clase 
                            SET transformado = 1, archivo = :archivo , archivo2 = :archivo2, 
                            usoDeDisco = :usoDeDisco, duracion = :duracion
                            WHERE idClase = :idClase");
    $stmt->bindParam(':archivo', $archivo);
    $stmt->bindParam(':archivo2', $archivo2);
    $stmt->bindParam(':usoDeDisco', $usoDeDisco);
    $stmt->bindParam(':idClase', $idClase);
    $stmt->bindParam(':duracion', $duration);
    return $stmt->execute();
}

function getClase($idClase) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("SELECT * FROM clase where idClase = :idClase");
    $stmt->bindParam(':idClase', $idClase);
    $stmt->execute();
    $clase = NULL;
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $clase = new Clase();
        $clase->idClase = $row['idClase'];
        $clase->idTema = $row['idTema'];
        $clase->idTipoClase = $row['idTipoClase'];
        $clase->titulo = $row['titulo'];
        $clase->orden = $row['orden'];
        $clase->codigo = $row['codigo'];
        $clase->descripcion = $row['descripcion'];
        $clase->archivo = $row['archivo'];
        $clase->archivo2 = $row['archivo2'];
        $clase->transformado = $row['transformado'];
        $clase->view = $row['views'];
        $clase->duracion = $row['duracion'];
        $clase->usoDeDisco = $row['usoDeDisco'];
    }
    return $clase;
}

function getImagenTipoClase($tipoClase) {
    switch ($tipoClase) {
        case 0://Video
            return '/layout/imagenes/video.png';
            break;
        case 1://Presentacion
            return '/layout/imagenes/presentation.png';
            break;
        case 2://Pdf
            return '/layout/imagenes/document.png';
            break;
        case 3://Caja
            return '/layout/imagenes/document.png';
            break;
        case 4://Audio
            return '/layout/imagenes/audio.png';
            break;
        default:
            return '/layout/imagenes/document.png';
            break;
    }
}

function clasePerteneceACurso($idCurso, $idClase) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("Select c.idCurso, cl.idClase
                             FROM curso c, tema t, clase cl
                             WHERE c.idCurso = t.idCurso AND t.idTema = cl.idTema 
                             AND c.idCurso = :idCurso AND cl.idClase = :idClase");
    $stmt->bindParam(":idCurso", $idCurso);
    $stmt->bindParam(":idClase", $idClase);
    $stmt->execute();
    if ($stmt->rowCount() == 1) {
        return true;
    } else {
        return false;
    }
}

function getCursoPerteneciente($idClase) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("Select c.idCurso, c.idUsuario, c.titulo, c.uniqueUrl, c.descripcionCorta, c.descripcion, c.imagen, c.rating
                             FROM curso c, tema t, clase cl
                             WHERE c.idCurso = t.idCurso AND t.idTema = cl.idTema 
                             AND cl.idClase = :idClase");
    $stmt->bindParam(":idClase", $idClase);
    $curso = NULL;
    if ($stmt->execute()) {
        $row = $stmt->fetch();
        require_once 'modulos/cursos/clases/Curso.php';
        $curso = new Curso();
        $curso->idCurso = $row['idCurso'];
        $curso->idUsuario = $row['idUsuario'];
        $curso->titulo = $row['titulo'];
        $curso->uniqueUrl = $row['uniqueUrl'];
        $curso->descripcionCorta = $row['descripcionCorta'];
        $curso->descripcion = $row['descripcion'];
        $curso->imagen = $row['imagen'];
        $curso->rating = $row['rating'];
    }
    return $curso;
}

function sumarVistaClase($idClase) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("UPDATE clase 
                            SET views = views + 1
                            WHERE idClase = :idClase");
    $stmt->bindParam(':idClase', $idClase);
    return $stmt->execute();
}

function obtenerClaseSiguienteYanterior($idCurso, $idClase) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("SELECT c.idClase, c.idTema, c.titulo, c.orden, c.idTipoClase, c.transformado, c.views, c.duracion
                            FROM clase c, tema t
                            WHERE c.idTema = t.idTema AND t.idCurso = :id
                            ORDER BY  t.idTema, orden ASC ");
    $stmt->bindParam(':id', $idCurso);
    if (!$stmt->execute())
        print_r($stmt->errorInfo());
    $rows = $stmt->fetchAll();
    $anterior = -1;
    $siguiente = -1;
    $encontrada = false;
    $i = 0;
    while ($i < sizeof($rows) && !$encontrada) {
        if ($rows[$i]['idClase'] == $idClase) {
            $encontrada = true;
        } else {
            $anterior = $rows[$i]['idClase'];
        }
        $i++;
    }
    if($encontrada){
        if(isset($rows[$i])){
            $siguiente = $rows[$i]['idClase'];
        }
    }
    return array(
        "anterior" => $anterior,
        "siguiente" => $siguiente
    );
}

function getTotalDiscoUtilizado() {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->query("SELECT SUM(usoDeDisco) as suma 
                          FROM clase");
    $count = 0;
    foreach ($stmt as $row) {
        $count = $row['suma'];
    }
    return $count;
}

//Esta función borra todas las clases que pertenecen a un usuario y los archivos en el S3 de Amazon
function borrarClasesConArchivosDeUsuario($idUsuario) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("SELECT c.idClase, c.archivo, c.archivo2, c.idTipoClase, c.transformado
                            FROM clase c, tema t, curso cu
                            WHERE c.idTema = t.idTema 
                            AND t.idCurso = cu.idCurso
                            AND cu.idUsuario = :idUsuario");
    $stmt->bindParam(':idUsuario', $idUsuario);
    if (!$stmt->execute())
        print_r($stmt->errorInfo());
    $rows = $stmt->fetchAll();
    $clase = null;
    $todoOk = true;
    $error = "";
    foreach ($rows as $row) {
        $clase = new Clase();
        $clase->archivo = $row['archivo'];
        $clase->archivo2 = $row['archivo2'];
        $clase->idTipoClase = $row['idTipoClase'];
        $clase->idClase = $row['idClase'];
        $clase->transformado = $row['transformado'];
        if ($clase->transformado == 1) {
            require_once 'modulos/aws/modelos/s3Modelo.php';
            deleteFileFromS3ByUrl($clase->archivo);
            if ($clase->idTipoClase == 0 || $clase->idTipoClase == 4) {
                //si es video o audio borramos el archivo2
                deleteFileFromS3ByUrl($clase->archivo2);
            }
            if (bajaClase($clase->idClase) == 0) {
                $todoOk = false;
                $error = "Ocurrió un error al borrar la clase";
            }
        } else {
            $todoOk = false;
            $error = "No puedes borrar el curso mientras uno de sus archivos se está transformando";
        }
    }
    return array(
        "res" => $todoOk,
        "error" => $error);
}

//Esta funcion borra todas las clases de un curso, incluyendo sus archivos en el S3 de Amazon
function borrarClasesConArchivosDeCurso($idCurso) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("SELECT c.idClase, c.archivo, c.archivo2, c.idTipoClase, c.transformado
                            FROM clase c, tema t
                            WHERE c.idTema = t.idTema 
                            AND t.idCurso = :idCurso");
    $stmt->bindParam(':idCurso', $idCurso);
    if (!$stmt->execute())
        print_r($stmt->errorInfo());
    $rows = $stmt->fetchAll();
    $clase = null;
    $todoOk = true;
    $error = "";
    foreach ($rows as $row) {
        $clase = new Clase();
        $clase->archivo = $row['archivo'];
        $clase->archivo2 = $row['archivo2'];
        $clase->idTipoClase = $row['idTipoClase'];
        $clase->idClase = $row['idClase'];
        $clase->transformado = $row['transformado'];
        if ($clase->transformado == 1) {
            require_once 'modulos/aws/modelos/s3Modelo.php';
            deleteFileFromS3ByUrl($clase->archivo);
            if ($clase->idTipoClase == 0 || $clase->idTipoClase == 4) {
                //si es video borramos el archivo2
                deleteFileFromS3ByUrl($clase->archivo2);
            }
            if (bajaClase($clase->idClase) == 0) {
                $todoOk = false;
                $error = "Ocurrió un error al borrar la clase";
            }
        } else {
            $todoOk = false;
            $error = "No puedes borrar el curso mientras uno de sus archivos se está transformando";
        }
    }
    return array(
        "res" => $todoOk,
        "error" => $error);
}

function crearClaseDeArchivo($idUsuario, $idCurso, $idTema, $fileName, $fileType) {
    require_once 'modulos/usuarios/modelos/usuarioModelo.php';
    require_once 'modulos/cursos/modelos/CursoModelo.php';
    require_once 'modulos/cursos/modelos/TemaModelo.php';
    //Carpeta donde se va a guardar el archivo temporal
    $filePath = getServerRoot() . "/archivos/temporal/uploaderFiles/";
    $res = array();
    //Validamos que el curso sea del usuario y que el tema sea del curso        
    if (getIdUsuarioDeCurso($idCurso) == $idUsuario && $idCurso == getIdCursoPerteneciente($idTema)) {
        //Guardamos el nombre original del archivo para establecerlo como titulo
        $pathInfo = pathinfo($filePath . $fileName);
        $titulo = $pathInfo['filename'];
        $newName = getUniqueCode(64) . "." . $pathInfo['extension'];
        require_once 'funcionesPHP/funcionesParaArchivos.php';
        //Le cambiamos el nombre del archivo a uno generico
        if (rename($filePath . $fileName, $filePath . $newName)) {
            $file = $filePath . $newName;
            $pathInfo = pathinfo($file);
            $clase = new Clase();
            $clase->idTema = $idTema;
            $clase->titulo = $titulo;
            $clase->idTipoClase = getTipoClase($fileType);

            //Establecemos el ancho de banda utilizado por la subida de este archivo
            $size = getFileSize($file);
            require_once('modulos/principal/modelos/variablesDeProductoModelo.php');
            deltaVariableDeProducto("usoActualAnchoDeBanda", $size);

            require_once 'modulos/aws/modelos/s3Modelo.php';
            if ($clase->idTipoClase == 0 || $clase->idTipoClase == 4) {
                //Creamos la clase en la bd
                //Si es video o audio creamos la clase con la bandera que todavía no se transforma
                $clase->transformado = 0;
                $clase->usoDeDisco = 0;
                $clase->duracion = "00:00";
                $clase->orden = getUltimoOrdenEnTema($idTema) + 1;
                $idClase = altaClase($clase);
                if ($idClase >= 0) {
                    //Subimos el archivo al servicio S3 de amazon
                    $s3res = uploadFileToS3($file);
                    if ($s3res['res']) {
                        //El archivo se subio al cdn
                        //Generamos los datos del mensaje
                        $datosDelMensaje = array(
                            "bucket" => $s3res['bucket'],
                            "key" => $s3res['key'],
                            "tipo" => $clase->idTipoClase,
                            "host" => getDomainName(),
                            "idClase" => $idClase
                        );
                        $datosJson = json_encode($datosDelMensaje);
                        require_once 'modulos/aws/modelos/sqsModelo.php';
                        if (AddMessageToQueue($datosJson)) {
                            //Se mando correctamente el mensaje
                            //Se dió de alta correctamente
                            $res['resultado'] = true;
                            $res['url'] = "#";
                        } else {
                            //Ocurrio un eror al agregar el mensaje
                            $res['resultado'] = false;
                            $res['mensaje'] = "Ocurrió un error al guardar tu archivo en nuestros servidores. Intenta de nuevo más tarde";
                        }
                    } else {
                        //Erro al subir el archivo al s3 de amazon
                        $res['resultado'] = false;
                        $res['mensaje'] = "Ocurrió un error al guardar tu archivo en nuestros servidores. Intenta de nuevo más tarde";
                    }
                } else {
                    //Ocurrió un error al agregar a la bd
                    $res['resultado'] = false;
                    $res['mensaje'] = "Ocurrió un error al guardar tu archivo en nuestros servidores. Intenta de nuevo más tarde";
                }
            } else {
                $clase->transformado = 1;
                //Subimos el archivo al servicio S3 de amazon
                $s3res = uploadFileToS3($file);
                if ($s3res['res']) {
                    //Si se subio, guardamos la clase en la bd
                    $clase->archivo = $s3res['link'];
                    $clase->usoDeDisco = $size;
                    $clase->orden = getUltimoOrdenEnTema($idTema) + 1;
                    $idClase = altaClase($clase);
                    if ($idClase >= 0) {
                        //Se dió de alta correctamente
                        $res['resultado'] = true;
                        $res['url'] = "#";
                    } else {
                        //Ocurrió un error al agregar a la bd
                        $res['resultado'] = false;
                        $res['mensaje'] = "Ocurrió un error al guardar tu archivo en nuestros servidores. Intenta de nuevo más tarde";
                    }
                } else {
                    //Si ocurrió un error al subir al s3
                    $res['resultado'] = false;
                    $res['mensaje'] = "Ocurrió un error al guardar tu archivo en nuestros servidores. Intenta de nuevo más tarde";
                }
            }
            //Sin importar que paso, borramos el archivo temporal
            unlink($file);
        } else {
            //Si ocurrió un error, se borra y regresamos false
            unlink($filePath . $fileName);
            $res['resultado'] = false;
            $res['mensaje'] = "El nombre del archivo no es válido";
        }
    } else {
        //Hay errores en la integridad usuario <-> curso
        //borramos el archivo
        unlink($filePath . $fileName);
        $res['resultado'] = false;
        $res['mensaje'] = "No tienes permisos para modificar este curso";
    }
    return $res;
}

function getTipoClase($fileType) {
    //Si es video
    if (stristr($fileType, "video")) {
        return 0;
    }

    if (stristr($fileType, "presentation") || stristr($fileType, "powerpoint")) {
        return 1;
    }

    if (stristr($fileType, "word") || stristr($fileType, "pdf")) {
        return 2;
    }

    //tipo de clase 3 son las tarjetas de aprendizaje, no es un archivo

    if (stristr($fileType, "audio")) {
        return 4;
    }
}

function registrarClaseTomada($idUsuario, $idClase) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("INSERT INTO tomoclase (fecha, idUsuario, idClase)
                             VALUES(NOW(), :idUsuario, :idClase)");
    $stmt->bindParam(':idUsuario', $idUsuario);
    $stmt->bindParam(':idClase', $idClase);
    return $stmt->execute();
}

?>