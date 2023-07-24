<?php

class ControlMesa extends Mesa implements InterfaceApiUsable
{

    public static function CrearUno($request, $response, $args)
    {
        $modo = token :: decodificarToken($request);

        if($modo == "admin" || $modo == "mozo")
        {
            $parametros = $request->getParsedBody();
            $idServicio = $parametros["idServicio"];
            $estado = $parametros["estado"];
            $foto = $parametros["foto"];
            $fecha = $parametros["fecha"];
            $dniCliente = $parametros["dniCliente"];
            $nroMesa = $parametros["nroMesa"];

            if(validarCadena($idServicio) && validarNumero($nroMesa) && validarDNI($dniCliente)
               validarCadena($estado))
            {
                try
                {
                    $conStr = "mysql:host=localhost;dbname=admin_comanda";
                    $user ="yo";
                    $pass ="cp35371754";
                    $pdo = new PDO($conStr,$user,$pass);
    
                    $sentencia = $pdo->prepare("INSERT INTO mesa (idServicio,estado,foto,fecha
                                                                  dniCliente,nroMesa) 
                                                    VALUES (:idServicio,:estado,:foto,:fecha
                                                            :dniCliente,:nroMesa)");
                    $sentencia->bindValue(':idServicio', $idServicio);
                    $sentencia->bindValue(':estado', $estado);
                    $sentencia->bindValue(':foto', $foto);
                    $sentencia->bindValue(':fecha', $fecha);
                    $sentencia->bindValue(':dniCliente', $dniCliente);
                    $sentencia->bindValue(':nroMesa', $nroMesa);

                    if($sentencia->execute())
                    {
                        $payload = json_encode(array("mensaje"=>"mesa creada exitosamente"));
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

        if($modo == "admin" || $modo == "mozo")
        {
            $parametros = $request->getParsedBody();
            $dniCliente = $parametros["dniCliente"];


            if(validarDNI($dniCliente))
            {
                try
                {
                    $conStr = "mysql:host=localhost;dbname=admin_comanda";
                    $user ="yo";
                    $pass ="cp35371754";
                    $pdo = new PDO($conStr,$user,$pass);
    
                    $sentencia = $pdo->prepare("SELECT FROM mesa WHERE dniCliente = :dniCliente");
                    $sentencia->bindValue(':idServicio', $idServicio);


                    if($sentencia->execute())
                    {
                        $resultado = $sentencia->fetchALL(PDO :: FETCH_ASSOC);
                        
                        if(!empty($resultado))
                        {
                            $contador = 0;
                            foreach($resultado as $mesa)
                            {
                                $contador++;
                                $i = $mesa["idServicio"];
                                $e = $mesa["estado"];
                                $f = $mesa["fecha"];
                                $d = $mesa["dniCliente"];
                                $n = $mesa["nroMesa"];

                                echo ("dni $d nro mesa $n id servicio $i estado $e fecha $f");
                            }
                            
                            $payload = json_encode(array("mensaje"=>"cantidad de servicios $contador"));
                            $response->getBody()->write($payload);
                        }
                        else
                        {
                            $payload = json_encode(array("mensaje"=>"No se encontraron servicios con ese DNI"));
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
    
                $sentencia = $pdo->prepare("SELECT FROM mesa");

                if($sentencia->execute())
                {
                    $resultado = $sentencia->fetchAll(PDO :: FETCH_ASSOC);

                    if(!empty($resultado))
                    {
                        $contador = 0;
                        foreach($resultado as $mesa)
                        {
                            $contador++;
                            $i = $mesa["idServicio"];
                            $e = $mesa["estado"];
                            $f = $mesa["fecha"];
                            $d = $mesa["dniCliente"];
                            $n = $mesa["nroMesa"];

                            echo ("dni $d nro mesa $n id servicio $i estado $e fecha $f");
                        }
                        
                        $payload = json_encode(array("mensaje"=>"cantidad de mesas $contador"));
                        $response->getBody()->write($payload);
                    }
                    else
                    {
                        $payload = json_encode(array("mensaje"=>"No se encontraron mesas"));
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
            $estado = $parametros["estado"];


            if(validarCadena($estado))
            {
                try
                {
                    $conStr = "mysql:host=localhost;dbname=admin_comanda";
                    $user ="yo";
                    $pass ="cp35371754";
                    $pdo = new PDO($conStr,$user,$pass);
    
                    $sentencia = $pdo->prepare("UPDATE mesa SET estado = :estado
                                                WHERE idServicio = :idServicio");
                    $sentencia->bindValue(':idServicio', $idServicio);
                    $sentencia->bindValue(':estado', $estado);


                    if($sentencia->execute())
                    {
                        $payload = json_encode(array("mensaje"=>"mesa modificada exitosamente"));
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
}

?>