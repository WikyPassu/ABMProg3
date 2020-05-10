<?php
class AccesoBD
{
    private static $_objAccesoBD;
    private $_objPDO;

    public function __construct()
    {
        try
        {
            $host = "localhost";
            $usuario = "root";
            $clave = "";
            $bdNombre = "mibase";

            $this->_objPDO = new PDO("mysql:host=$host;dbname=$bdNombre;charset=utf8", $usuario, $clave);
        }
        catch(PDOException $error)
        {
            print "Error!!!<br/>".$error->getMessage();
            die();
        }
    }

    public function RetornarConsulta($sql)
    {
        return $this->_objPDO->prepare($sql);
    }

    //Singleton
    public static function ObtenerAcceso()
    {
        if (!isset(self::$_objAccesoBD)) {       
            self::$_objAccesoBD = new AccesoBD(); 
        }
 
        return self::$_objAccesoBD;        
    }

    //Evita que el objeto se pueda clonar
    public function __clone()
    {
        trigger_error('La clonación de este objeto no está permitida!!!', E_USER_ERROR);
    }
}