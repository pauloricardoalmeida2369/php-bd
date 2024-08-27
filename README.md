CREATE DATABASE meu_sistema;

USE meu_sistema;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(100) NOT NULL
);

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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Sistema em PHP</title>
</head>
<body>
    <header>
        <h1>Bem-vindo ao Meu Sistema</h1>
        <nav>
            <a href="index.php">Início</a>
            <a href="criar.php">Criar Usuário</a>
        </nav>
    </header>
    <main>

    </main>
    <footer>
        <p>&copy; 2024 Meu Sistema</p>
    </footer>
</body>
</html>

body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
}

header {
    background-color: #333;
    color: #fff;
    padding: 10px 20px;
    text-align: center;
}

header h1 {
    margin: 0;
}

nav a {
    color: #fff;
    margin: 0 15px;
    text-decoration: none;
}

nav a:hover {
    text-decoration: underline;
}

main {
    padding: 20px;
}

footer {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 10px;
    position: absolute;
    bottom: 0;
    width: 100%;
}

<?php include('config/db.php'); ?>
<?php include('includes/header.php'); ?>

<h2>Lista de Usuários</h2>

<?php
$sql = "SELECT * FROM usuarios";
$stmt = $conn->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll();

if (count($usuarios) > 0):
?>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo $usuario['id']; ?></td>
                    <td><?php echo $usuario['nome']; ?></td>
                    <td><?php echo $usuario['email']; ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $usuario['id']; ?>">Editar</a> |
                        <a href="excluir.php?id=<?php echo $usuario['id']; ?>">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nenhum usuário encontrado.</p>
<?php endif; ?>

<?php include('includes/footer.php'); ?>

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
