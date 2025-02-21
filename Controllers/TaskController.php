<?php

include_once 'Models/Task.php';
include_once 'Models/TaskTransactions.php';
include_once 'Models/UserTransactions.php';
include_once 'Models/ProjectTransactions.php';
include_once 'Models/DatabaseManager.php';
include_once 'Models/NotificationTransactions.php';
include_once 'Controllers/NotificationController.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class TaskController
{
    private $taskTransactions;
    private $userTransactions;
    private $projectTransaction;
    private $notificationTransactions;
    private $notificationController;

    public function __construct()
    {
        $dbManager = new DatabaseManager();
        $this->taskTransactions = new TaskTransactions($dbManager);
        $this->userTransactions = new UserTransactions($dbManager);
        $this->projectTransaction =new ProjectTransactions($dbManager);
        $this->notificationTransactions = new NotificationTransactions($dbManager);
        $this->notificationController = new NotificationController();
    }

    public function getAllTasksAction()
    {
        $tasks = $this->taskTransactions->getAllTasksWithProjectName();
        require_once 'Views/Tasks/listTasks.php';
    }

    
    public function getTaskByIdAction()
    {
        if (isset($_GET['id'])) {
            $taskId = $_GET['id'];
            $task = $this->taskTransactions->getTaskById($taskId);

            if ($task) {
                require_once 'Views/Tasks/viewTask.php';
            } else {
                header('Location: index.php?action=listTasks');
                exit();
            }
        } else {
            header('Location: index.php?action=listTasks');
            exit();
        }
    }

    // Action pour afficher la page d'ajout d'une tâche
    /*
    public function addTaskAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $projectId = $_POST['project_id'] ?? null;
            $name = $_POST['name'] ?? null;
            $description = $_POST['description'] ?? null;
            $startDate = $_POST['start_date'] ?? null;
            $plannedEndDate = $_POST['planned_end_date'] ?? null;
            $status = $_POST['status'] ?? 'En cours';
            $priority = $_POST['priority'] ?? 'Normal';
            $assigneeId = $_POST['assignee_id'] ?? null;

            if ($projectId && $name && $startDate && $plannedEndDate) {
                $task = new Task(null, $projectId, $name, $description, $startDate, $plannedEndDate, null, $status, $priority, $assigneeId);

                if ($this->taskTransactions->createTask($projectId,$name,$description)) {
                    header("Location: index.php?action=listTasks");
                    exit();
                } else {
                    echo "Erreur lors de l'ajout de la tâche.";
                }
            } else {
                echo "Veuillez remplir tous les champs obligatoires.";
            }
        } else {
            $users = $this->userTransactions->getAllUsers(); // Assurez-vous que cette méthode existe
            require_once 'Views/Tasks/addTask.php';
        }
    }
*/
    // Action pour afficher la page de modification d'une tâche
    public function editTaskAction()
    {
        if (isset($_GET['id'])) {
            $taskId = $_GET['id'];
            $project_id = $_GET['project_id'];
            $task = $this->taskTransactions->getTaskById($taskId);

            if ($task) {
                $users = $this->userTransactions->getAllMembers($project_id); // Assurez-vous que cette méthode existe
                $projects = $this->projectTransaction->getAllProjects();
               
                require_once 'Views/Tasks/editTask.php';
            } else {
                header('Location: index.php?action=listTasks');
                exit();
            }
        } else {
            header('Location: index.php?action=listTasks');
            exit();
        }
    }

    // Action pour mettre à jour une tâche
    public function updateTaskAction()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $projectId = $_POST['project_id'] ?? null;
        $name = $_POST['name'] ?? null;
        $description = $_POST['description'] ?? null;
        $startDate = $_POST['start_date'] ?? null;
        $plannedEndDate = $_POST['planned_end_date'] ?? null;
        $actualEndDate = $_POST['actual_end_date'] ?? null;
        $status = $_POST['status'] ?? null;
        $priority = $_POST['priority'] ?? null;
        $assigneeId = $_POST['member_id'] ?? null;

        if ($id && $projectId && $name && $startDate && $plannedEndDate) {
            // Récupérer l'ancien statut avant la mise à jour
            $taskBeforeUpdate = $this->taskTransactions->getTaskById($id);
            $oldStatus = $taskBeforeUpdate->getStatus() ?? null;

            // Effectuer la mise à jour
            if ($this->taskTransactions->updateTask($id, $projectId, $name, $description, $startDate, $plannedEndDate, $actualEndDate, $status, $priority, $assigneeId)) {
                // Si le statut a changé, envoyer une notification
                if ($oldStatus !== $status) {
                    include_once 'Controllers/NotificationController.php';
                    $notificationController = new NotificationController();
                    $notificationController->createStatusChangeNotification($id, $taskId,$status,$projectId);
                }
                
                header("Location: index.php?action=listTasks");
                exit();
            } else {
                echo "Erreur lors de la mise à jour de la tâche.";
            }
        } else {
            echo "Veuillez remplir tous les champs obligatoires.";
        }
    } else {
        header("Location: index.php?action=listTasks");
        exit();
    }
}
public function createTaskWithMemberAction() {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['projectId'], $data['userId'], $data['name'], $data['description'], $data['startDate'], $data['endDate'], $data['status'], $data['priority'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Données incomplètes.']);
        exit;
    }

    $success = $this->taskTransactions->createTaskWithMember(
        $data['projectId'],
        $data['userId'],
        $data['name'],
        $data['description'],
        $data['startDate'],
        $data['endDate'],
        $data['status'],
        $data['priority']
    );

    if ($success) {
        http_response_code(201);
        echo json_encode(['message' => 'Tâche créée avec succès.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la création de la tâche.']);
    }

    exit;
}

    // Action pour confirmer la suppression d'une tâche
    public function confirmDeleteTaskAction()
    {
        if (isset($_GET['id'])) {
            $taskId = $_GET['id'];
            $task = $this->taskTransactions->getTaskById($taskId);
            require_once 'Views/Tasks/confirmDelete.php';
        } else {
            header('Location: index.php?action=listTasks');
            exit();
        }
    }

    // Action pour supprimer une tâche
    public function deleteTaskByIdAction()
    {
        if (isset($_GET['id'])) {
            $taskId = $_GET['id'];
            if ($this->taskTransactions->deleteTaskById($taskId)) {
                header('Location: index.php?action=listTasks');
                exit();
            } else {
                echo "Erreur lors de la suppression de la tâche.";
            }
        } else {
            header('Location: index.php?action=listTasks');
            exit();
        }
    }


    public function seeProjectDetailsAction(){
       $projectId=$_GET['id'];
        if($_SESSION['role']=='Responsable de projet'){
            $tasks=$this->taskTransactions->getTasksByProjectId($projectId);
            $project=$this->projectTransaction->getProjectById($projectId);
            $_SESSION['project']=$project;
            $members = $this->projectTransaction->getGroupMembers($projectId);
            $progress = $this->taskTransactions->getProjectProgress($projectId);
            require_once 'Views/Tasks/tasksProject.php';
        }
        else{
            header('Location:index.php?action=login');
            exit();
        }
    
    
    
    
      } 
    
      public function getTasksForProjectAction()
      {
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              $userId = $_SESSION['user']->getId();
              $projectId = $_POST['project_id'];
              
              $tasks = $this->taskTransactions->getUserTasksByProject($userId, $projectId);
      
              echo json_encode($tasks);
              exit();
          }
    
}



public function updateStatusTaskAction() {
    try {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'error' => 'Non autorisé']);
            exit;
        }

        $taskId = $_POST['task_id'] ?? null;
        $newStatus = $_POST['status'] ?? null;

        if (!$taskId || !$newStatus) {
            echo json_encode(['success' => false, 'error' => 'Données manquantes']);
            exit;
        }

        $userId = $_SESSION['user']->getId();
        if (!$this->taskTransactions->verifyTaskOwnership($taskId, $userId)) {
            echo json_encode(['success' => false, 'error' => 'Non autorisé pour cette tâche']);
            exit;
        }
        $taskBeforeUpdate = $this->taskTransactions->getTaskById($taskId);
        $oldStatus = $taskBeforeUpdate->getStatus() ?? null;

        $projectData = $this->projectTransaction->getProjectByTaskId($taskId);
        
        if (!$projectData) {
            echo json_encode(['success' => false, 'error' => 'Projet non trouvé']);
            exit;
        }
        
       
        if ($this->taskTransactions->updateStatusTask($taskId, $newStatus)) {
            echo json_encode([
                'success' => true, 
                'project_id' => $projectData['project_id']
            ]);
          

            if ($oldStatus !== $newStatus) {
                $this->notificationController->createStatusChangeNotification($userId,$taskId,$newStatus, $projectData['project_id']);
            }
            
           
        } else {
            echo json_encode(['success' => false, 'error' => 'Échec de la mise à jour']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Erreur serveur: ' . $e->getMessage()]);
    }
    exit;
}
}