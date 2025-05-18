<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/src/controllers/UserController.php';

$userController = new UserController($pdo);
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['create'])){
        $userController->create($_POST['name'], $_POST['email']);
    }elseif(isset($_POST['update'])){
        $userController->update($_POST['id'], $_POST['name'], $_POST['email']);
    }elseif(isset($_POST['delete'])){
        $userController->delete($_POST['id']);
        $_SESSION['message'] = 'Usuário removido com sucesso!';

   } echo '<script>window.location.href = window.location.href;</script>';
    exit(); 
}

$users = $userController->index();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <META charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/style.css">
    <title>CRUD User</title>
</head>
<body>
    <h1>Usuarios</h1>
    <form id="createForm" method="POST">
        <input type="text" id="createName" name="name" placeholder="Nome" required>
        <input type="email" id="createEmail" name="email" placeholder="required">
        <button type="submit" name="create">Adicionar</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td> <?= $user['id']?> </td>
                <td> <?= $user['name']?> </td>
                <td> <?= $user['email']?> </td>
                <td>
                    <button class="edit-btn" data-id="<?= $user['id']?>" data-name="<?= $user['name']?>" data-email="<?= $user['email']?>">Editar</button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $user['id']?>">
                        <button type="submit" name="delete" class="delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach ?>              
        </tbody>
    </table>

    <!-- Modal -->
     <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">Editar Usuario</span>
            <form method="POST">
                <input type="hidden" id=editId name = "id">
                <input type="text" id="editName" name="name" placeholder="Nome" required>
                <input type="text" id="editEmail" name="email" placeholder="Email" required>
                <button type="submit" name="update">Atualizar</button>
            </form>
        </div>
     </div>
<script src="public/js/script.js"></script>
</body>
</html>