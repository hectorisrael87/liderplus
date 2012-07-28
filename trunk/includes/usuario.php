<?php

/**
 * Clase para manejo de usuarios
 *
 * @author anyul
 */
class usuario extends db implements crud {

    const tabla = "usuarios";

    public function insertar($data) {
        return $this->insert(self::tabla, $data);
    }

    public function actualizar($id, $data) {
        return $this->update(self::tabla, $data, array("id" => $id));
    }

    public function borrar($id) {
        return $this->delete(self::tabla, array("id" => $id));
    }

    /**
     * funcion que retorna una matriz de datos de un usuario determinado
     * @param int $id id del usuario
     * @return array arreglo de datos
     */
    public function ver($id) {
        $result = $this->dame_query("select usuarios.*, grupo.descripcion grupo from " . self::tabla .
        " inner join grupo on grupo.id = usuarios.grupo_id where usuarios.id = " . $id);
        return $result;
    }

    /**
     * funcion que retorna un arreglo de usuarios
     * @return Array
     */
    public function listar() {
        return $this->dame_query("select id, nombre, grupo.descripcion grupo from " . self::tabla. " inner join grupo on grupo.id = usuarios.grupo_id ");
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
            $result = $this->dame_query("select ".self::tabla.".*, grupo.id grupo_id, grupo.descripcion grupo from " . self::tabla . " 
                inner join grupo on grupo.id = ".self::tabla.".grupo_id 
                    where login='$usuario' and password='$password'");
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
            header("location:" . ROOT . "login.php");
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
            header("location:" . ROOT . "login.php");
        }
    }

    public function listar_grupos() {
        return $this->select("*", "grupo");
    }
    public function listar_fases_usuario($usuario_id){
        return $this->dame_query("select fase.* from fase 
    inner join fase_grupo on fase.id = fase_grupo.fase_id
    inner join grupo on grupo.id = fase_grupo.grupo_id
    inner join usuarios on usuarios.grupo_id = grupo.id
    where usuarios.id = $usuario_id order by fase.nombre");
    }
}

?>