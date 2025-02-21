<?php 

include_once 'Models/Comment.php';
include_once 'Models/DatabaseManager.php';
include_once 'Models/ProjectTransactions.php';

Class NotificationTransactions{
private $pdo;

public function __construct(DatabaseManager $dbManager){
 $this->pdo=$dbManager->getConnection();
}


public function getNotificationsByUserId($userId, $projects) {
    try {
        if (empty($projects)) {
            return [];
        }
        
        $query = $this->pdo->prepare("
        SELECT 
            n.*, 
            u.name AS sender_name,
            t.name AS task_name
        FROM 
            notifications n
        INNER JOIN 
            users u ON n.user_id = u.id
        INNER JOIN 
            tasks t ON t.id = n.task_id
        WHERE 
            n.project_id IN (" . implode(',', array_fill(0, count($projects), '?')) . ")
            AND n.user_id != ?
        ORDER BY 
            n.created_at DESC
        ");
        
        foreach ($projects as $index => $projectId) {
            $query->bindValue($index + 1, $projectId, PDO::PARAM_INT);
        }
        
        $query->bindValue(count($projects) + 1, $userId, PDO::PARAM_INT);
        
        $query->execute();
        
        return $query->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log("Erreur: " . $e->getMessage());
        return [];
    }
}
public function createTaskStatusNotification($userId,$taskId, $newStatus,$projectId) {
    try {
        $query = $this->pdo->prepare("
            INSERT INTO notifications (user_id, title, message,project_id,task_id)
            Values 
            (:userId,
            'Changement de statut',
            CONCAT('Nouvelle Statut : ', :newStatus),
            :projectId,
            :taskId
            )");
            $query->bindParam(':userId', $userId, PDO::PARAM_INT);   
        $query->bindParam(':taskId', $taskId, PDO::PARAM_INT);
        $query->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
        $query->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        return $query->execute();
    } catch (PDOException $e) {
        error_log("Erreur lors de la création de la notification de statut : " . $e->getMessage());
        return false;
    }
}
public function createCommentNotification($taskId, $commentContent, $userId, $projectId) {
    try {
        $query = $this->pdo->prepare("
        INSERT INTO notifications (user_id, title, message, is_read, created_at, project_id, task_id)
        VALUES (
            :userId,
            'Nouveau commentaire',
            :commentContent,
            0,
            NOW(),
            :projectId,
            :taskId
        )");
        
        $query->bindParam(':taskId', $taskId, PDO::PARAM_INT);
        $query->bindParam(':commentContent', $commentContent, PDO::PARAM_STR);
        $query->bindParam(':userId', $userId, PDO::PARAM_INT);
        $query->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        
        return $query->execute();
        
    } catch (PDOException $e) {
        error_log("Erreur lors de la création de la notification : " . $e->getMessage());
        return false;
    }
}






}