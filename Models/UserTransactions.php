<?php 
include_once 'Models/User.php';
include_once 'Models/DatabaseManager.php';
include_once 'Models/TaskTransactions.php';

Class UserTransactions{

private $pdo;

public function __construct(DatabaseManager $dbManager)
{
    $this->pdo = $dbManager->getConnection();
}



public function login($email, $password)
{

    $query = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
    $query->bindParam(':email', $email, PDO::PARAM_STR);

    $query->execute();

    
    $user = $query->fetch(PDO::FETCH_ASSOC);

   
    if ($user && password_verify($password, $user['password'])) {
        
        return new User(
            $user['id'],
            $user['name'],
            $user['email'],
            $user['password'],
            $user['role'],
           
        );
    }

    return null;
}


public function getAllUsers(){
    $query=$this->pdo->prepare("SELECT * FROM users");
    $query->execute();
    $users=$query->fetchAll(PDO::FETCH_ASSOC);
    $objUser=[];
    foreach ($users as $user){
     $objUser[]= new User($user['id'], $user['name'], $user['email'], $user['password'] ,$user['role']);
    }
    return $objUser;
}

public function getUserById($id) {
    $query = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $userData = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($userData) {
        return new User($userData['id'], $userData['name'], $userData['email'], $userData['password'], $userData['role']);
    }
    
    return null; 
}



    public function getAllManagers() {
        try {
            $query = "SELECT id, name, email, role 
                      FROM users 
                      WHERE role = 'Responsable de projet' 
                      ORDER BY name ASC";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            
            $managers = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $manager = new User();
                $manager->setId($row['id']);
                $manager->setName($row['name']);
                $manager->setEmail($row['email']);
                $manager->setRole($row['role']);
                $managers[] = $manager;
            }
            
            return $managers;
        } catch (PDOException $e) {
            // Log l'erreur
            error_log("Erreur lors de la récupération des managers : " . $e->getMessage());
            return [];
        }
    }


   public function getAllMembers($projectId) {
    try{
    $query = $this->pdo->prepare("
        SELECT u.id, u.name, u.email, u.role
        FROM users u
        JOIN tasks t ON u.id = t.assignee_id
        JOIN projects p ON t.project_id = p.id
        WHERE p.id = :project_id
          AND u.role = 'Membre de l\'équipe'
    ");
    $query->execute([':project_id' => $projectId]);
            $members = [];
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $member = new User();
                $member->setId($row['id']);
                $member->setName($row['name']);
                $member->setEmail($row['email']);
                $member->setRole($row['role']);
                $members[] = $member;
            }
            
            return $members;
        } catch (PDOException $e) {
            // Log l'erreur
            error_log("Erreur lors de la récupération des members : " . $e->getMessage());
            return [];
        }
    }


public function updateUser($id, $name, $email, $role) {
    $query = $this->pdo->prepare("UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':role', $role, PDO::PARAM_STR);
    return $query->execute(); 
}

public function deleteUserById($userId)
    {
        $taskTransactions = new TaskTransactions(new DatabaseManager());

       
        if ($taskTransactions->dissociateTasks($userId)) {

            $query = $this->pdo->prepare("DELETE FROM users WHERE id = :userId");
            $query->bindParam(':userId', $userId);
            return $query->execute();
        }

        
        return false;
    }


 public function addUser($name,$email, $role, $password){
    try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = $this->pdo->prepare("INSERT INTO users (name, email, role, password) VALUES (:name, :email, :role, :password)");
        
        $query->bindParam(':name', $name);
        $query->bindParam(':email', $email);
        $query->bindParam(':role', $role);
        $query->bindParam(':password', $hashedPassword);


      
        return $query->execute();
    } catch (PDOException $e) {
 
        error_log("Erreur lors de l'ajout d'un utilisateur : " . $e->getMessage());
        return false;
    }
 }   

  
 


 public function getAvailableUsers($projectId) {
    try {
        $query = $this->pdo->prepare("
            SELECT u.id, u.name 
            FROM users u
            WHERE u.role = 'Membre de l\'équipe'
            AND u.id NOT IN (
                SELECT pu.user_id 
                FROM project_users pu 
                WHERE pu.project_id = :projectId
            )
        ");
        $query->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des utilisateurs disponibles : " . $e->getMessage());
        return [];
    }
}


}

