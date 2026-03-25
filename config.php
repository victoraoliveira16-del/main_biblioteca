<?php
$host = 'localhost';
$db   = 'livraria_livh';
$user = 'root';
$pass = ''; // Senha padrão do XAMPP é vazia

try {
    // Criando a conexão PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Conexão realizada com sucesso!"; 
} catch (PDOException $e) {
    // Caso a conexão falhe, exibe o erro
    die("Erro ao conectar: " . $e->getMessage());
}
?>