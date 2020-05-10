<?php
include_once ("./clases/Usuario.php");
include_once ("./clases/Archivo.php");

$opcion = isset($_POST['opcion']) ? $_POST['opcion'] : NULL;
$id = isset($_POST['id']) ? $_POST['id'] : "";
$nickname = isset($_POST['nickname']) ? $_POST['nickname'] : "";
$pass = isset($_POST['pass']) ? $_POST['pass'] : "";
$pathFoto = isset($_POST['pathFoto']) ? $_POST['pathFoto'] : "";

$opcion = "listar";
$id = 3;
$nickname = "tommaister";
$pass = "tomaister123";
$pathFoto = "https://i.imgflip.com/3lvsd8.jpg";

switch($opcion)
{
    case "listar":
        $usuarios = Usuario::TraerDeBD();
        
        echo "Traigo desde Base de datos";
        echo    "<table border='solid'>
                    <thead>
                        <th>ID</th>
                        <th>Nickname</th>
                        <th>Pass</th>
                        <th>PathFoto</th>
                        <th>Foto</th>
                    </thead>";
        foreach($usuarios as $usuario)
        {
            echo    "<tr>
                        <td align='center'>".$usuario->GetID()."</td>
                        <td align='center'>".$usuario->GetNickname()."</td>
                        <td align='center'>".$usuario->GetPass()."</td>
                        <td align='center'>".$usuario->GetPathFoto()."</td>
                        <td align='center'>
                            <img src='".$usuario->GetPathFoto()."' width='90px' height='90px'>
                        </td>
                    </tr>";
        }
        echo    "</table>";
        /////////////////////////////////////////////////////
        $usuarios = Usuario::TraerDeArchivo("usuarios");
        
        echo "<br/>Traigo desde Archivo";
        echo    "<table border='solid'>
                    <thead>
                        <th>ID</th>
                        <th>Nickname</th>
                        <th>Pass</th>
                        <th>PathFoto</th>
                        <th>Foto</th>
                    </thead>";
        foreach($usuarios as $usuario)
        {
            echo    "<tr>
                        <td align='center'>".$usuario->GetID()."</td>
                        <td align='center'>".$usuario->GetNickname()."</td>
                        <td align='center'>".$usuario->GetPass()."</td>
                        <td align='center'>".$usuario->GetPathFoto()."</td>
                        <td align='center'>
                            <img src='".$usuario->GetPathFoto()."' width='90px' height='90px'>
                        </td>
                    </tr>";
        }
        echo    "</table>";
        break;
    case "agregar":
        if($id !== "" && $nickname !== "" && $pass !== "")
        {
            $usuario = new Usuario($id, $nickname, $pass);
            $usuario->SetPathFoto($pathFoto);
            if($usuario->AgregarBD())
            {
                Archivo::SubirImagen($usuario, $pathFoto);
                echo "Usuario agregado correctamente!";
                $usuarios = Usuario::TraerDeBD();
                Usuario::GuardarEnArchivo("usuarios", $usuarios);
            }
        }
        break;
    case "modificar":
        if($id !== "" && $nickname !== "" && $pass !== "")
        {
            $usuario = new Usuario($id, $nickname, $pass);
            $usuario->SetPathFoto($pathFoto);
            if($usuario->ModificarBD())
            {
                Archivo::SubirImagen($usuario, $pathFoto);
                echo "Usuario modificado correctamente!";
                $usuarios = Usuario::TraerDeBD();
                Usuario::GuardarEnArchivo("usuarios", $usuarios);
            }
        }
        break;
    case "eliminar":
        if($id !== "")
        {
            if(Usuario::EliminarBD($id))
            {
                echo "Usuario eliminado correctamente!";
                $usuarios = Usuario::TraerDeBD();
                foreach($usuarios as $usuario)
                {
                    if($usuario->GetID() == $id)
                    {
                        unlink($usuario->GetPathFoto());
                        break;
                    }
                }
                Usuario::GuardarEnArchivo("usuarios", $usuarios);
            }
        }
        break;
    default:
        echo ":(";
        break;
}