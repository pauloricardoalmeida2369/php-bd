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