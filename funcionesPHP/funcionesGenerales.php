<?php

function goToIndex() {
    redirect('/');
}

function getUrl() {
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/") . $s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

function getDomainName() {
    return 'http://' . $_SERVER['HTTP_HOST'];
}

function getRequestUri() {
    return $_SERVER['REQUEST_URI'];
}

function getServerRoot() {
    return $_SERVER["DOCUMENT_ROOT"];
}

function redirect($url, $permanent = false) {
    if ($permanent) {
        header('HTTP/1.1 301 Moved Permanently');
    }
    header('Location: ' . $url);
    exit();
}

function strleft($s1, $s2) {
    return substr($s1, 0, strpos($s1, $s2));
}

function tipoUsuario() {
    require_once 'modulos/usuarios/clases/Usuario.php';
    if (isset($_SESSION['usuario'])) {
        $usuario = $_SESSION['usuario'];
        switch ($usuario->tipoUsuario) {
            case 0:
                return 'usuario';
                break;
            case 1:
                return 'administrador';
                break;
            case 2:
                return 'administradorPrivado';
                break;
            case 3:
                return 'profesor';
                break;
        }
    } else {
        return 'visitante';
    }
}

function getTipoUsuarioTexto() {
    require_once 'modulos/usuarios/clases/Usuario.php';
    if (isset($_SESSION['usuario'])) {
        $usuario = $_SESSION['usuario'];
        switch ($usuario->tipoUsuario) {
            case 0:
                return 'Alumno';
                break;
            case 1:
                return 'Administrador de Unova';
                break;
            case 2:
                return 'Administrador';
                break;
            case 3:
                return 'Profesor';
                break;
        }
    } else {
        return 'visitante';
    }
}

function validarUniqueSession() {
    if (isset($_SESSION['usuario'])) {
        require_once 'modulos/principal/modelos/loginModelo.php';
        $sessionId = session_id();
        $idUsuario = $_SESSION['usuario']->idUsuario;
        if (validateSessionIdUsuario($idUsuario, $sessionId)) {
            return true;
        } else {
            //No es una sesión válida, destruimos la sesión actual y las cookies
            //el sessionId ya no es válido para este usuario, destruimos la session            
            $_SESSION['usuario'] = null;
            session_destroy();
            unset($_COOKIE['usrcookiePrv']);
            unset($_COOKIE['clvcookiePrv']);
            setcookie("usrcookiePrv", "logout", 1, '/');
            setcookie("clvcookiePrv", "logout", 1, '/');
            $message = "Alguien utilizó tus datos para iniciar sesión. Te recomendamos iniciar sesión y cambiar tu contraseña.";
            session_start();
            setSessionMessage($message, "¡Error!", "error");
            return false;
        }
    } else {
        //no hay ningún usuario loggeado, entonces es una sesión válida
        return true;
    }
}

function getUsuarioActual() {
    if (isset($_SESSION['usuario'])) {
        //hay un usuario en la sesioń, regresamos eso.
        return $_SESSION['usuario'];
    } else {
        //no hay usuario en session, verificamos si hay cookies guardadas
        if (isset($_COOKIE['usrcookiePrv']) && isset($_COOKIE['clvcookiePrv'])) {
            //hay cookies, tratamos de hacer login
            require_once 'modulos/principal/modelos/loginModelo.php';
            if (loginUsuario($_COOKIE['usrcookiePrv'], $_COOKIE['clvcookiePrv'], false) == 1) {
                //hay buen login
                return $_SESSION['usuario'];
            } else {
                //los datos guardados en las cookies no son correctos. Se borran
                return NULL;
            }
        } else {
            return NULL;
        }
    }
}

function validarUsuarioLoggeado() {
    $usuario = getUsuarioActual();
    if (!isset($usuario)) {
        $pagina = getUrl();
        require_once 'modulos/principal/vistas/login.php';
        return false;
    } else {
        return true;
    }
}

function paginaValidaSinUsuario($accion) {
    if ($accion == "loginSubmit" ||
            $accion == "recuperarPassword" ||
            $accion == "recuperarPasswordSubmit" ||
            $accion == "reestablecerPassword" ||
            $accion == "reestablecerPasswordSubmit" ||
            $accion == "establecerPassword" ||
            $accion == "contacto" ||
            $accion == "actualizarDatosDespuesDeTransformacion")
        return true;
    return false;
}

function validarUsuarioLoggeadoParaSubmits() {
    return isset($_SESSION['usuario']);
}

function validarUsuarioAdministrador() {
    $usuario = getUsuarioActual();
    if (isset($usuario)) {
        if ($usuario->tipoUsuario == 1) {
            return true;
        } else {
            //no tiene los permisos
            return false;
        }
    } else {
        return false;
    }
}

function validarAdministradorPrivado() {
    if (tipoUsuario() == "administradorPrivado")
        return true;
    else
        return false;
}

function comprobar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function transformaDateDDMMAAAA($date) {
    return date("d-m-Y", $date);
}

function transformaMysqlDateDDMMAAAA($date) {
    $fecha = DateTime::createFromFormat('Y-m-d', $date);
    return $fecha->format('d/m/Y');
}

function transformaMysqlDateDDMMAAAAConHora($date) {
    $time = strtotime($date);
    return date('d/m/Y  h:i a', $time);
}

function getUniqueCode($length = 32) {
    $code = md5(uniqid(rand(), true));
    if ($length > 32) {
        while (strlen($code) < $length) {
            $code = $code . md5(uniqid(rand(), true));
        }
    }
    return substr($code, 0, $length);
}

function setSessionMessage($message, $title = "", $type = "") {
    $class = "alert";
    switch ($type) {
        case 'error':
            $class .= " alert-error";
            break;
        case 'success':
            $class .= " alert-success";
            break;
        case 'info':
            $class .= " alert-info";
            break;
    }
    $aux = '<div class="' . $class . ' alert-block">';
    $aux .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
    if (isset($title)) {
        $aux .= '<strong> ' . $title . ' </strong> ';
    }
    $aux .= $message . '</div>';
    $_SESSION['sessionMessage'] = $aux;
}

function getSessionMessage() {
    if (isset($_SESSION['sessionMessage'])) {
        $sessionMessage = $_SESSION['sessionMessage'];
        $_SESSION['sessionMessage'] = NULL;
        unset($_SESSION['sessionMessage']);
        return $sessionMessage;
    }
    else
        return NULL;
}

function removeBadHtmlTags($badHtml) {
    require_once 'lib/php/htmlPurifier/library/HTMLPurifier.auto.php';
    $config = HTMLPurifier_Config::createDefault();
    $config->set('HTML.Doctype', 'XHTML 1.0 Transitional'); // replace with your doctype
    $purifier = new HTMLPurifier($config);
    $pureHtml = $purifier->purify($badHtml);
    return $pureHtml;
}

function bytesToString($bytes, $decimales) {
    if ($bytes < 1024) {
        //mostramos en bytes
        return $bytes . " bytes";
    } else if ($bytes < 1048576) {
        //mostramos en KB
        return round($bytes / 1000, $decimales) . " KB";
    } else if ($bytes < 1073741824) {
        //mostramos en MB
        return round($bytes / 1048576, $decimales) . " MB";
    } else {
        //mostramos en GB
        return round($bytes / 1073741824, $decimales) . " GB";
    }
}

function bytesToDollars($bytes) {
    //Convertir primero a GB y luego multiplicar por 0.15 que es lo que cuesta el gb al mes
    $dollars = round($bytes / 1000000000 * 0.15, 4);
    return $dollars;
}

function transformaMMSStoMinutes($tiempo) {
    list($minutes, $seconds) = explode(":", $tiempo);
    $minutes = $minutes + floor($seconds / 60);
    return $minutes;
}

function guardarTipoLayout() {
    //checamos si es tablet, movil o desktop
    if (!isset($_SESSION['layout'])) {
        require_once 'lib/php/Mobile_Detect/Mobile_Detect.php';
        $detect = new Mobile_Detect();
        $layout = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');
        $_SESSION['layout'] = $layout;
    }
}

function getTipoLayout() {
    return $_SESSION['layout'];
}

function clearBreadCrumbs() {
    unset($_SESSION['breadcrumbs']);
    $_SESSION['breadcrumbs'] = array();
}

function pushBreadCrumb($url, $txt, $lastBreadcrumb = false, $level = -1) {
    $insertValue = array("url" => $url, "txt" => $txt);
    if ($level >= 0) {
        //si establecen un nivel, guardamos en ese nivel y borramos todo lo que sigue
        $auxArray = array_keys($_SESSION['breadcrumbs']);
        //empezamos a borrar desde $level+1
        for ($i = $level; $i < sizeof($auxArray); $i++) {
            unset($_SESSION['breadcrumbs'][$auxArray[$i]]);
        }
        $_SESSION['breadcrumbs'] = array_values($_SESSION['breadcrumbs']);
        $_SESSION['breadcrumbs'][] = $insertValue;
    } else {
        if ($lastBreadcrumb) {
            //hay que borrar todos los breadcrumbs que le sigan a este
            if (isset($_SESSION['breadcrumbs'])) {
                $auxArray = array_keys($_SESSION['breadcrumbs']);
                $indiceBorrar = array_search($insertValue, $_SESSION['breadcrumbs']);
                if ($indiceBorrar !== FALSE) {
                    $borrando = false;
                    for ($i = 0; $i < sizeof($auxArray); $i++) {
                        if ($auxArray[$i] === $indiceBorrar) {
                            $borrando = true;
                        }
                        if ($borrando) {
                            unset($_SESSION['breadcrumbs'][$auxArray[$i]]);
                        }
                    }
                    $_SESSION['breadcrumbs'] = array_values($_SESSION['breadcrumbs']);
                }
            }
            $_SESSION['breadcrumbs'][] = $insertValue;
        } else {
            if (!in_array($insertValue, $_SESSION['breadcrumbs']))
                $_SESSION['breadcrumbs'][] = $insertValue;
        }
    }
}

function getBreadCrumbs() {
    if (isset($_SESSION['breadcrumbs']))
        return $_SESSION['breadcrumbs'];
    else
        return array();
}

?>