<?php

class ControlStaff extends Cliente implements InterfaceApiUsable
{

    public static function CrearUno($request, $response, $args)
    {
        $modo = token :: decodificarToken($request);

        if($modo == "admin" || $modo == "mozo")
        {
            $parametros = $request->getParsedBody();
            $nombre = $parametros["nombre"];
            $apellido = $parametros["apellido"];
            $dni = $parametros["dni"];
            $mail = $parametros["mail"];

            if(validarDNI($dni) && validarCadena($nombre) && validarCadena($apellido) && validarMail($mail))
            {
                if(Mesa :: deudaPendiente($dni))
                {
                    try
                    {
                        $idServicio = controlID();
                        $conStr = "mysql:host=localhost;dbname=admin_comanda";
                        $user ="yo";
                        $pass ="cp35371754";
                        $pdo = new PDO($conStr,$user,$pass);
        
                        $sentencia = $pdo->prepare("INSERT INTO cliente (nombre,apellido,dni,mail,
                                                                         idServicio) 
                                                        VALUES (:nombre,:apellido,:dni,:mail,
                                                                :idServicio)");
                        $sentencia->bindValue(':nombre', $nombre);
                        $sentencia->bindValue(':apellido', $apellido);
                        $sentencia->bindValue(':dni', $dni);
                        $sentencia->bindValue(':mail', $mail);
                        $sentencia->bindValue(':idServicio', $idServicio);
    
                        if($sentencia->execute())
                        {
                            $payload = json_encode(array("mensaje"=>"cliente creado exitosamente"));
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
                    $payload = json_encode(array("mensaje"=>"Error, el cliente tiene una deuda pendiente"));
                    $response->getBody()->write($payload);
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
            $dni = $parametros["dni"];


            if(validarDNI($dni))
            {
                try
                {
                    $conStr = "mysql:host=localhost;dbname=admin_comanda";
                    $user ="yo";
                    $pass ="cp35371754";
                    $pdo = new PDO($conStr,$user,$pass);
    
                    $sentencia = $pdo->prepare("SELECT FROM cliente WHERE dni = :dni");
                    $sentencia->bindValue(':dni', $dni);


                    if($sentencia->execute())
                    {
                        $resultado = $sentencia->fetch(PDO :: FETCH_ASSOC);
                        
                        if(!empty($resultado))
                        {
                            $n = $resultado["nombre"];
                            $a = $resultado["apellido"];
                            $d = $resultado["dni"];
                            $m = $resultado["mail"];
                            $i = $resultado["idServicio"];

                            $payload = json_encode(array("mensaje"=>"nombre $a apellido $a DNi $d mail $m id servicio $i"));
                            $response->getBody()->write($payload);
                        }
                        else
                        {
                            $payload = json_encode(array("mensaje"=>"No se encontro un cliente con ese DNI"));
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

        if($modo == "admin")
        {
            try
            {
                $conStr = "mysql:host=localhost;dbname=admin_comanda";
                $user ="yo";
                $pass ="cp35371754";
                $pdo = new PDO($conStr,$user,$pass);
    
                $sentencia = $pdo->prepare("SELECT FROM cliente");

                if($sentencia->execute())
                {
                    $resultado = $sentencia->fetchAll(PDO :: FETCH_ASSOC);

                    if(!empty($resultado))
                    {
                        $contador = 0;

                        foreach($resultado as $cliente)
                        {
                            $contador++;
                            $n = $cliente["nombre"];
                            $a = $cliente["apellido"];
                            $d = $cliente["dni"];
                            $m = $cliente["mail"];
                            $i = $cliente["idServicio"];
                            echo "nombre $n apellido $a DNi $d mail $m id servicio $i\n";
                        }
                        $payload = json_encode(array("mensaje"=>"cantidad de empleados totales $contador"));
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

}

?>