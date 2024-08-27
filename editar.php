<?php include('config/db.php'); ?>
<?php include('includes/header.php'); ?>

<h2>Editar Usuário</h2>

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'] ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : null;

        $sql = "UPDATE usuarios SET nome = :nome, email = :email";
        if ($senha) {
            $sql .= ", senha = :senha";
        }
        $sql .= " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        if ($senha) {
            $stmt->bindParam(':senha', $senha);
        }
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            echo "<p>Usuário atualizado com sucesso!</p>";
        } else {
            echo "<p>Erro ao atualizar usuário.</p>";
        }
    }

    $sql = "SELECT * FROM usuarios WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $usuario = $stmt->fetch();

    if ($usuario):
?>
        <form method="POST" action="editar.php?id=<?php echo $id; ?>">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo $usuario['nome']; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $usuario['email']; ?>" required>

            <label for="senha">Nova Senha (deixe em branco para manter):</label>
            <input type="password" id="senha" name="senha">

            <button type="submit">Atualizar Usuário</button>
        </form>
<?php
    else:
        echo "<p>Usuário não encontrado.</p>";
    endif;
}
?>

<?php include('includes/footer.php'); ?>
