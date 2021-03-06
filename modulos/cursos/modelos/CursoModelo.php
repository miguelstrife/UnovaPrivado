<?php

require_once 'modulos/cursos/clases/Curso.php';

function altaCurso($curso) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("INSERT into curso (idUsuario, titulo, uniqueUrl, descripcionCorta, fechaCreacion, publicado) 
                             values (:idUsuario, :titulo, :uniqueUrl, :descripcionCorta, NOW(), :publicado)");
    $stmt->bindParam(':idUsuario', $curso->idUsuario);
    $stmt->bindParam(':titulo', $curso->titulo);
    $stmt->bindParam(':uniqueUrl', $curso->uniqueUrl);
    $stmt->bindParam(':descripcionCorta', $curso->descripcionCorta);
    $stmt->bindParam(':publicado', $curso->publicado);
    $id = -1;
    $val = $stmt->execute();
    if ($val) {
        $id = $conex->lastInsertId();
        $curso->idCurso = $id;
    }
    return $id;
}

function bajaCurso($idCurso) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("DELETE FROM curso WHERE idCurso = :id");
    $stmt->bindParam(':id', $idCurso);
    if ($stmt->execute()) {
        $n = $stmt->rowCount();
        return $n;
    } else {
        print_r($stmt->errorInfo());
        return 0;
    }
}

function actualizaInformacionCurso($curso) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("UPDATE curso SET titulo = :titulo, uniqueUrl = :uniqueUrl,
                             descripcionCorta = :descripcionCorta, descripcion = :descripcion
                            WHERE idCurso = :idCurso");
    $stmt->bindParam(':titulo', $curso->titulo);
    $stmt->bindParam(':uniqueUrl', $curso->uniqueUrl);
    $stmt->bindParam(':descripcionCorta', $curso->descripcionCorta);
    $stmt->bindParam(':descripcion', $curso->descripcion);
    $stmt->bindParam(':idCurso', $curso->idCurso);
    return $stmt->execute();
}

function setPublicarCurso($idCurso, $valor) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("UPDATE curso SET publicado = :valor, fechaPublicacion = NOW()
                            WHERE idCurso = :idCurso");
    $stmt->bindParam(':valor', $valor);
    $stmt->bindParam(':idCurso', $idCurso);
    return $stmt->execute();
}

function getIdUsuarioDeCurso($idCurso) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("SELECT idUsuario FROM curso where idCurso = :id");
    $stmt->bindParam(':id', $idCurso);
    $stmt->execute();
    $row = $stmt->fetch();
    $idUsuario = $row['idUsuario'];
    return $idUsuario;
}

function getUsuarioDeCurso($idCurso) {
    require_once 'bd/conex.php';
    require_once 'modulos/usuarios/clases/Usuario.php';

    global $conex;
    $stmt = $conex->prepare("SELECT u.idUsuario, u.nombreUsuario, u.avatar, u.bio, u.tituloPersonal, u.uniqueUrl, u.email
                            FROM curso c, usuario u
                            WHERE c.idUsuario = u.idUsuario AND c.idCurso = :id");
    $stmt->bindParam(':id', $idCurso);
    $stmt->execute();
    $row = $stmt->fetch();
    $usuario = new Usuario();
    $usuario->idUsuario = $row['idUsuario'];
    $usuario->nombreUsuario = $row['nombreUsuario'];
    $usuario->avatar = $row['avatar'];
    $usuario->bio = $row['bio'];
    $usuario->tituloPersonal = $row['tituloPersonal'];
    $usuario->uniqueUrl = $row['uniqueUrl'];
    $usuario->email = $row['email'];
    return $usuario;
}

function actualizaImagenCurso($curso) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("UPDATE curso SET imagen = :imagen
                            WHERE idCurso = :idCurso");
    $stmt->bindParam(':imagen', $curso->imagen);
    $stmt->bindParam(':idCurso', $curso->idCurso);
    return $stmt->execute();
}

function getCurso($idCurso) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("SELECT * FROM curso where idCurso = :id");
    $stmt->bindParam(':id', $idCurso);

    $stmt->execute();
    $curso = NULL;
    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch();
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

function getRatingCurso($idCurso) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("SELECT rating FROM curso where idCurso = :id");
    $stmt->bindParam(':id', $idCurso);

    $i = 0;
    if($stmt->execute()){
        $row = $stmt->fetch();
        $i = $row['rating'];
    }
    return $i;
}

function getCursoFromUniqueUrl($cursoUrl) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("SELECT * FROM curso where uniqueUrl = :uniqueUrl");
    $stmt->bindParam(':uniqueUrl', $cursoUrl);

    $stmt->execute();
    $curso = NULL;
    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch();
        $curso = new Curso();
        $curso->idCurso = $row['idCurso'];
        $curso->idUsuario = $row['idUsuario'];
        $curso->titulo = $row['titulo'];
        $curso->uniqueUrl = $row['uniqueUrl'];
        $curso->descripcionCorta = $row['descripcionCorta'];
        $curso->descripcion = $row['descripcion'];
        $curso->imagen = $row['imagen'];
        $curso->rating = $row['rating'];
        $curso->publicado = $row['publicado'];
    }
    return $curso;
}

function getCursos($offset, $numRows) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("SELECT SQL_CALC_FOUND_ROWS c.idCurso, c.idUsuario, c.titulo, 
                                c.uniqueUrl, c.imagen, u.nombreUsuario, u.uniqueUrl as uniqueUrlUsuario,
                                count(distinct cl.idClase) as numClases, count(distinct uc.idUsuario) as numAlumnos, 
                                c.descripcionCorta
                            FROM curso c
                            LEFT OUTER JOIN tema t ON c.idCurso = t.idCurso
                            LEFT OUTER JOIN clase cl ON t.idTema = cl.idTema
                            LEFT OUTER JOIN usuariocurso uc ON c.idCurso = uc.idCurso
                            LEFT OUTER JOIN usuario u ON c.idUsuario = u.idUsuario
                            GROUP BY c.idCurso
                            ORDER BY c.titulo ASC
                            LIMIT $offset, $numRows");

    if (!$stmt->execute())
        print_r($stmt->errorInfo());
    $rows = $stmt->fetchAll();

    $r = $conex->query("SELECT FOUND_ROWS() as numero")->fetch();
    $n = $r['numero'];


    $cursos = null;
    $curso = null;
    $i = 0;
    foreach ($rows as $row) {
        $curso = new Curso();
        $curso->idCurso = $row['idCurso'];
        $curso->idUsuario = $row['idUsuario'];
        $curso->titulo = $row['titulo'];
        $curso->uniqueUrl = $row['uniqueUrl'];
        $curso->imagen = $row['imagen'];
        $curso->nombreUsuario = $row['nombreUsuario'];
        $curso->numeroDeClases = $row['numClases'];
        $curso->numeroDeAlumnos = $row['numAlumnos'];
        $curso->descripcionCorta = $row['descripcionCorta'];
        $curso->uniqueUrlUsuario = $row['uniqueUrlUsuario'];
        $cursos[$i] = $curso;
        $i++;
    }
    $array = array(
        "n" => $n,
        "cursos" => $cursos
    );
    return $array;
}

function getAllCursos() {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("SELECT * FROM curso
                            ORDER BY titulo asc");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    $cursos = null;
    $curso = null;
    $i = 0;
    foreach ($rows as $row) {
        $curso = new Curso();
        $curso->idCurso = $row['idCurso'];
        $curso->idUsuario = $row['idUsuario'];
        $curso->titulo = $row['titulo'];
        $curso->uniqueUrl = $row['uniqueUrl'];
        $curso->descripcionCorta = $row['descripcionCorta'];
        $curso->descripcion = $row['descripcion'];
        $curso->totalViews = $row['totalViews'];
        $curso->fechaCreacion = $row['fechaCreacion'];
        $curso->fechaPublicacion = $row['fechaPublicacion'];
        $curso->publicado = $row['publicado'];
        $curso->rating = $row['rating'];
        $curso->imagen = $row['imagen'];
        $cursos[$i] = $curso;
        $i++;
    }
    return $cursos;
}

function getCursosFuncion() {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("SELECT SQL_CALC_FOUND_ROWS c.idCurso, c.idUsuario, c.titulo, 
                                c.uniqueUrl, c.imagen, u.nombreUsuario, u.uniqueUrl as uniqueUrlUsuario,
                                count(distinct cl.idClase) as numClases, count(distinct uc.idUsuario) as numAlumnos, 
                                c.descripcionCorta
                            FROM curso c
                            LEFT OUTER JOIN tema t ON c.idCurso = t.idCurso
                            LEFT OUTER JOIN clase cl ON t.idTema = cl.idTema
                            LEFT OUTER JOIN usuariocurso uc ON c.idCurso = uc.idCurso
                            LEFT OUTER JOIN usuario u ON c.idUsuario = u.idUsuario
                            WHERE c.publicado = 1
                            GROUP BY c.idCurso
                            ORDER BY c.rating DESC");

    if (!$stmt->execute())
        print_r($stmt->errorInfo());
    $rows = $stmt->fetchAll();

    $r = $conex->query("SELECT FOUND_ROWS() as numero")->fetch();
    $n = $r['numero'];


    $cursos = null;
    $curso = null;
    $i = 0;
    foreach ($rows as $row) {
        $curso = new Curso();
        $curso->idCurso = $row['idCurso'];
        $curso->idUsuario = $row['idUsuario'];
        $curso->titulo = $row['titulo'];
        $curso->uniqueUrl = $row['uniqueUrl'];
        $curso->imagen = $row['imagen'];
        $curso->nombreUsuario = $row['nombreUsuario'];
        $curso->numeroDeClases = $row['numClases'];
        $curso->numeroDeAlumnos = $row['numAlumnos'];
        $curso->descripcionCorta = $row['descripcionCorta'];
        $curso->uniqueUrlUsuario = $row['uniqueUrlUsuario'];
        $cursos[$i] = $curso;
        $i++;
    }
    $array = array(
        "n" => $n,
        "cursos" => $cursos
    );
    return $array;
}

function getTemas($idCurso) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("SELECT idTema, idCurso, nombre 
                             FROM tema
                             WHERE idCurso = :idCurso
                             ORDER BY idTema ASC");
    $stmt->bindParam(":idCurso", $idCurso);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    require_once 'modulos/cursos/clases/Tema.php';
    $temas = null;
    $tema = null;
    $i = 0;
    foreach ($rows as $row) {
        $tema = new Tema();
        $tema->idTema = $row['idTema'];
        $tema->idCurso = $row['idCurso'];
        $tema->nombre = $row['nombre'];

        $temas[$i] = $tema;
        $i++;
    }
    return $temas;
}

function getClases($idCurso) {
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
    require_once 'modulos/cursos/clases/Clase.php';
    $clases = null;
    $clase = null;
    $i = 0;
    foreach ($rows as $row) {
        $clase = new Clase();
        $clase->idClase = $row['idClase'];
        $clase->idTema = $row['idTema'];
        $clase->titulo = $row['titulo'];
        $clase->idTipoClase = $row['idTipoClase'];
        $clase->orden = $row['orden'];
        $clase->transformado = $row['transformado'];
        $clase->view = $row['views'];
        $clase->duracion = $row['duracion'];

        $clases[$i] = $clase;
        $i++;
    }
    return $clases;
}

function getNumeroDeAlumnos($idCurso) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("SELECT count(idUsuario) as cuenta
                             FROM usuariocurso
                             WHERE idCurso = :id");
    $stmt->bindParam(":id", $idCurso);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row['cuenta'];
}

function elTituloEsUnico($uniqueUrl) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("SELECT idCurso FROM curso where uniqueUrl = :uniqueUrl");
    $stmt->bindParam(':uniqueUrl', $uniqueUrl);

    $stmt->execute();
    return ($stmt->rowCount() == 0);
}

function sumarTotalView($idCurso) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("UPDATE curso 
                            SET totalViews = totalViews + 1
                            WHERE idCurso = :idCurso");
    $stmt->bindParam(':idCurso', $idCurso);
    return $stmt->execute();
}

function getAlumnosDeCurso($idCurso, $offset, $numRows) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("SELECT SQL_CALC_FOUND_ROWS  u.*
                            FROM usuario u, usuariocurso uc
                            WHERE u.idUsuario = uc.idUsuario
                            AND uc.idCurso = :idCurso
                            ORDER BY u.nombreUsuario ASC
                            LIMIT $offset, $numRows");
    $stmt->bindParam(':idCurso', $idCurso);

    if (!$stmt->execute())
        print_r($stmt->errorInfo());
    $rows = $stmt->fetchAll();

    $r = $conex->query("SELECT FOUND_ROWS() as numero")->fetch();
    $n = $r['numero'];

    $usuario = null;
    $usuarios = null;
    $i = 0;
    foreach ($rows as $row) {
        $usuario = new Usuario();
        $usuario->idUsuario = $row['idUsuario'];
        $usuario->nombreUsuario = $row['nombreUsuario'];
        $usuario->avatar = $row['avatar'];
        $usuario->uniqueUrl = $row['uniqueUrl'];
        $usuarios[$i] = $usuario;
        $i++;
    }
    $array = array(
        "n" => $n,
        "alumnos" => $usuarios
    );
    return $array;
}

function getTodosAlumnosDecurso($idCurso) {
    require_once 'bd/conex.php';
    global $conex;
    $stmt = $conex->prepare("SELECT u.*
                            FROM usuario u, usuariocurso uc
                            WHERE u.idUsuario = uc.idUsuario
                            AND uc.idCurso = :idCurso
                            ORDER BY u.nombreUsuario ASC");
    $stmt->bindParam(':idCurso', $idCurso);

    if (!$stmt->execute())
        print_r($stmt->errorInfo());
    $rows = $stmt->fetchAll();

    $usuario = null;
    $usuarios = null;
    $i = 0;
    foreach ($rows as $row) {
        $usuario = new Usuario();
        $usuario->idUsuario = $row['idUsuario'];
        $usuario->nombreUsuario = $row['nombreUsuario'];
        $usuario->avatar = $row['avatar'];
        $usuario->uniqueUrl = $row['uniqueUrl'];
        $usuarios[$i] = $usuario;
        $i++;
    }
    return $usuarios;
}

?>