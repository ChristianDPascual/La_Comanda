<?php

class ControlStaff extends Staff implements InterfaceApiUsable
{

    public static function CrearUno($request, $response, $args)
    {
        $modo = token :: decodificarToken($request);

        if($modo == "admin")
        {
            $parametros = $request->getParsedBody();
            $nombre = $parametros["nombre"];
            $apellido = $parametros["apellido"];
            $dni = $parametros["dni"];
            $mail = $parametros["mail"];
            $estado = $parametros["estado"];
            $categoria = $parametros["categoria"];

            if(validarDNI($dni) && validarCadena($nombre) && validarCadena($apellido) &&
               validarCadena($estado) && validarCadena($categoria) && validarMail($mail))
            {
                try
                {
                    $conStr = "mysql:host=localhost;dbname=admin_comanda";
                    $user ="yo";
                    $pass ="cp35371754";
                    $pdo = new PDO($conStr,$user,$pass);
    
                    $sentencia = $pdo->prepare("INSERT INTO staff (nombre,apellido,dni,mail,
                                                                   categoria,estado) 
                                                    VALUES (:nombre,:apellido,:dni,:mail,
                                                    :categoria,:estado)");
                    $sentencia->bindValue(':nombre', $nombre);
                    $sentencia->bindValue(':apellido', $apellido);
                    $sentencia->bindValue(':dni', $dni);
                    $sentencia->bindValue(':mail', $mail);
                    $sentencia->bindValue(':categoria', $categoria);
                    $sentencia->bindValue(':estado', $estado);

                    if($sentencia->execute())
                    {
                        $payload = json_encode(array("mensaje"=>"miembro del staff creado exitosamente"));
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
            $dni = $parametros["dni"];


            if(validarDNI($dni))
            {
                try
                {
                    $conStr = "mysql:host=localhost;dbname=admin_comanda";
                    $user ="yo";
                    $pass ="cp35371754";
                    $pdo = new PDO($conStr,$user,$pass);
    
                    $sentencia = $pdo->prepare("SELECT FROM staff WHERE dni = :dni");
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
                            $c = $resultado["categoria"];
                            $e = $resultado["estado"];
                            
                            $payload = json_encode(array("mensaje"=>"nombre $a apellido $a DNi $d\n
                            mail $m categoria $m estado $d"));
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

        if($modo == "admin")
        {
            try
            {
                $conStr = "mysql:host=localhost;dbname=admin_comanda";
                $user ="yo";
                $pass ="cp35371754";
                $pdo = new PDO($conStr,$user,$pass);
    
                $sentencia = $pdo->prepare("SELECT FROM staff");

                if($sentencia->execute())
                {
                    $resultado = $sentencia->fetchAll(PDO :: FETCH_ASSOC);

                    if(!empty($resultado))
                    {
                        $contador = 0;

                        foreach($resultado as $staff)
                        {
                            $contador++;
                            $n = $staff["nombre"];
                            $a = $staff["apellido"];
                            $d = $staff["dni"];
                            $m = $staff["mail"];
                            $c = $staff["categoria"];
                            $e = $staff["estado"];
                            echo "nombre $n apellido $a DNi $d mail $m categoria $m estado $d\n";
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

    public static function ModificarUno($request, $response, $args)
    {
        $modo = token :: decodificarToken($request);

        if($modo == "admin")
        {
            $parametros = $request->getParsedBody();
            $dni = $parametros["dni"];
            $nombre = $parametros["nombre"];
            $apellido = $parametros["apellido"];
            $mail = $parametros["mail"];
            $estado = $parametros["estado"];
            $categoria = $parametros["categoria"];


            if(validarDNI($dni) && validarCadena($nombre) && validarCadena($apellido) &&
               validarCadena($estado) && validarCadena($categoria) && validarMail($mail))
            {
                try
                {
                    $conStr = "mysql:host=localhost;dbname=admin_comanda";
                    $user ="yo";
                    $pass ="cp35371754";
                    $pdo = new PDO($conStr,$user,$pass);
    
                    $sentencia = $pdo->prepare("UPDATE staff SET nombre = :nombre, apellido = :apellido,
                                                                 mail = :mail, estado = :estado,
                                                                categoria = :categoria
                                                            WHERE dni = :dni");
                    $sentencia->bindValue(':nombre', $nombre);
                    $sentencia->bindValue(':apellido', $apellido);
                    $sentencia->bindValue(':dni', $dni);
                    $sentencia->bindValue(':mail', $mail);
                    $sentencia->bindValue(':categoria', $categoria);
                    $sentencia->bindValue(':estado', $estado);


                    if($sentencia->execute())
                    {
                        $payload = json_encode(array("mensaje"=>"$nombre $apellido $dni modificado exitosamente"));
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

        if($modo == "admin")
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
    
                    $sentencia = $pdo->prepare("DELETE FROM staff WHERE dni = :dni");
                    $sentencia->bindValue(':dni', $dni);


                    if($sentencia->execute())
                    {

                        $payload = json_encode(array("mensaje"=>"$dni ha sido borrado"));
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