<?php

/**
 * Clase para manejo de usuarios
 *
 * @author anyul
 */
class usuario extends db implements crud {

    const tabla = "usuario";

    public function insertar($data) {
        return $this->insert(self::tabla, $data);
    }

    public function actualizar($id, $data) {
        return $this->update(self::tabla, $data, array("id" => $id));
    }

    public function borrar($id) {
        return $this->delete(self::tabla, $id);
    }

    /**
     * funcion que retorna una matriz de datos de un usuario determinado
     * @param int $id id del usuario
     * @return array arreglo de datos
     */
    public function ver($id) {
        return $result = $this->dame_query("
                select * 
                from usuario 
                where usuario.id=$id");
    }

    /**
     * funcion que retorna un arreglo de usuarios
     * @return Array
     */
    public function listar() {
        return $this->dame_query("select id, nombre from " . self::tabla);
    }

    /**
     * Gestiona el inicio de sesión en el sistema
     * @param String $usuario
     * @param String $password
     * @return boolean 
     */
    public function login($usuario, $password, $empresa) {
        $result = array();
        try {
            $result = $this->dame_query("select * from usuario where login='$usuario' and password='$password'");
            if ($result['suceed'] == 'true' && count($result['data']) > 0) {
                session_start();
                $_SESSION['usuario'] = $result['data'][0];
                $_SESSION['status'] = 'logueado';
                header("location:" . URL_SISTEMA . "/usuario/");
                return $result;
            } else {
                $result['suceed'] = false;
                $result['error'] = "Login y/o clave inválidos";
                return $result;
            }
        } catch (Exception $exc) {
            trigger_error($exc->getTraceAsString(), E_USER_NOTICE);
            $result['suceed'] = false;
            $result['error'] = "Error desconocido. Contacte al administrador del sistema.";
            return $result;
        }
    }

    /**
     * Confirma que el usuario sea haya logueado en el sistema 
     */
    public function confirmar_miembro() {
        session_start();
        if (!isset($_SESSION['status']) || $_SESSION['status'] != 'logueado' || !isset($_SESSION['usuario']))
            header("location:" . ROOT . "/login.php");
    }

    /**
     * Cierra la sesión 
     */
    public function logout() {
        session_start();
        if (isset($_SESSION['status'])) {
            unset($_SESSION['status']);
            unset($_SESSION['usuario']);
            session_unset();
            session_destroy();

            if (isset($_COOKIE[session_name()]))
                setcookie(session_name(), '', time() - 1000);
            header("location:" . ROOT . "/login.php");
        }
    }

}

?>
