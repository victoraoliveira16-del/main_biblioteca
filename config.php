<?php
$host = 'localhost';
$db   = 'livraria_livh';
$user = 'root';
$pass = ''; // senha padrão do XAMPP é vazia

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}
?>