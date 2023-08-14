<?php

class Mesa
{
    public $idServicio;
    public $estado;
    public $foto;
    public $fecha;
    public $total;
    public $nroMesa;

    public static function deudaPendiente($dni)//retorna true, si el dni no tiene una deuda pendiente
    {                                          //o no existe en la base de dato, caso contrario false
        $valor = false;
        $control = 0;
        if(isset($dni))
        {
            try
            {
                $conStr = "mysql:host=localhost;dbname=admin_comanda";
                $user ="yo";
                $pass ="cp35371754";
                $pdo = new PDO($conStr,$user,$pass);
    
                    $sentencia = $pdo->prepare("SELECT FROM mesa WHERE dniCliente = :dni");
                    $sentencia->bindValue(':dni', $dni);

                    if($sentencia->execute())
                    {
                        $resultado = $sentencia->fetchAll(PDO :: FETCH_ASSOC);
                        $pdo = null;

                        if(!empty($resultado))
                        {
                            foreach($resultado as $m)
                            {
                                if($m["estado"] != "cerrada")
                                {
                                    $control = 1;
                                    break;
                                }
                            }

                            if($control = 0)
                            {
                                $valor = true;
                            }
                        }

                    }
            }
            catch(PDOException $e)
            {
                $pdo = null;
                echo "Error: " .$e->getMessage();
            }
        }
        return $valor;
    }
    
    public static function traerIDServicioActivoCliente($dni)
    {
        try
        {
            $estado = "cerrada";
            $conStr = "mysql:host=localhost;dbname=admin_comanda";
            $user ="yo";
            $pass ="cp35371754";
            $pdo = new PDO($conStr,$user,$pass);

        
            $sentencia = $pdo->prepare("SELECT FROM mesa WHERE dniCliente = :dni AND estado != :estado");
            $sentencia->bindValue(':dni', $dni);
            $sentencia->bindValue(':estado', $estado);

            if($sentencia->execute())
            {
                $resultado = $sentencia->fetch(PDO :: FETCH_ASSOC);

                if(isset($resultado))
                {
                    return $resultado["idServicio"];
                }
                else
                {
                    return false;
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
}

?>