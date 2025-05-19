<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (isset($_SESSION['loggedin'])) {
    header('Location: ../../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === 'admin' && $password === '123') {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header('Location: ../../index.php');
        exit;
    } else {
        $_SESSION['login_error'] = 'Credenciais inválidas';
        header('Location: login.php');
        exit;
    }
}

$error = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/CRUD/public/css/style login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="login-container">
        <h1 class="login-title">Gerenciador de Usuários</h1>
        <form class="login-form" method="POST">
            <input type="text" name="username" placeholder="Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <div class="button-container">
                <button>Entrar</button>
            </div>
        </form>
    </div>
    <?php if ($error): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro no Login',
                    text: '<?php echo addslashes($error); ?>',
                    confirmButtonColor: '#224ead',
                    confirmButtonText: 'Tentar novamente',
                    backdrop: `
                    rgba(0,0,0,0.4)
                    url("/CRUD/public/imagens/02.jpg")
                    center left
                    no-repeat`
                });
            });
        </script>
    <?php endif; ?>
</body>

</html>