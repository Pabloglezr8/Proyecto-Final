<?php

    // Función para conectar a la base de datos
    function connectDB(){
            $conexion = "mysql:dbname=pagespee_hPe1Kd11;host=localhost";
            $user = "pagespee_hPe1Kd11";
            $password = "k}zJX0/10h9/CU";

            try{
                $bd = new PDO($conexion, $user, $password);
                $bd->exec("set names utf8mb4");
                return $bd;
            }catch(PDOException $e){
                echo "Error al conectar con la base de datos " . $e;
                return false;
            }
        }