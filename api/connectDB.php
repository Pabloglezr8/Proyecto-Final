<?php

    // Función para conectar a la base de datos
    function connectDB(){
            $conexion = "mysql:dbname=proyecto;host=localhost";
            $user = "root";
            $password = "";

            try{
                $bd = new PDO($conexion, $user, $password);
                $bd->exec("set names utf8mb4");
                return $bd;
            }catch(PDOException $e){
                echo "Error al conectar con la base de datos " . $e;
                return false;
            }
        }