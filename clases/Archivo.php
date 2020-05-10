<?php
class Archivo
{
	public static function SubirImagen($usuario, $pathFoto)
	{
		$imagen = file_get_contents($pathFoto);
		$destino = $usuario->GetPathFoto();
		file_put_contents($destino, $imagen);
	}

	//Esta funcion sirve para cuando se reciben imagenes por $_FILES
	public static function Subir($id, $nickname)
	{
		$retorno["Exito"] = TRUE;
		//INDICO CUAL SERA EL DESTINO DEL ARCHIVO SUBIDO
		$tipoArchivo = pathinfo($_FILES["fileFoto"]["name"], PATHINFO_EXTENSION);
		$nombre = "./fotos/".$_FILES["fileFoto"]["name"];
		$destino = "./fotos/".$id."-".$nickname.".".$tipoArchivo;

		//VERIFICO EL TAMAÑO MAXIMO QUE PERMITO SUBIR
		if ($_FILES["fileFoto"]["size"] > 500000) {
			$retorno["Exito"] = FALSE;
			$retorno["Mensaje"] = "El archivo es demasiado grande. Verifique!!!";
			return $retorno;
		}

		//OBTIENE EL TAMAÑO DE UNA IMAGEN, SI EL ARCHIVO NO ES UNA
		//IMAGEN, RETORNA FALSE
		$esImagen = getimagesize($_FILES["fileFoto"]["tmp_name"]);

		if($esImagen === FALSE) {//NO ES UNA IMAGEN
			$retorno["Exito"] = FALSE;
			$retorno["Mensaje"] = "Sólo son permitidas IMAGENES.";
			return $retorno;
		}
		else {// ES UNA IMAGEN
            //VERIFICO SI EXISTE UNA IMAGEN CON ESE NOMBRE
            if(file_exists($nombre)){
                $retorno["Exito"] = FALSE;
                $retorno["Mensaje"] = "Ya existe una imagen con ese nombre.";
                return $retorno;
            }
			//SOLO PERMITO CIERTAS EXTENSIONES
			if($tipoArchivo != "jpg" && $tipoArchivo != "bmp" && $tipoArchivo != "jpeg"
            && $tipoArchivo != "gif" && $tipoArchivo != "png") {
				$retorno["Exito"] = FALSE;
				$retorno["Mensaje"] = "Sólo son permitidas imagenes con extensi&oacute;n JPG, BMP, JPEG, PNG o GIF.";
				return $retorno;
			}
		}
		
		if (!move_uploaded_file($_FILES["fileFoto"]["tmp_name"], $destino)) {

			$retorno["Exito"] = FALSE;
			$retorno["Mensaje"] = "Ocurrio un error al subir el archivo. No pudo guardarse.";
			return $retorno;
		}
		else{
			$retorno["Mensaje"] = "Archivo subido exitosamente!!!"; 
			$retorno["PathTemporal"] = $destino;
			
			return $retorno;
		}
	}
}