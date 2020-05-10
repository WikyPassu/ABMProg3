<?php
include "AccesoBD.php";
include "IArchivo.php";
include "IPDO.php";

class Usuario implements IArchivo, IPDO
{
    private $_id;
    private $_nickname;
    private $_pass;
    private $_pathFoto;

    public function __construct($id, $nickname, $pass, $pathFoto = "")
    {
        $this->_id = $id;
        $this->_nickname = $nickname;
        $this->_pass = $pass;
        $this->_pathFoto = $pathFoto;
    }

    public function GetID()
    {
        return $this->_id;
    }

    public function GetNickname()
    {
        return $this->_nickname;
    }

    public function GetPass()
    {
        return $this->_pass;
    }

    public function GetPathFoto()
    {
        return $this->_pathFoto;
    }

    public function SetPathFoto($pathFoto)
    {
		$ext = pathinfo($pathFoto, PATHINFO_EXTENSION);
		$path = "./fotos/".$this->GetID()."-".$this->GetNickname().".".$ext;
        $this->_pathFoto = $path;
    }

    public function ToString()
    {
        return $this->GetID()." - ".$this->GetNickname()." - ".$this->GetPass()." - ".$this->GetPathFoto();
    }

    //Funciones referidas a archivos

    static function GuardarEnArchivo($nombreArchivo, $lista)
    {
        $ruta = "./archivos/".$nombreArchivo.".txt";
        $archivo = fopen($ruta, "w");
        
        foreach($lista as $usuario){
            fwrite($archivo, $usuario->ToString()."\r\n");
        }
        
        fclose($archivo);
    }

    static function TraerDeArchivo($nombreArchivo)
    {
        $lista = array();
        $ruta = "./archivos/".$nombreArchivo.".txt";
        $archivo = fopen($ruta, "r");

        while(!feof($archivo))
        {
            $lineaStr = fgets($archivo);
            $lineaArray = explode(" - ", $lineaStr);
            $lineaArray[0] = trim($lineaArray[0]);

            if($lineaArray[0] != "")
            {
                $lineaArray[3] = trim($lineaArray[3]);
                $usuario = new Usuario($lineaArray[0], $lineaArray[1], $lineaArray[2], $lineaArray[3]);
                array_push($lista, $usuario);
            }
        }

        fclose($archivo);

        return $lista;
    }

    //Funciones referidas a base de datos
    static function TraerDeBD()
    {
        $objAccesoBD = AccesoBD::ObtenerAcceso();
        $consulta = $objAccesoBD->RetornarConsulta("SELECT * FROM usuarios");
        $consulta->execute();

        $lista = array();
        $resultado = $consulta->fetchAll();
        foreach($resultado as $fila)
        {
            $usuario = new Usuario($fila['id'], $fila['nickname'], $fila['pass'], $fila['pathFoto']);
            array_push($lista, $usuario);
        }

        return $lista;
    }

    public function AgregarBD()
    {
        $objAccesoBD = AccesoBD::ObtenerAcceso();
        $consulta = $objAccesoBD->RetornarConsulta("INSERT INTO usuarios VALUES(:id, :nickname, :pass, :pathFoto)");
        
        $consulta->binDValue(':id', $this->GetID(), PDO::PARAM_INT);
        $consulta->binDValue(':nickname', $this->GetNickname(), PDO::PARAM_STR);
        $consulta->binDValue(':pass', $this->GetPass(), PDO::PARAM_STR);
        $consulta->binDValue(':pathFoto', $this->GetPathFoto(), PDO::PARAM_STR);
        
        return $consulta->execute();
    }

    public function ModificarBD()
    {
        $retorno = FALSE;
        $usuarios = Usuario::TraerDeBD();

        foreach($usuarios as $usuario)
        {
            if($usuario->GetID() == $this->GetID())
            {
                $objAccesoBD = AccesoBD::ObtenerAcceso();
                $consulta = $objAccesoBD->RetornarConsulta("UPDATE usuarios SET nickname = :nickname, pass = :pass, pathFoto = :pathFoto
                                                            WHERE id = :id");
                
                $consulta->binDValue(':id', $this->GetID(), PDO::PARAM_INT);
                $consulta->binDValue(':nickname', $this->GetNickname(), PDO::PARAM_STR);
                $consulta->binDValue(':pass', $this->GetPass(), PDO::PARAM_STR);
                $consulta->binDValue(':pathFoto', $this->GetPathFoto(), PDO::PARAM_STR);
                
                $retorno = $consulta->execute();
                break;                
            }
        }

        return $retorno;
    }

    static function EliminarBD($id)
    {
        $objAccesoBD = AccesoBD::ObtenerAcceso();
        $consulta = $objAccesoBD->RetornarConsulta("DELETE FROM usuarios WHERE id = :id");
 
        $consulta->binDValue(':id', $id, PDO::PARAM_INT);
        
        return $consulta->execute();
    }
}