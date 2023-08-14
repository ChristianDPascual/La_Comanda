<?php

class ControlOrden extends Ordenes implements InterfaceApiUsable
{

    public static function CrearUno($request, $response, $args)
    {
        $modo = token :: decodificarToken($request);

        if($modo == "admin" || $modo == "mozo")
        {
            $parametros = $request->getParsedBody();
            $idServicio = $parametros["idServicio"];
            $cantidad = $parametros["cantidad"];
            $estado = $parametros["estado"];
            $idProducto = $parametros["idProducto"];
            $fecha = $parametros["fecha"];
            $tiempoRestante = $parametros["tiempoRestante"];

            if(validarCadena($idServicio) && validarNumero($cantidad) && validarNumero($idProducto)
               validarCadena($estado))
            {
                try
                {
                    $conStr = "mysql:host=localhost;dbname=admin_comanda";
                    $user ="yo";
                    $pass ="cp35371754";
                    $pdo = new PDO($conStr,$user,$pass);
    
                    $sentencia = $pdo->prepare("INSERT INTO ordenes (idServicio,cantidad,estado,
                                                                   idProducto,fecha,tiempoRestante) 
                                                    VALUES (:idServicio,:cantidad,:estado,
                                                            :idProducto,:fecha,:tiempoRestante)");
                    $sentencia->bindValue(':idServicio', $idServicio);
                    $sentencia->bindValue(':cantidad', $cantidad);
                    $sentencia->bindValue(':estado', $estado);
                    $sentencia->bindValue(':idProducto', $idProducto);
                    $sentencia->bindValue(':fecha', $fecha);
                    $sentencia->bindValue(':tiempoRestante', $tiempoRestante);

                    if($sentencia->execute())
                    {
                        $payload = json_encode(array("mensaje"=>"orden creada exitosamente"));
                        $response->getBody()->write($payload);
                    }
                }
                catch(PDOException $e)
                {
                    $pdo = null;
                    $payload = json_encode(array("mensaje"=>"Error al realizar la coneccion con la base de datos\n"));
                    $response->getBody()->write($payload);
                    echo "Error: " .$e->getMessage();
                    return $response->withHeader('Content-Type', 'application/json');
                }
            }
            else
            {
                $payload = json_encode(array("mensaje"=>"Error, faltan ingresar campos"));
                $response->getBody()->write($payload);
            }

        }
        else
        {
            $payload = json_encode(array("mensaje"=>"usuario no valido"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function MostrarUno($request, $response, $args)
    {
        $modo = token :: decodificarToken($request);

        if($modo == "admin")
        {
            $parametros = $request->getParsedBody();
            $idServicio = $parametros["idServicio"];


            if(validarCadena($idServicio))
            {
                try
                {
                    $conStr = "mysql:host=localhost;dbname=admin_comanda";
                    $user ="yo";
                    $pass ="cp35371754";
                    $pdo = new PDO($conStr,$user,$pass);
    
                    $sentencia = $pdo->prepare("SELECT FROM ordenes WHERE idServicio = :idServicio");
                    $sentencia->bindValue(':idServicio', $idServicio);


                    if($sentencia->execute())
                    {
                        $resultado = $sentencia->fetchALL(PDO :: FETCH_ASSOC);
                        
                        if(!empty($resultado))
                        {
                            $contador = 0;
                            foreach($resultado as $orden)
                            {
                                $contador++;
                                $is = $orden["idServicio"];
                                $ip = $orden["idProducto"];
                                $c = $orden["cantidad"];
                                $e = $orden["estado"];
                                $f = $orden["fecha"];
                                $t = $orden["tiempoRestante"];

                                echo ("id servicio  $is id producto $ip cantidad $c estado $e 
                                tiempo restante $t fecha de pedido $f");
                            }
                            
                            $payload = json_encode(array("mensaje"=>"cantidad de pedidos $contador"));
                            $response->getBody()->write($payload);
                        }
                        else
                        {
                            $payload = json_encode(array("mensaje"=>"No se encontro un empleado con ese DNI"));
                            $response->getBody()->write($payload);
                        }
                    }
                }
                catch(PDOException $e)
                {
                    $pdo = null;
                    $payload = json_encode(array("mensaje"=>"Error al realizar la coneccion con la base de datos\n"));
                    $response->getBody()->write($payload);
                    echo "Error: " .$e->getMessage();
                    return $response->withHeader('Content-Type', 'application/json');
                }
            }
            else
            {
                $payload = json_encode(array("mensaje"=>"Error, al ingresar el DNI"));
                $response->getBody()->write($payload);
            }

        }
        else
        {
            $payload = json_encode(array("mensaje"=>"usuario no valido"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function MostrarTodos($request, $response, $args)
    {
        $modo = token :: decodificarToken($request);

        if($modo == "admin" || $modo == "mozo")
        {
            try
            {
                $conStr = "mysql:host=localhost;dbname=admin_comanda";
                $user ="yo";
                $pass ="cp35371754";
                $pdo = new PDO($conStr,$user,$pass);
    
                $sentencia = $pdo->prepare("SELECT FROM ordenes");

                if($sentencia->execute())
                {
                    $resultado = $sentencia->fetchAll(PDO :: FETCH_ASSOC);

                    if(!empty($resultado))
                    {
                        $contador = 0;
                        foreach($resultado as $orden)
                        {
                            $contador++;
                            $is = $orden["idServicio"];
                            $ip = $orden["idProducto"];
                            $c = $orden["cantidad"];
                            $e = $orden["estado"];
                            $f = $orden["fecha"];
                            $t = $orden["tiempoRestante"];

                            echo ("id servicio  $is id producto $ip cantidad $c estado $e 
                            tiempo restante $t fecha de pedido $f");
                        }
                        
                        $payload = json_encode(array("mensaje"=>"cantidad de pedidos $contador"));
                        $response->getBody()->write($payload);
                    }
                    else
                    {
                        $payload = json_encode(array("mensaje"=>"No se encontraron empleados"));
                        $response->getBody()->write($payload);
                    }
                }
            }
            catch(PDOException $e)
            {
                $pdo = null;
                $payload = json_encode(array("mensaje"=>"Error al realizar la coneccion con la base de datos\n"));
                $response->getBody()->write($payload);
                echo "Error: " .$e->getMessage();
                return $response->withHeader('Content-Type', 'application/json');
            }
        }
        else
        {
            $payload = json_encode(array("mensaje"=>"usuario no valido"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ModificarUno($request, $response, $args)
    {
        $modo = token :: decodificarToken($request);

        if($modo == "admin" || $modo == "mozo")
        {
            $parametros = $request->getParsedBody();
            $idServicio = $parametros["idServicio"];
            $idProducto = $parametros["idProducto"];
            $cantidad = $parametros["cantidad"];
            $estado = $parametros["estado"];


            if(validarCadena($estado))
            {
                try
                {
                    $conStr = "mysql:host=localhost;dbname=admin_comanda";
                    $user ="yo";
                    $pass ="cp35371754";
                    $pdo = new PDO($conStr,$user,$pass);
    
                    $sentencia = $pdo->prepare("UPDATE ordenes SET estado = :estado
                                                WHERE idProducto = :idProducto 
                                                AND idServicio = :idServicio
                                                AND cantidad = :cantidad");
                    $sentencia->bindValue(':idServicio', $idServicio);
                    $sentencia->bindValue(':idProducto', $idProducto);
                    $sentencia->bindValue(':cantidad', $cantidad);
                    $sentencia->bindValue(':estado', $estado);


                    if($sentencia->execute())
                    {
                        $payload = json_encode(array("mensaje"=>"orden modificada exitosamente"));
                        $response->getBody()->write($payload);
                    }
                }
                catch(PDOException $e)
                {
                    $pdo = null;
                    $payload = json_encode(array("mensaje"=>"Error al realizar la coneccion con la base de datos\n"));
                    $response->getBody()->write($payload);
                    echo "Error: " .$e->getMessage();
                    return $response->withHeader('Content-Type', 'application/json');
                }
            }
            else
            {
                $payload = json_encode(array("mensaje"=>"Error, faltan campos"));
                $response->getBody()->write($payload);
            }

        }
        else
        {
            $payload = json_encode(array("mensaje"=>"usuario no valido"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function EliminarUno($request, $response, $args)
    {
        $modo = token :: decodificarToken($request);

        if($modo == "admin" || $modo == "mozo")
        {
            $parametros = $request->getParsedBody();
            $idServicio = $parametros["idServicio"];
            $idProducto = $parametros["idProducto"];
            $cantidad = $parametros["cantidad"];


            if(validarDNI($dni))
            {
                try
                {
                    $conStr = "mysql:host=localhost;dbname=admin_comanda";
                    $user ="yo";
                    $pass ="cp35371754";
                    $pdo = new PDO($conStr,$user,$pass);
    
                    $sentencia = $pdo->prepare("DELETE FROM ordenes
                                                WHERE idProducto = :idProducto 
                                                AND idServicio = :idServicio
                                                AND cantidad = :cantidad");
                    $sentencia->bindValue(':idServicio', $idServicio);
                    $sentencia->bindValue(':idProducto', $idProducto);
                    $sentencia->bindValue(':cantidad', $cantidad);


                    if($sentencia->execute())
                    {

                        $payload = json_encode(array("mensaje"=>"orden fue borrada con exito"));
                        $response->getBody()->write($payload);

                    }
                }
                catch(PDOException $e)
                {
                    $pdo = null;
                    $payload = json_encode(array("mensaje"=>"Error al realizar la coneccion con la base de datos\n"));
                    $response->getBody()->write($payload);
                    echo "Error: " .$e->getMessage();
                    return $response->withHeader('Content-Type', 'application/json');
                }
            }
            else
            {
                $payload = json_encode(array("mensaje"=>"Error, al ingresar el DNI"));
                $response->getBody()->write($payload);
            }

        }
        else
        {
            $payload = json_encode(array("mensaje"=>"usuario no valido"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

}

?>