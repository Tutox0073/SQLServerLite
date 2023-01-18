<?php

namespace Rmunate\SqlServerLite;

/**
 * Clase para conexion con SQL Server
 * --------------------------------------------
 * Desarrollado por: Raul Mauricio Uñate Castro
 * raulmauriciounate@gmail.com
 * V 2.0.0
 */

use PDO;
use Exception;
use Throwable;
use PDOException;

class SQLServer {

    #--------------------------╔═════════════════════════════════╗--------------------------#
    #--------------------------║      ATRIBUTOS DEL OBJETO       ║--------------------------#
    #--------------------------╚═════════════════════════════════╝--------------------------#

    /* Atributos Para Heredar */
    protected $status_connection;
    protected $server_name;
    protected $user_database;
    protected $password_database;
    protected $sqlsrv_attr_query_timeout;
    protected $PDO;
    protected $error;

    /* Atributos Privados */
    private $response;
    private $nocount = false;
    private $nocheck = false;
    private $check = false;

    /* Datos al inicial la clase */
    private static $server;
    private static $instance;
    private static $database;
    private static $user;
    private static $password;
    private static $timeout;
    private static $use_expecific_instance = true;

    #--------------------------╔═════════════════════════════════╗--------------------------#
    #--------------------------║      CONSTRUCTOR DE LA CLASE    ║--------------------------#
    #--------------------------╚═════════════════════════════════╝--------------------------#

    public function __construct(){

        /* Valores de Conexion a la Base de Datos */
        if(Self::$use_expecific_instance){
            $this->server_name = Self::$server . chr(92) . Self::$instance . chr(59) . Self::$database;
        } else {
            $this->server_name = Self::$server . chr(59) . Self::$database;
        }
        $this->user_database = Self::$user;
        $this->password_database = Self::$password;
        $this->sqlsrv_attr_query_timeout = !empty(Self::$timeout) ? Self::$timeout :0;

        /* Creacion de la Conexion */
        try {

            $conn = new PDO($this->server_name, $this->user_database, $this->password_database);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, $this->sqlsrv_attr_query_timeout);
            $this->PDO = $conn;
            $this->status_connection = true;
            $this->error = null;

        } catch (PDOException $e) {

            $this->PDO = null;
            $this->status_connection = false;

            /* Retorno de Errores */
            if(is_array($e->errorInfo)){
                $erroresImplode =  implode(' | ',$e->errorInfo);
                $erroresImplode .= "Garantice que se encuentre instalado el ODBC Driver Correctamente.";
                $this->error = strtoupper($erroresImplode);
            } else {
                $this->error = $e->errorInfo;
            }

        }
    }

    #--------------------------╔═════════════════════════════════╗--------------------------#
    #--------------------------║         INICIALIZACION          ║--------------------------#
    #--------------------------╚═════════════════════════════════╝--------------------------#

    public static function database(array $arrayCredentials){

        /* Validar que todos los datos vengan en la clase */
        if (!isset($arrayCredentials['server'])) {
            throw new Exception('No se encuentra el index [server] en el arreglo ingresado en el metodo ::database');
        } else if (!isset($arrayCredentials['database'])){
            throw new Exception('No se encuentra el index [database] en el arreglo ingresado en el metodo ::database');
        } else if (!isset($arrayCredentials['user'])){
            throw new Exception('No se encuentra el index [user] en el arreglo ingresado en el metodo ::database');
        } else if (!isset($arrayCredentials['password'])){
            throw new Exception('No se encuentra el index [password] en el arreglo ingresado en el metodo ::database');
        } else {
            /* Creacion de Datos en Valores Estaticos */
            Self::$server = 'sqlsrv:Server=' . trim($arrayCredentials['server']);
            Self::$database = 'Database=' . trim($arrayCredentials['database']);
            Self::$user = trim($arrayCredentials['user']);
            Self::$password = trim($arrayCredentials['password']);
            Self::$timeout = 0; /* Sin Tiempo Definido Para Detener El Script*/

            /* Definir Uso de Instancia Especifica */
            if(isset($arrayCredentials['instance']) && $arrayCredentials['instance'] != ''){
                Self::$use_expecific_instance = true;
                Self::$instance = trim($arrayCredentials['instance']);

            } else {
                Self::$use_expecific_instance = false;
                Self::$instance = '';
            }
            return new static();
        }
    }

    #--------------------------╔═════════════════════════════════╗--------------------------#
    #--------------------------║            CONSULTAS            ║--------------------------#
    #--------------------------╚═════════════════════════════════╝--------------------------#

    /* ▼ SET NOCOUNT ON ▼ */
    public function noCount(){
        $this->nocount = "SET NOCOUNT ON;";
        return $this;
    }

    /* Deshabilitar llaves  */
    public function noCheck(array $tablas){
        if (is_array($tablas) && count($tablas) > 0) {
            $this->nocheck = '';
            foreach ($tablas as $key => $tabla) {
                $this->nocheck .= "ALTER TABLE $tabla NOCHECK CONSTRAINT ALL;";
            }
        }
        return $this;
    }

    /* Habilitar Llaves */
    public function check(string $tabla){
        if (is_array($tablas) && count($tablas) > 0) {
            $this->check = '';
            foreach ($tablas as $key => $tabla) {
                $this->check .= "ALTER TABLE $tabla CHECK CONSTRAINT ALL;";
            }
        }
        return $this;
    }

    /* ▼ PROCEDURE ▼ */
    public function procedure(string $statement, $return = true){
        if ($return) {
            if ($this->status_connection) {
                $conn = $this->PDO;
                $query = strval($statement);
                /* Evitar Errores Con OpenQuery */
                if ($this->nocount) {
                    $query = $this->nocount . $query;
                }
                /* Deshabilitar Foraneas */
                if ($this->nocheck) {
                    $query = $this->nocheck . $query;
                }
                /* Habilitar Foraneas */
                if ($this->check) {
                    $query = $this->check . $query;
                }
                $stmt = $conn->query($query);
                $rows = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    array_push($rows, $row);
                }
                $this->response = $rows;
                return $this;
            } else {
                throw new Exception('No se logro conectar al servidor: ' . $this->server_name . ', error: ' . $this->error);
            }
        } else {
            if ($this->status_connection) {
                $conn = $this->PDO;
                $query = strval($statement);
                try {
                    $stmt = $conn->exec($query);
                    return true;
                } catch (\Throwable $th) {
                    return $th->getMessage();
                }
            } else {
                throw new Exception('No se logro conectar al servidor: ' . $this->server_name . ', error: ' . $this->error);
            }
        }
    }

    /* ▼ SELECT ▼ */
    public function select(string $statement){
        if ($this->status_connection) {
            $conn = $this->PDO;
            $query = strval($statement);
            /* Evitar Errores Con OpenQuery */
            if ($this->nocount) {
                $query = $this->nocount . $query;
            }
            $stmt = $conn->query($query);
            $rows = [];

            /* Creacion de Arreglo Asociativo */
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                array_push($rows, $row);
            }
            $this->response = $rows;
            return $this;
        } else {
            throw new Exception('No se logro conectar al servidor: ' . $this->server_name . ', error: ' . $this->error);
        }
    }

    /*----- ▼ RETORNO DE RESULTADO ▼ -----*/

    /* Retorno de todas las respuestas en array */
    public function get(){
        if (count($this->response) >= 1) {
            return $this->response;
        } else {
            return $response = [];
        }
    }

    /* Retornar la primer llave */
    public function first(){
        if (count($this->response) >= 1) {
            return $this->response[array_key_first($this->response)];
        } else {
            return $response = [];
        }
    }

    /* Retornar la ultima llave */
    public function last(){
        if (count($this->response) >= 1) {
            return $this->response[array_key_last($this->response)];
        } else {
            return $response = [];
        }
    }

    /* Retornar la cuenta */
    public function count(){
        return count($this->response);
    }

    /* Retornar la consulta de forma inversa */
    public function reverse(){
        if (count($this->response) >= 1) {
            return array_reverse($this->response);
        } else {
            return $response = [];
        }
    }

    /* Retornar en coleccion de Laravel */
    public function collect(){
        if (count($this->response) >= 1) {
            return collect($this->response);
        } else {
            return $response = collect([]);
        }
    }

#--------------------------╔═════════════════════════════════╗--------------------------#
#--------------------------║            SENTENCIAS           ║--------------------------#
#--------------------------╚═════════════════════════════════╝--------------------------#

    /* ▼ UPDATE ▼ */
    public function update(string $statement){
        if ($this->status_connection) {
            $conn = $this->PDO;
            $query = strval($statement);
            /* Deshabilitar Foraneas */
            if ($this->nocheck) {
                $query = $this->nocheck . $query;
            }
            /* Habilitar Foraneas */
            if ($this->check) {
                $query = $this->check . $query;
            }
            $conn->beginTransaction();
            try {
                $stmt = $conn->exec($query);
                $conn->commit();
                return true;
            } catch (\Throwable $th) {
                $conn->rollback();
                return $th->getMessage();
            }
        } else {
            throw new Exception('No se logro conectar al servidor: ' . $this->server_name . ', error: ' . $this->error);
        }
    }

    /* ▼ INSERT ▼ */
    public function insert(string $statement){
        if ($this->status_connection) {
            $conn = $this->PDO;
            $query = strval($statement);
            /* Deshabilitar Foraneas */
            if ($this->nocheck) {
                $query = $this->nocheck . $query;
            }
            /* Habilitar Foraneas */
            if ($this->check) {
                $query = $this->check . $query;
            }
            $conn->beginTransaction();
            try {
                $stmt = $conn->exec($query);
                $conn->commit();
                return true;
            } catch (\Throwable $th) {
                $conn->rollback();
                return $th->getMessage();
            }
        } else {
            throw new Exception('No se logro conectar al servidor: ' . $this->server_name . ', error: ' . $this->error);
        }
    }

    /* ▼ DELETE ▼ */
    public function delete(string $statement){
        if ($this->status_connection) {
            $conn = $this->PDO;
            $query = strval($statement);
            /* Deshabilitar Foraneas */
            if ($this->nocheck) {
                $query = $this->nocheck . $query;
            }
            /* Habilitar Foraneas */
            if ($this->check) {
                $query = $this->check . $query;
            }
            $conn->beginTransaction();
            try {
                $stmt = $conn->exec($query);
                $conn->commit();
                return true;
            } catch (\Throwable $th) {
                $conn->rollback();
                return $th->getMessage();
            }
        } else {
            throw new Exception('No se logro conectar al servidor: ' . $this->server_name . ', error: ' . $this->error);
        }
    }
}

?>
