<?php 
include_once 'Models/Project.php';
include_once 'Models/DatabaseManager.php';

Class ProjectTransactions {

    private $pdo;

    public function __construct(DatabaseManager $dbManager)
    {
        $this->pdo = $dbManager->getConnection();
    }


    

public function getAllProjects(){
    $query=$this->pdo->prepare("SELECT * FROM projects");
    $query->execute();
    $projects=$query->fetchAll(PDO::FETCH_ASSOC);
    $objProject=[];
    foreach ($projects as $project){
     $objProject[]= new Project($project['id'], $project['name'], $project['description'], $project['start_date'] ,$project['end_date'],$project['status'],$project['manager_id']);
    }
    return $objProject;
}

public function getProjectById($id) {
    $query = $this->pdo->prepare("SELECT * FROM projects WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $project = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($project) {
       return new Project($project['id'], $project['name'], $project['description'], $project['start_date'] ,$project['end_date'],$project['status'],$project['manager_id']);
    }
    
    return null; 
}

public function updateProject($id, $name, $description, $startDate, $endDate, $status, $managerId) {
    try {
        $query = $this->pdo->prepare("
            UPDATE projects 
            SET name = :name, 
                description = :description, 
                start_date = :start_date, 
                end_date = :end_date, 
                status = :status, 
                manager_id = :manager_id 
            WHERE id = :id
        ");

        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':start_date', $startDate, PDO::PARAM_STR);
        $query->bindParam(':end_date', $endDate, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':manager_id', $managerId, PDO::PARAM_INT);

        return $query->execute();
    } catch (PDOException $e) {
        error_log("Erreur lors de la mise à jour du projet : " . $e->getMessage());
        return false;
    }
}


public function getAllProjectsWithManagers()
{
    $query = "SELECT p.*, m.name AS manager_name FROM projects p
              LEFT JOIN users m ON p.manager_id = m.id";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute();

    $projects = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $project = new Project(
            $row['id'],
            $row['name'],
            $row['description'],
            $row['start_date'],
            $row['end_date'],
            $row['status'],
            $row['manager_id']
        );
        $project->setManagerName($row['manager_name']); // Ajouter le nom du manager
        $projects[] = $project;
    }
    return $projects;
}


public function deleteProjectById($projectId) {
    try {
        $query = $this->pdo->prepare("DELETE FROM projects WHERE id = :projectId");
        $query->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        return $query->execute();
    } catch (PDOException $e) {
        error_log("Erreur lors de la suppression du projet : " . $e->getMessage());
        return false;
    }
}


public function getTotalProjects() {
    $query = $this->pdo->prepare("SELECT COUNT(*) AS total_projects FROM projects");
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result['total_projects'];
}

// Nombre de projets par statut
public function getProjectsByStatus() {
    $query = $this->pdo->prepare("
        SELECT status, COUNT(*) AS count
        FROM projects
        GROUP BY status
    ");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Nombre de projets par manager
public function getProjectsByManager() {
    $query = $this->pdo->prepare("
        SELECT u.name AS manager_name, COUNT(p.id) AS project_count
        FROM projects p
        INNER JOIN users u ON p.manager_id = u.id
        GROUP BY u.name
    ");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Durée moyenne des projets (en jours)
public function getAverageProjectDuration() {
    $query = $this->pdo->prepare("
        SELECT AVG(DATEDIFF(end_date, start_date)) AS avg_duration
        FROM projects
        WHERE status = 'Terminé'
    ");
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result['avg_duration'];
}


public function createProject(Project $project) {
    try {
        $stmt = $this->pdo->prepare("
            INSERT INTO projects (name, description, start_date, end_date, status, manager_id) 
            VALUES (:name, :description, :start_date, :end_date, :status, :manager_id)
        ");

        $stmt->execute([
            'name' => $project->getName(),
            'description' => $project->getDescription(),
            'start_date' => $project->getStartDate(),
            'end_date' => $project->getEndDate(),
            'status' => $project->getStatus(),
            'manager_id' => $project->getManagerId()
        ]);

        return true;
    } catch (PDOException $e) {
        error_log("Erreur lors de la création du projet : " . $e->getMessage());
        return false;
    }
}





public function getProjectsByResponsable($id){

$query=$this->pdo->prepare("SELECT * FROM projects WHERE manager_id =:id");
$query->execute(['id'=>$id]);
$projects=$query->fetchAll(PDO::FETCH_ASSOC);
$objProject=[];
foreach ($projects as $project){
 $objProject[]= new Project($project['id'], $project['name'], $project['description'], $project['start_date'] ,$project['end_date'],$project['status'],$project['manager_id']);
}
return $objProject;





}

public function getRelevantProjectIds($userId) {
    try {
        $query = $this->pdo->prepare("
            SELECT DISTINCT p.id 
            FROM projects p
            LEFT JOIN tasks t ON t.project_id = p.id
            WHERE t.assignee_id = :user_id OR p.manager_id = :user_id
        ");
        $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des projets pertinents : " . $e->getMessage());
        return [];
    }
}

public function countProjectsInProgress($id) {
    $query = $this->pdo->prepare("
        SELECT COUNT(*) AS count 
        FROM projects 
        WHERE status = 'En cours' AND manager_id = :id
    ");
    $query->execute(['id'=>$id]);
    return $query->fetch(PDO::FETCH_ASSOC)['count'];
}


public function countProjectsCompleted($id) {
    $query = $this->pdo->prepare("
        SELECT COUNT(*) AS count 
        FROM projects 
        WHERE status = 'Terminé' AND manager_id = :id
    ");
    $query->execute(['id'=>$id]);
    return $query->fetch(PDO::FETCH_ASSOC)['count'];
}

public function countProjectsOverdue($id) {
    $query = $this->pdo->prepare("
        SELECT COUNT(*) AS count 
        FROM projects 
        WHERE end_date < CURDATE() AND status != 'Terminé' AND manager_id = :id
    ");
    $query->execute(['id'=>$id]);
    return $query->fetch(PDO::FETCH_ASSOC)['count'];
}

public function getGroupMembers($projectId) {
    $query = $this->pdo->prepare("
        SELECT DISTINCT
            users.id AS member_id, 
            users.name AS member_name, 
            users.email AS member_email, 
            users.role AS member_role
        FROM tasks
        JOIN users ON tasks.assignee_id = users.id
        WHERE tasks.project_id = :projectId
    ");

    $query->bindParam(':projectId', $projectId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

public function assignUsersToTask($userId, $taskId) {
    try {
        $query = $this->pdo->prepare("
            UPDATE tasks
            SET assignee_id = :userId
            WHERE id = :taskId
        ");
        $query->bindParam(':userId', $userId, PDO::PARAM_INT);
        $query->bindParam(':taskId', $taskId, PDO::PARAM_INT);
        return $query->execute();
    } catch (PDOException $e) {
        error_log("Erreur lors de l'assignation d'un utilisateur à une tâche : " . $e->getMessage());
        return false;
    }
}

public function getNonProjectMembers($projectId) {
    $query = "
        SELECT u.id, u.name, u.email 
        FROM users u 
        WHERE u.role = :role 
        AND u.id NOT IN (
            SELECT t.assignee_id 
            FROM tasks t 
            WHERE t.assignee_id IS NOT NULL 
            AND t.project_id = :projectId
        )
    ";

    try {
        $result = $this->pdo->prepare($query);
        $result->execute([
            ':role' => 'Membre de l\'équipe',
            ':projectId' => $projectId
        ]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error in getNonProjectMembers: " . $e->getMessage());
        return [];
    }
}


public function getProjectByTaskId($taskId) {
    $query = "SELECT p.id as project_id 
              FROM projects p 
              JOIN tasks t ON p.id = t.project_id 
              WHERE t.id = :taskId";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':taskId', $taskId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function assignUserToTask($projectId, $userId) {
    try {
        $query = $this->pdo->prepare("
            INSERT INTO tasks (project_id, name, description, start_date, planned_end_date, status, priority, assignee_id)
            VALUES (:projectId, 'Nouvelle tâche pour membre', 'Tâche associée à un nouveau membre.', CURDATE(), CURDATE(), 'À faire', 'moyenne', :userId)
        ");
        $query->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        $query->bindParam(':userId', $userId, PDO::PARAM_INT);
        return $query->execute();
    } catch (PDOException $e) {
        error_log("Erreur lors de l'assignation d'un utilisateur au projet : " . $e->getMessage());
        return false;
    }
}



}


