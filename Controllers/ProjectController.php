<?php

include_once 'Models/Project.php';
include_once 'Models/ProjectTransactions.php';
include_once 'Models/UserTransactions.php';
include_once 'Models/DatabaseManager.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


class ProjectController
{
    private $projectTransactions;
    private $userTransactions;

    public function __construct()
    {
        $dbManager = new DatabaseManager();
        $this->projectTransactions = new ProjectTransactions($dbManager);
        $this->userTransactions = new UserTransactions($dbManager);
    }

    // Action pour afficher tous les projets
    public function getAllProjectsAction()
    {
        if ($_SESSION['role'] != 'Administrateur') {
            header('Location: index.php?action=login');
            exit();
        }
        $projects = $this->projectTransactions->getAllProjectsWithManagers();
        require_once 'Views/Projects/listProjects.php';
    }

    // Action pour afficher un projet spécifique
    public function getProjectByIdAction()
    {
        if ($_SESSION['role'] != 'Administrateur') {
            header('Location: index.php?action=login');
            exit();
        }
        if (isset($_GET['id'])) {
            $projectId = $_GET['id'];
            $project = $this->projectTransactions->getProjectById($projectId);

            if ($project) {
                require_once 'Views/Projects/viewProject.php';
            } else {
                header('Location: index.php?action=listProjects');
                exit();
            }
        } else {
            header('Location: index.php?action=listProjects');
            exit();
        }
    }

    public function addProjectAction()
    {
        
        if ($_SESSION['role'] != 'Administrateur') {
            header('Location: index.php?action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? null;
            $description = $_POST['description'] ?? null;
            $startDate = $_POST['start_date'] ?? null;
            $endDate = $_POST['end_date'] ?? null;
            $status = $_POST['status'] ?? 'En cours';
            $managerId = $_POST['manager_id'] ?? null;

            if ($name && $startDate && $endDate && $managerId) {
          
                $project = new Project(null, $name, $description, $startDate, $endDate, $status, $managerId);
                if ($this->projectTransactions->createProject($project)) {
                          
                    header("Location: index.php?action=listProjects");
                    exit();
                } else {
                    echo "Erreur lors de l'ajout du projet.";
                }
            } else {
                echo "Veuillez remplir tous les champs obligatoires.";
            }
        } else {
            $managers = $this->userTransactions->getAllManagers(); 
            require_once 'Views/Projects/addProject.php';
        }
    }

    public function editProjectAction()
    {
        if ($_SESSION['role'] == 'Administrateur' || $_SESSION['role']=='Responsable de projet') {
            
        
        if (isset($_GET['id'])) {
            $projectId = $_GET['id'];
            $project = $this->projectTransactions->getProjectById($projectId);

            if ($project) {
                $managers = $this->userTransactions->getAllManagers(); 
                require_once 'Views/Projects/editProject.php';
            } else {
                header('Location: index.php?action=listProjects');
                exit();
            }
        } else {
            header('Location: index.php?action=listProjects');
            exit();
        }
    }else{
        header('Location: index.php?action=login');
            exit();
    }
    }

    // Action pour mettre à jour un projet
    public function updateProjectAction()
    {
        if ($_SESSION['role'] == 'Administrateur' || $_SESSION['role']=='Responsable de projet') {
            
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $name = $_POST['name'] ?? null;
            $description = $_POST['description'] ?? null;
            $startDate = $_POST['start_date'] ?? null;
            $endDate = $_POST['end_date'] ?? null;
            $status = $_POST['status'] ?? null;
            $managerId = $_POST['manager_id'] ?? null;

            if ($id && $name && $startDate && $endDate && $managerId) {
                if ($this->projectTransactions->updateProject($id, $name, $description, $startDate, $endDate, $status, $managerId)) {
                    if($_SESSION['role'] == 'Administrateur'){
                    header("Location: index.php?action=listProjects");
                    exit();
                    }
                    elseif($_SESSION['role'] == 'Responsable de projet'){
                        header("Location: index.php?action=dashboardResponsable");
                        exit();
                    }
                } else {
                    echo "Erreur lors de la mise à jour du projet.";
                }
            } else {
                echo "Veuillez remplir tous les champs obligatoires.";
            }
        } else {
            header("Location: index.php?action=listProjects");
            exit();
        }
    }else{
        header('Location: index.php?action=login');
            exit();
    }

}

    // Action pour confirmer la suppression d'un projet
    public function confirmDeleteProjectAction()
    {

        if ($_SESSION['role'] != 'Administrateur') {
            header('Location: index.php?action=login');
            exit();
        }

        if (isset($_GET['id'])) {
            $projectId = $_GET['id'];
            $project = $this->projectTransactions->getProjectById($projectId);
            require_once 'Views/Projects/confirmDelete.php';
        } else {
            header('Location: index.php?action=listProjects');
            exit();
        }
    }

    // Action pour supprimer un projet
    public function deleteProjectByIdAction()
    {

        if ($_SESSION['role'] != 'Administrateur') {
            header('Location: index.php?action=login');
            exit();
        }

        if (isset($_GET['id'])) {
            $projectId = $_GET['id'];
            if ($this->projectTransactions->deleteProjectById($projectId)) {
                header('Location: index.php?action=listProjects');
                exit();
            } else {
                echo "Erreur lors de la suppression du projet.";
            }
        } else {
            header('Location: index.php?action=listProjects');
            exit();
        }
    }





    public function getNonProjectMembersAction() {
        // Récupérer l'ID du projet depuis les paramètres GET
        $projectId = $_GET['projectId'] ?? null;
    
        if (!$projectId) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'ID de projet non spécifié.']);
            exit;
        }
    
        // Récupérer les membres non affectés à ce projet
        $members = $this->projectTransactions->getNonProjectMembers($projectId);
    
        // Retourner les membres sous forme JSON
        header('Content-Type: application/json');
        echo json_encode(['members' => $members]);
        exit;
    }
    
    
    public function assignUserToTaskAction() {
        $input = json_decode(file_get_contents('php://input'), true);
        $projectId = $input['projectId'];
        $userId = $input['userId'];
    
        $success = $this->projectTransactions->assignUserToTask($projectId, $userId);
    
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }
    

    



}
