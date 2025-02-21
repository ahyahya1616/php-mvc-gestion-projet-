<?php
include_once 'Models/Task.php';
include_once 'Models/DatabaseManager.php';
include_once 'Models/NotificationTransactions.php';

class TaskTransactions {

    private $pdo;
    

    public function __construct(DatabaseManager $dbManager)
    {
        $this->pdo = $dbManager->getConnection();
        
    }

    public function dissociateTasks($userId)
    {
        $query = $this->pdo->prepare("UPDATE tasks SET assignee_id = NULL WHERE assignee_id = :userId");
        $query->bindParam(':userId', $userId);
        return $query->execute();
    }


public function getTasksByUserId($userId){
    $query="SELECT * FROM tasks WHERE assignee_id = :userId";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(":userId",$userId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

public function getUserProjects($userId)
{
    $query = "SELECT DISTINCT p.id, p.name, p.description
              FROM projects p
              INNER JOIN tasks t ON p.id = t.project_id
              WHERE t.assignee_id = :userId";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getUserTasksByProject($userId, $projectId)
{
    $query = "SELECT id, name, description, status, priority, planned_end_date 
              FROM tasks
              WHERE assignee_id = :userId AND project_id = :projectId";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    
    public function getAllTasksWithProjectName()
    {
        // Requête SQL avec jointures simples
        $query = "
                 SELECT 
                t.*, 
                p.name AS project_name, 
                u.name AS member_name
                FROM 
                tasks AS t
                JOIN 
                projects AS p ON t.project_id = p.id
                JOIN 
                users AS u ON t.assignee_id = u.id;
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
    
        $tasks = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $task = new Task(
                $row['id'],
                $row['project_id'],
                $row['name'],
                $row['description'],
                $row['start_date'],
                $row['planned_end_date'],
                $row['actual_end_date'],
                $row['status'],
                $row['priority'],
                $row['assignee_id']
            );
            $task->setProjectName($row['project_name']); // Ajouter le nom du projet
            $task->setMemberName($row['member_name']);   // Ajouter le nom de l'utilisateur assigné
            $tasks[] = $task;
        }
    
        return $tasks;
    }
    

    public function getNombreTasks(){
        $query = $this->pdo->prepare("SELECT COUNT(*) AS ongoing_tasks FROM tasks WHERE status = 'En cours'");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['ongoing_tasks'];
    }
    

    public function getTotalTasks() {
        $query = $this->pdo->prepare("SELECT COUNT(*) AS total_tasks FROM tasks");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['total_tasks'];
    }

    
    public function getTasksByStatus() {
        $query = $this->pdo->prepare("
            SELECT status, COUNT(*) AS count
            FROM tasks
            GROUP BY status
        ");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC); // Renvoie un tableau avec chaque statut et son nombre
    }

    
    public function getTasksByPriority() {
        $query = $this->pdo->prepare("
            SELECT priority, COUNT(*) AS count
            FROM tasks
            GROUP BY priority
        ");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getAverageCompletionTime() {
        $query = $this->pdo->prepare("
            SELECT AVG(DATEDIFF(actual_end_date, start_date)) AS avg_completion_time
            FROM tasks
            WHERE status = 'Terminé'
        ");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['avg_completion_time'];
    }

    
    public function getAllTasks() {
        $query = $this->pdo->prepare("SELECT * FROM tasks");
        $query->execute();
        $tasks = $query->fetchAll(PDO::FETCH_ASSOC);
        $objTasks = [];

        foreach ($tasks as $task) {
            $objTasks[] = new Task(
                $task['id'],
                $task['project_id'],
                $task['name'],
                $task['description'],
                $task['start_date'],
                $task['planned_end_date'],
                $task['actual_end_date'],
                $task['status'],
                $task['priority'],
                $task['assignee_id']
            );
        }
        return $objTasks;
    }

    // Récupérer une tâche par son ID
    public function getTaskById($id) {
        $query = $this->pdo->prepare("SELECT * FROM tasks WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $task = $query->fetch(PDO::FETCH_ASSOC);

        if ($task) {
            return new Task(
                $task['id'],
                $task['project_id'],
                $task['name'],
                $task['description'],
                $task['start_date'],
                $task['planned_end_date'],
                $task['actual_end_date'],
                $task['status'],
                $task['priority'],
                $task['assignee_id']
            );
        }
        return null;
    }

    
    // Mettre à jour une tâche
    public function updateTask($id, $project_id, $name, $description, $start_date, $planned_end_date, $actual_end_date, $status, $priority, $assignee_id) {
        try {
            $query = $this->pdo->prepare("
                UPDATE tasks
                SET project_id = :project_id,
                    name = :name,
                    description = :description,
                    start_date = :start_date,
                    planned_end_date = :planned_end_date,
                    actual_end_date = :actual_end_date,
                    status = :status,
                    priority = :priority,
                    assignee_id = :assignee_id
                WHERE id = :id
            ");

            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':project_id', $project_id, PDO::PARAM_INT);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->bindParam(':description', $description, PDO::PARAM_STR);
            $query->bindParam(':start_date', $start_date, PDO::PARAM_STR);
            $query->bindParam(':planned_end_date', $planned_end_date, PDO::PARAM_STR);
            $query->bindParam(':actual_end_date', $actual_end_date, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_STR);
            $query->bindParam(':priority', $priority, PDO::PARAM_STR);
            $query->bindParam(':assignee_id', $assignee_id, PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de la tâche : " . $e->getMessage());
            return false;
        }
    }

    // Supprimer une tâche par son ID
    public function deleteTaskById($id) {
        try {
            $query = $this->pdo->prepare("DELETE FROM tasks WHERE id = :id");
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de la tâche : " . $e->getMessage());
            return false;
        }
    }

    // Récupérer les tâches d'un projet donné
    public function getTasksByProjectId($projectId) {
        $query = $this->pdo->prepare("
            SELECT 
    tasks.id AS task_id,
    tasks.project_id,
    tasks.name AS task_name,
    tasks.description,
    tasks.start_date,
    tasks.planned_end_date,
    tasks.actual_end_date,
    tasks.status,
    tasks.priority,
    tasks.assignee_id,
    users.name AS assignee_name,
    projects.name AS project_name
    FROM tasks
    JOIN users ON tasks.assignee_id = users.id
    JOIN projects ON tasks.project_id = projects.id
    WHERE tasks.project_id = :projectId
        ");
    
        $query->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        $query->execute();
        $tasks = $query->fetchAll(PDO::FETCH_ASSOC);
    
        return $tasks; 
    }

    public function getProjectProgress($projectId) {
        $query = $this->pdo->prepare("
            SELECT 
                COUNT(*) AS total_tasks,
                SUM(CASE WHEN status = 'Terminé' THEN 1 ELSE 0 END) AS completed_tasks
            FROM tasks
            WHERE project_id = :projectId
        ");
    
        $query->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
    
        if ($result['total_tasks'] == 0) {
            return 0; // Pas de tâches, avancement à 0%
        }
    
        $progress = ($result['completed_tasks'] / $result['total_tasks']) * 100;
        return round($progress, 2); // Retourne le pourcentage arrondi
    }
    
    public function getProjectTasks($projectId) {
        try {
            $query = $this->pdo->prepare("
                SELECT t.id, t.name 
                FROM tasks t
                WHERE t.project_id = :projectId
            ");
            $query->bindParam(':projectId', $projectId, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des tâches du projet : " . $e->getMessage());
            return [];
        }
    }
    public function createTaskWithMember($projectId, $userId, $name, $description, $startDate, $endDate,$status,$priority) {
        try {
            $query = $this->pdo->prepare("
                INSERT INTO tasks (project_id, name, description, start_date, planned_end_date, status, priority, assignee_id)
                VALUES (:projectId, :name, :description, :startDate, :endDate, :status, :priority, :userId)
            ");
            $query->bindParam(':projectId', $projectId, PDO::PARAM_INT);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->bindParam(':description', $description, PDO::PARAM_STR);
            $query->bindParam(':startDate', $startDate, PDO::PARAM_STR);
            $query->bindParam(':endDate', $endDate, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_STR);
            $query->bindParam(':priority', $priority, PDO::PARAM_STR);
            $query->bindParam(':userId', $userId, PDO::PARAM_INT);
           
            return $query->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création d'une tâche : " . $e->getMessage());
            return false;
        }
    }
    
    
    public function updateTaskStatus($taskId, $newStatus)
{
    // Mettre à jour le statut
    $query = "UPDATE tasks SET status = :status WHERE id = :task_id";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':status', $newStatus, PDO::PARAM_STR);
    $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
    $stmt->execute();

    $query = "SELECT project_id, name FROM tasks WHERE id = :task_id";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
    $stmt->execute();
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($task) {
        $projectId = $task['project_id'];
        $taskName = $task['name'];
        $notificationTransactions =new NotificationTransactions(new DatabaseManager());
        // Créer une notification pour tous les membres du projet
        $notificationTitle = "Mise à jour de tâche";
        $notificationMessage = "Le statut de la tâche '{$taskName}' a été changé à '{$newStatus}'.";
        $this-> $notificationTransactions->createNotificationForProject($projectId, $notificationTitle, $notificationMessage);
    }
}


public function getUpcomingTasksCount($userId)
    {
        $query = "SELECT COUNT(*) as count
                  FROM tasks
                  WHERE assignee_id = :userId AND status = 'À faire'";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    
    public function getCompletedTasksCount($userId)
    {
        $query = "SELECT COUNT(*) as count
                  FROM tasks
                  WHERE assignee_id = :userId AND status = 'Terminé'";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    
    public function getOverdueTasksCount($userId)
    {
        $query = "SELECT COUNT(*) as count
                  FROM tasks
                  WHERE assignee_id = :userId 
                  AND status != 'Terminé'
                  AND planned_end_date < CURDATE()";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }






    public function updateStatusTask($taskId, $newStatus) {
        $query = "UPDATE tasks 
                  SET status = :status, 
                      actual_end_date = CASE WHEN :status = 'Terminé' THEN NOW() ELSE NULL END 
                  WHERE id = :task_id";
    
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':status', $newStatus, PDO::PARAM_STR);
        $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
    
        return $stmt->execute();
    }
    
    public function verifyTaskOwnership($taskId, $userId) {
        $query = "SELECT COUNT(*) FROM tasks WHERE id = :taskId AND assignee_id = :userId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':taskId', $taskId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
    


