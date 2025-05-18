<?php
$host = 'localhost';
$dbname = 'crud_php';
$username = 'root';
$password = '';

try{
    $pdo = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
}catch(PDOException $e){
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>