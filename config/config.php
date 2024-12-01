<?php
require_once __DIR__ . '/conexion.php';

// retorna un objeto de la clase Conexion con la conexion a y mysql iniciada
function getConexion(){
    return new Conexion("localhost", "root", "12345", "registropruebas");
}
