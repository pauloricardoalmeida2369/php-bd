<?php include('config/db.php'); ?>
<?php include('includes/header.php'); ?>

<h2>Criar Novo Usuário</h2>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);

    if ($stmt->execute()) {
        echo "<p>Usuário criado com sucesso!</p>";
    } else {
        echo "<p>Erro ao criar usuário.</p>";
    }
}
?>

<form method="POST" action="criar.php">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required>

    <button type="submit">Criar Usuário</button>
</form>

<?php include('includes/footer.php'); ?>
