<?php

interface InterfaceApiUsable
{
    public static function CrearUno($request, $response, $args);
    public static function MostrarUno($request, $response, $args);
    public static function MostrarTodos($request, $response, $args);
    public static function ModificarUno($request, $response, $args);
    public static function EliminarUno($request, $response, $args);
    public static function CambiarEstado($request, $response, $args);
}

?>