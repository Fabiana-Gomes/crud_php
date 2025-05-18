<?php

class User{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function create($name, $email) {
        $sql = "INSERT INTO users(name, email) VALUES (:name, :email)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['name'=> $name, 'email'=> $email]); 
    }

    public function read(){
        $sql = "SELECT * from users";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $email){
        $sql = "UPDATE users SET name =:name, email = :email WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id, 'name' => $name, 'email' => $email]);
    }

   public function delete($id) {
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute(['id' => $id]);
}
}
?>