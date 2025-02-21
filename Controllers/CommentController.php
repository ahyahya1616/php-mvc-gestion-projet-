<?php
include_once 'Models/DatabaseManager.php';
include_once 'Models/CommentTransactions.php';
include_once 'Controllers/NotificationController.php';
include_once 'Models/CommentTransactions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class CommentController {
    private $commentTransactions;
    private $notificationController;
    private $taskTransactions;

    public function __construct() {
        $dbManager = new DatabaseManager();
        $this->commentTransactions = new CommentTransactions($dbManager);
        $this->notificationController = new NotificationController();
        $this->taskTransactions = new TaskTransactions($dbManager);
    }

    public function addCommentAction() {
        try {
            $taskId = $_POST['taskId'] ?? null;
            $content = $_POST['content'] ?? null;
            $userId = $_SESSION['user']->getId();
            
            if (!$taskId || !$content) {
                echo json_encode(['success' => false, 'message' => 'Données manquantes']);
                exit;
            }
        
            // Ajouter le commentaire
            $success = $this->commentTransactions->addComment($taskId, $userId, $content);
        
            if ($success) {
                // Récupérer la tâche et le projet
                $task = $this->taskTransactions->getTaskById($taskId);
                if ($task) {
                    $projectId = $task->getProjectId();
                    
                    // Créer la notification
                    $this->notificationController->createCommentNotification(
                        $taskId,
                        $content,
                        $userId,
                        $projectId
                    );
                    
                    echo json_encode(['success' => true]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => "Erreur lors de l'ajout du commentaire"]);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Une erreur est survenue']);
        }
        exit;
    }
      public function getCommentsAction() {
        include_once 'utils.php';
        header('Content-Type: application/json; charset=utf-8');
       
        try {
            $taskId = $_GET['taskId'] ?? null;

            if (!$taskId) {
                echo json_encode(['success' => false, 'message' => 'Task ID manquant']);
                exit;
            }

            $comments = $this->commentTransactions->getCommentsByTaskId($taskId);
            foreach ($comments as &$comment) {
                if (isset($comment['created_at'])) {
                    $comment['time_ago'] = timeAgo($comment['created_at']);
                }
            }

            echo json_encode(['success' => true, 'comments' => $comments]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }

        exit;
    }
}