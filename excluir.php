<?php
include('config/db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "<p>Usuário excluído com sucesso!</p>";
    } else {
        echo "<p>Erro ao excluir usuário.</p>";
    }
}

header("Location: index.php");
exit;
?>
