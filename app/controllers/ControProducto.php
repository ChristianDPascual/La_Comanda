<?php

class ControlProducto extends Producto implements InterfaceApiUsable
{

    public static function CrearUno($request, $response, $args)
    {
        $modo = token :: decodificarToken($request);

        if($modo == "admin")
        {
            $parametros = $request->getParsedBody();
            $nombre = $parametros["nombre"];
            $categoria = $parametros["categoria"];
            $idProducto = $parametros["idProducto"];
            $precioVenta = $parametros["precioVenta"];

            if(validarCadena($nombre) && validarPrecio($precioVenta) && validarNumero($idProducto)
               validarCadena($categoria))
            {
                try
                {
                    $conStr = "mysql:host=localhost;dbname=admin_comanda";
                    $user ="yo";
                    $pass ="cp35371754";
                    $pdo = new PDO($conStr,$user,$pass);
    
                    $sentencia = $pdo->prepare("INSERT INTO productos (nombre,categoria,
                                                                       precioVenta,idProducto) 
                                                    VALUES (:nombre,:categoria,
                                                            :precioVenta,:idProducto) ");
                    $sentencia->bindValue(':nombre', $nombre);
                    $sentencia->bindValue(':categoria', $categoria);
                    $sentencia->bindValue(':precioVenta', $precioVenta);
                    $sentencia->bindValue(':idProducto', $idProducto);


                    if($sentencia->execute())
                    {
                        $payload = json_encode(array("mensaje"=>"producto creado exitosamente"));
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
            $idProducto = $parametros["idProducto"];


            if(validarCadena($idServicio))
            {
                try
                {
                    $conStr = "mysql:host=localhost;dbname=admin_comanda";
                    $user ="yo";
                    $pass ="cp35371754";
                    $pdo = new PDO($conStr,$user,$pass);
    
                    $sentencia = $pdo->prepare("SELECT FROM productos WHERE idProducto = :idProducto");
                    $sentencia->bindValue(':idProducto', $idProducto);


                    if($sentencia->execute())
                    {
                        $resultado = $sentencia->fetch(PDO :: FETCH_ASSOC);
                        
                        if(!empty($resultado))
                        {
                            $n = $resultado["nombre"];
                            $c = $resultado["categoria"];
                            $i = $resultado["idProducto"];
                            $p = $resultado["precioVenta"];
                            
                            $payload = json_encode(array("mensaje"=>"articulo $n categoria $c precio venta $p id $i"));
                            $response->getBody()->write($payload);
                        }
                        else
                        {
                            $payload = json_encode(array("mensaje"=>"No se encontro un producto con ese ID"));
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
    
                $sentencia = $pdo->prepare("SELECT FROM productos");

                if($sentencia->execute())
                {
                    $resultado = $sentencia->fetchAll(PDO :: FETCH_ASSOC);

                    if(!empty($resultado))
                    {
                        $contador = 0;
                        foreach($resultado as $producto)
                        {
                            $contador++;
                            $n = $producto["nombre"];
                            $c = $producto["categoria"];
                            $i = $producto["idProducto"];
                            $p = $producto["precioVenta"];

                            echo ("articulo $n categoria $c precio venta $p id $i");
                        }
                        
                        $payload = json_encode(array("mensaje"=>"cantidad de productos $contador"));
                        $response->getBody()->write($payload);
                    }
                    else
                    {
                        $payload = json_encode(array("mensaje"=>"No se encontraron productos"));
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
            $precioVenta = $parametros["precioVenta"];
            $idProducto = $parametros["idProducto"];


            if(validarCadena($estado))
            {
                try
                {
                    $conStr = "mysql:host=localhost;dbname=admin_comanda";
                    $user ="yo";
                    $pass ="cp35371754";
                    $pdo = new PDO($conStr,$user,$pass);
    
                    $sentencia = $pdo->prepare("UPDATE productos SET precioVenta = :precioVenta
                                                WHERE idProducto = :idProducto");
                    $sentencia->bindValue(':precioVenta', $precioVenta);
                    $sentencia->bindValue(':idProducto', $idProducto);


                    if($sentencia->execute())
                    {
                        $payload = json_encode(array("mensaje"=>"producto modificado exitosamente"));
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
            $idProducto = $parametros["idProducto"];


            if(validarNumero($idProducto))
            {
                try
                {
                    $conStr = "mysql:host=localhost;dbname=admin_comanda";
                    $user ="yo";
                    $pass ="cp35371754";
                    $pdo = new PDO($conStr,$user,$pass);
    
                    $sentencia = $pdo->prepare("DELETE FROM productos
                                                WHERE idProducto = :idProducto");
                    $sentencia->bindValue(':idProducto', $idProducto);

                    if($sentencia->execute())
                    {

                        $payload = json_encode(array("mensaje"=>"producto fue borrado con exito"));
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
                $payload = json_encode(array("mensaje"=>"Error, al ingresar el ID"));
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