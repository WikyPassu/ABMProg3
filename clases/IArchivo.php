<?php
interface IArchivo
{
    static function GuardarEnArchivo($nombreArchivo, $lista);
    static function TraerDeArchivo($nombreArchivo);
}