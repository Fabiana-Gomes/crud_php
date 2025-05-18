<?php
require_once __DIR__ . '/../classe/User.php';
class UserController {
    private $user;
    private $pdo; 

    public function __construct($pdo) {
        $this->pdo = $pdo; 
        $this->user = new User($pdo);
    }

    // Criar novo usuário
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

    // Read
     public function index() {
        return $this->user->read();
    }

    // Update
  public function update($id, $name, $email) {
    $checkEmail = $this->pdo->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
    $checkEmail->execute(['email' => $email, 'id' => $id]);
    if ($checkEmail->fetch()) {
        $_SESSION['alert'] = ['type' => 'error','message' => 'Este e-mail já está cadastrado para outro usuário!'
        ];
        return false;
    }$result = $this->user->update($id, $name, $email);
    if ($result) {
        $_SESSION['alert'] = ['type' => 'success','message' => 'Usuário atualizado com sucesso!'];
        return true;
    } else {
        $_SESSION['alert'] = ['type' => 'error','message' => 'Nenhuma alteração foi realizada!'
        ];
        return false;
    }
}

    // Delet
    public function delete($id) {
        try {
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            if ($stmt->rowCount() > 0) {
                $_SESSION['alert'] = ['type' => 'success','message' => 'Usuário removido com sucesso!'];
                return true;
            } else {$_SESSION['alert'] = ['type' => 'error','message' => 'Usuário não encontrado!'
                ];
                return false;
            }
        } catch (PDOException $e) {$_SESSION['alert'] = ['type' => 'error','message' => 'Erro ao remover usuário: ' . $e->getMessage()
            ];
            return false;
        }
}
}