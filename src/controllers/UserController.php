<?php
require_once __DIR__ . '/../classe/User.php';
class UserController {
    private $user;
    private $pdo; 

    public function __construct($pdo) {
        $this->pdo = $pdo; 
        $this->user = new User($pdo);
    }

    public function create($name, $email) {
    $checkName = $this->pdo->prepare("SELECT id FROM users WHERE name = :name");
    $checkName->execute(['name' => $name]);
    if ($checkName->fetch()) {
        $_SESSION['alert'] = ['type' => 'error','message' => 'Este nome de usuário já está em uso!'];
        return false;
    }
    $checkEmail = $this->pdo->prepare("SELECT id FROM users WHERE email = :email");
    $checkEmail->execute(['email' => $email]);
    if ($checkEmail->fetch()) {
        $_SESSION['alert'] = ['type' => 'error','message' => 'Este e-mail já está cadastrado!'];
        return false;
    }
    $result = $this->user->create($name, $email);
    if ($result) {
        $_SESSION['alert'] = ['type' => 'success','message' => 'Usuário cadastrado com sucesso!'];
    } else {
        $_SESSION['alert'] = ['type' => 'error','message' => 'Erro ao cadastrar usuário!'];
    }
    return $result;
}

     public function index() {
        return $this->user->read();
    }

    public function update($id, $name, $email) {
        return $this->user->update($id, $name, $email);
    }

public function delete($id) {
    try {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        // Verifica se alguma linha foi afetada
        if ($stmt->rowCount() > 0) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'message' => 'Usuário removido com sucesso!'
            ];
            return true;
        } else {
            $_SESSION['alert'] = [
                'type' => 'error',
                'message' => 'Usuário não encontrado!'
            ];
            return false;
        }
    } catch (PDOException $e) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Erro ao remover usuário: ' . $e->getMessage()
        ];
        return false;
    }
}
}