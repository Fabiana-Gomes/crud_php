<?php
require_once __DIR__ . '/../classe/User.php';

class UserController
{
    private $user;
    private $pdo;
    private $forbiddenPatterns = [
        '/[\'"]/',
        '/\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE)?|INSERT( +INTO)?|MERGE|SELECT|UPDATE|UNION( +ALL)?)\b/i'
    ];

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->user = new User($pdo);
    }

    //anti trombadinhas
    private function sanitizeInput($input, $isEmail = false)
    {
        $input = trim($input);
        foreach ($this->forbiddenPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                throw new InvalidArgumentException("Entrada contém caracteres ou comandos não permitidos");
            }
        }
        $input = strip_tags($input);
        $input = stripslashes($input);
        if ($isEmail && !filter_var($input, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email inválido");
        }
        return $input;
    }

    //validações para create
    public function create($name, $email)
    {
        try {
            $name = $this->sanitizeInput($name);
            $email = $this->sanitizeInput($email, true);
            $checkName = $this->pdo->prepare("SELECT id FROM users WHERE name = :name");
            $checkName->execute(['name' => $name]);
            
            if ($checkName->fetch()) {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Este nome de usuário já está em uso!'];
                return false;
            }

            $checkEmail = $this->pdo->prepare("SELECT id FROM users WHERE email = :email");
            $checkEmail->execute(['email' => $email]);
            
            if ($checkEmail->fetch()) {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Este e-mail já está cadastrado!'];
                return false;
            }

            // Cria o usuário
            $result = $this->user->create($name, $email);
            
            if ($result) {
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Usuário cadastrado com sucesso!'];
            } else {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Erro ao cadastrar usuário!'];
            }
            
            return $result;
            
        } catch (InvalidArgumentException $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => $e->getMessage()];
            return false;
        } catch (PDOException $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Erro no banco de dados'];
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    // Read
    public function index()
    {
        try {
            return $this->user->read();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }

    // Update
    public function update($id, $name, $email)
    {
        try {
            // Sanitização
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if ($id === false) {
                throw new InvalidArgumentException("ID inválido");
            }
            
            $name = $this->sanitizeInput($name);
            $email = $this->sanitizeInput($email, true);

            // Verifica se email pertence a outro usuário
            $checkEmail = $this->pdo->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
            $checkEmail->execute(['email' => $email, 'id' => $id]);
            
            if ($checkEmail->fetch()) {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Este e-mail já está cadastrado para outro usuário!'];
                return false;
            }

            // Atualiza o usuário
            $result = $this->user->update($id, $name, $email);
            
            if ($result) {
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Usuário atualizado com sucesso!'];
                return true;
            }
            
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Nenhuma alteração foi realizada!'];
            return false;
            
        } catch (InvalidArgumentException $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => $e->getMessage()];
            return false;
        } catch (PDOException $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Erro ao atualizar usuário'];
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    // Delete
    public function delete($id)
    {
        try {
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if ($id === false) {
                throw new InvalidArgumentException("ID inválido");
            }

            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);
            
            if ($stmt->rowCount() > 0) {
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Usuário removido com sucesso!'];
                return true;
            }
            
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Usuário não encontrado!'];
            return false;
            
        } catch (PDOException $e) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Erro ao remover usuário'];
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
}