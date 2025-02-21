<?php 
include_once 'Models/User.php';
include_once 'Models/UserTransactions.php';
include_once 'Models/TaskTransactions.php';
include_once 'Models/ProjectTransactions.php';
include_once 'Models/DatabaseManager.php';
include_once 'Models/NotificationTransactions.php';
session_start();
Class UserController{


private $userTransactions;
private $taskTransactions;

private $projectTransactions;
private $notificationTransactions;

public function __construct(){
 $dbManager= new DatabaseManager() ;
    $this->userTransactions=new UserTransactions($dbManager);
    $this->taskTransactions=new TaskTransactions($dbManager);
    $this->projectTransactions=new ProjectTransactions($dbManager);
    $this->notificationTransactions = new NotificationTransactions($dbManager);

}

// UserController.php
public function loginAction()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];
       
        $user = $this->userTransactions->login($email, $password);
            if ($user) {
            $_SESSION['user'] = $user;
            $_SESSION['role']=$user->getRole();
            switch ($user->getRole()) {
                case 'Administrateur':
                    header('Location: index.php?action=dashboardAdmin');
                    exit();
            
                case 'Responsable de projet':
                    header('Location: index.php?action=dashboardResponsable');
                    exit();
            
                case 'Membre de l\'équipe':
                    header('Location: index.php?action=dashboardMembre');
                    exit();
            
                default:
      
                    header('Location: index.php?action=login');
                    exit();
            }
            
        } else {
            $_SESSION['status'] = "Identifiants incorrects.";
            header('Location: index.php?action=loginAdminError');
            exit();
        }
    
    }

    else{
        include 'Views/Users/loginAdmin.php';
    }
}



public function dashboardResponsableAction() {
    if ($_SESSION['role'] == 'Responsable de projet') {
        
        $user = $_SESSION['user'];
        $projects = $this->projectTransactions->getProjectsByResponsable($user->getId());
        $projectsInProgress = $this->projectTransactions->countProjectsInProgress($user->getId());
        $projectsCompleted = $this->projectTransactions->countProjectsCompleted($user->getId());
        $projectsOverdue = $this->projectTransactions->countProjectsOverdue($user->getId());
      
        $projectsWithProgress = [];
        foreach ($projects as $project) {
            $progress = $this->taskTransactions->getProjectProgress($project->getId());
            $projectsWithProgress[] = [
                'project' => $project,
                'progress' => $progress
            ];
        }

        include 'Views/Users/dashboardResponsable.php';
    } else {
        header('Location:index.php?action=login');
        exit();
    }
}



public function dashboardMembreAction() {
    if ($_SESSION['role'] == 'Membre de l\'équipe') {
        
        $user = $_SESSION['user'];
       
        $upcomingTasks = $this->taskTransactions->getUpcomingTasksCount($user->getId());
        $completedTasks = $this->taskTransactions->getCompletedTasksCount($user->getId());
        $overdueTasks = $this->taskTransactions->getOverdueTasksCount($user->getId());
        $tasks = $this->taskTransactions->getTasksByUserId($user->getId());
        $projects = $this->taskTransactions->getUserProjects($user->getId());

        include 'Views/Users/dashboardMembre.php';
    } else {
        header('Location:index.php?action=login');
        exit();
    }
}


public function dashboardAdminAction() {
 
    if (!isset($_SESSION['user']) || $_SESSION['role'] != 'Administrateur') {
        header('Location: index.php?action=login');
        exit();
    }

    $user = $_SESSION['user'];

    $nombreTasksEnCours = $this->taskTransactions->getNombreTasks();
    $totalTasks = $this->taskTransactions->getTotalTasks();
    $tasksByStatus = $this->taskTransactions->getTasksByStatus();
    $tasksByPriority = $this->taskTransactions->getTasksByPriority();
    $averageCompletionTime = $this->taskTransactions->getAverageCompletionTime();

    $totalProjects = $this->projectTransactions->getTotalProjects();
    $projectsByStatus = $this->projectTransactions->getProjectsByStatus();
    $projectsByManager = $this->projectTransactions->getProjectsByManager();
    $averageProjectDuration = $this->projectTransactions->getAverageProjectDuration();

    include 'Views/Users/dashboardAdmin.php';
}




public function getAllUsersAction(){
    if ($_SESSION['role'] != 'Administrateur') {
        header('Location: index.php?action=login');
        exit();
    }
    $users = $this->userTransactions->getAllUsers();
require_once 'Views/Users/listUsers.php';
}

   
public function editUserAction() {

    if ($_SESSION['role'] =='Administrateur') {
        

    if (isset($_GET['id'])) {
        $userId = $_GET['id'];
        $user = $this->userTransactions->getUserById($userId);

        if ($user) {
     
            include 'Views/Users/editUser.php';
        } else {
           
            header('Location: index.php?action=listUsers');
            exit();
        }
    } else {
        
        header('Location: index.php?action=listUsers');
        exit();
    }
 } else{
        header('Location: index.php?action=login');
        exit();
    
    }

}


public function updateUserAction() {

    if ($_SESSION['role'] != 'Administrateur') {
        header('Location: index.php?action=login');
        exit();
    }

    if (isset($_POST['id'], $_POST['name'], $_POST['email'], $_POST['role'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        $updateSuccess = $this->userTransactions->updateUser($id, $name, $email, $role);

        if ($updateSuccess) {
 
            header('Location: index.php?action=listUsers');
            exit();
        } else {
            // Si la mise à jour échoue, afficher un message d'erreur
            echo "Échec de la mise à jour de l'utilisateur.";
        }
    } else {
        // Si les données ne sont pas envoyées
        header('Location: index.php?action=listUsers');
        exit();
    }
}

public function deleteUserByIdAction(){

    if ($_SESSION['role'] != 'Administrateur') {
        header('Location: index.php?action=login');
        exit();
    }
    if (isset($_GET['id'])) {
        $userId = $_GET['id'];
        $isDeleted = $this->userTransactions->deleteUserById($userId);
        if($isDeleted){
            header('Location: index.php?action=listUsers');
            exit();
        }
        else {
           
            header('Location: index.php?action=listUsers');
            exit();
        }
    } else {
        
        header('Location: index.php?action=listUsers');
        exit();
    }
}



    

public function confirmDelete(){

    if ( $_SESSION['role'] != 'Administrateur') {
        header('Location: index.php?action=login');
        exit();
    }

    if (isset($_GET['id'])) {
        $userId = $_GET['id'];

    $user=$this->userTransactions->getUserById($userId);
    require_once 'Views/Users/confirmDelete.php';
}

}


public function addUserAction()
{
    if ($_SESSION['role'] != 'Administrateur') {
        header('Location: index.php?action=login');
        exit();
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $name = $_POST['name'] ?? null;
        $email = $_POST['email'] ?? null;
        $role = $_POST['role'] ?? null;
        $password = $_POST['password'] ?? null;
         if (!empty($name) && !empty($email) && !empty($role) && !empty($password)) {
  
            if ($this->userTransactions->addUser($name, $email, $role , $password)) {          
                 header("Location: index.php?action=listUsers");
                exit();
            } else {
                echo "Erreur lors de l'ajout de l'utilisateur.";
            }
        } else {
            echo "Veuillez remplir tous les champs.";
        }
    } else {
       
        include 'Views/Users/addUser.php';
        
    }
}







public function logoutAction(){
  
session_start();
session_unset();
session_destroy();
header('Location: index.php?action=login');
exit();


}



}