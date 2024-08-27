<?php
$host = 'localhost';
$dbname = 'meu_sistema';
$username = 'root';  // Altere se seu usuário do MySQL for diferente
$password = '';      // Adicione a senha do MySQL se houver

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit;
}
?>
