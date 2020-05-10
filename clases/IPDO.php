<?php
interface IPDO
{
    static function TraerDeBD();
    function AgregarBD();
    function ModificarBD();
    static function EliminarBD($id);
}