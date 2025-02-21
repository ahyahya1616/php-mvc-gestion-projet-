
<?php
 include_once 'Models/DatabaseManager.php';
 include_once 'Models/NotificationTransactions.php';
 include_once 'Models/ProjectTransactions.php';
 if (session_status() === PHP_SESSION_NONE) {
     session_start();
 }

class NotificationController {
    private $notificationTransactions;
    private $projectTransactions;

    public function __construct() {
        $dbManager = new DatabaseManager();
        $this->notificationTransactions = new NotificationTransactions($dbManager);
        $this->projectTransactions =new ProjectTransactions($dbManager);
    }

    public function getNotificationsAction() {
        $userId = $_SESSION['user']->getId();
        $projectsIds = $this->projectTransactions->getRelevantProjectIds($userId);
        $notifications = $this->notificationTransactions->getNotificationsByUserId($userId, $projectsIds);
       
        header('Content-Type: application/json');
        echo json_encode(['notifications' => $notifications]);
        exit;
    }
    
    

   

    public function createStatusChangeNotification($userId,$taskId, $newStatus,$projectId) {
        $this->notificationTransactions->createTaskStatusNotification($userId,$taskId, $newStatus,$projectId);
    }

    public function createCommentNotification($taskId, $content, $userId, $projectId) {
        $this->notificationTransactions->createCommentNotification($taskId, $content, $userId, $projectId);
    }
    
    
    
    
    
}



