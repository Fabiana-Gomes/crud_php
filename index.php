<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/src/controllers/UserController.php';

if (!isset($_SESSION['loggedin'])) {
    header('Location: src/login/login.php');
    exit;
}
$userController = new UserController($pdo);
$users = $userController->index();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $userController->create($_POST['name'], $_POST['email']);
    } elseif (isset($_POST['update'])) {
        $userController->update($_POST['id'], $_POST['name'], $_POST['email']);
    } elseif (isset($_POST['delete'])) {
        $userController->delete($_POST['id']);
        $_SESSION['message'] = 'Usuário removido com sucesso!';
    }
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=logout" />
    <title>Gerenciador de usuários</title>
</head>

<body>
    <?php if (isset($_SESSION['alert'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '<?= $_SESSION['alert']['type'] ?>',
                    title: '<?= $_SESSION['alert']['type'] === 'success' ? 'Sucesso!' : 'Erro!' ?>',
                    text: '<?= $_SESSION['alert']['message'] ?>',
                    confirmButtonColor: '#3a5ae8'
                });
            });
        </script>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

    <div class="container">
        <header class="header">
            <h1>Gerenciador de usuários</h1>
            <a id="logout-btn" href="src/login/logout.php" class="logout-btn"><span class="material-symbols-outlined">
                    logout
                </span></a>
        </header>

        <div class="form-container">
            <form id="createForm" method="POST">
                <input type="text" id="createName" name="name" placeholder="Nome" required>
                <input type="email" id="createEmail" name="email" placeholder="Email">
                <button type="submit" name="create">Adicionar</button>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlentities($user['name']) ?></td>
                            <td><?= htmlentities($user['email']) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="edit-btn"
                                        data-id="<?= $user['id'] ?>"
                                        data-name="<?= $user['name'] ?>"
                                        data-email="<?= $user['email'] ?>">
                                        Editar
                                    </button>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit" name="delete" class="delete-btn">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="editModal" class="modal">
        <div class="modal-content">
            <button class="close-modal" aria-label="Fechar modal">&times;</button>
            <h2 class="modal-title">Alterar Cadastro</h2>
            <form class="modal-form" method="POST">
                <input type="hidden" id="editId" name="id">
                <input type="text" id="editName" name="name" placeholder="Nome" required>
                <input type="email" id="editEmail" name="email" placeholder="Email" required>
                <button type="submit" name="update">Atualizar</button>
            </form>
        </div>
    </div>
    <script src="public/js/script.js"></script>
</body>

</html>