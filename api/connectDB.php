<?php

    // Función para conectar a la base de datos
    function connectDB(){
            $conexion = "mysql:dbname=proyecto;host=localhost";
            $user = "root";
            $password = "";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try{
                $bd = new PDO($conexion, $user, $password,$options);
                $bd->exec("set names utf8mb4");
                return $bd;
            }catch(PDOException $e){
                echo "Error al conectar con la base de datos " . $e;
                return false;
            }
        }