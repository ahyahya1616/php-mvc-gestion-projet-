<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
include_once 'Models/DatabaseManager.php';
include_once 'Models/TaskTransactions.php';
include_once 'Models/ProjectTransactions.php';
include_once 'Models/Project.php';
include_once 'Models/Task.php';
include_once 'Models/User.php';
include_once 'Models/UserTransactions.php';
include_once 'Models/TaskTransactions.php';
include_once 'Controllers/UserController.php';
include_once 'Controllers/ProjectController.php';
include_once 'Controllers/TaskController.php';
include_once 'Controllers/CommentController.php';
include_once 'Controllers/NotificationController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'login';
$taskController = new TaskController();
$userController =new UserController();
$projectController = new ProjectController();
$commentController =new CommentController();
$notificationController =new NotificationController();
switch ($action) {
    case 'login':
    $userController->loginAction();

        break;

    case 'loginAdminError':
        include_once 'Views/Users/loginAdmin.php';
        break;   
    case 'dashboardAdmin':
         $userController->dashboardAdminAction(); 
        break;

    case 'dashboardResponsable':
          $userController->dashboardResponsableAction();
        break; 
        case 'dashboardMembre':
            $userController->dashboardMembreAction(); 
           break;    

    case 'listUsers':
       $userController->getAllUsersAction();
         break;

    case 'editUser':
           $userController->editUserAction(); 
               break;
            
    case 'updateUser':
      $userController->updateUserAction(); 
           break; 

    case 'confirmDelete':
             $userController->confirmDelete();
          break;   

    case 'deleteUser':
             $userController->deleteUserByIdAction();
            break;     
                  
             
                
     case 'addUser':              
      $userController->addUserAction();
          break;


     case 'listProjects':
      $projectController->getAllProjectsAction();
             break;  
            
    case 'editProject':
      $projectController->editProjectAction();  
             break;
                
    case 'updateProject':
        $projectController->updateProjectAction();   
          break;   

      case 'confirmDeleteProject':
         $projectController->confirmDeleteProjectAction();
         break;    
             
     case 'deleteProject':
                $projectController->deleteProjectByIdAction();
                break;   

    case 'createProject':
           $projectController->addProjectAction(); 
             break;  
              
    case 'listTasks':
        $taskController->getAllTasksAction();
            break;

    case 'editTask':
       $taskController->editTaskAction();
              break;   
    
    case 'updateTask':
          $taskController->updateTaskAction();
             break;
                
    case 'seeProject':
        $taskController->seeProjectDetailsAction();
        break;           
             
    case 'addComment':
        $commentController->addCommentAction();
        break;



        case 'getComments':
        $commentController->getCommentsAction();
            break;

   
    case 'assignUserToTask':
    $projectController->assignUserToTaskAction();
    break;
    case 'createTaskWithMember':
        $taskController->createTaskWithMemberAction();
        break;
    case 'getNotifications':
        $notificationController->getNotificationsAction();
        break;
        
     case 'logout':
        $userController->logoutAction();
        break;     
       
     case 'getTasksForProject':
        $taskController->getTasksForProjectAction();   
        break;

     
       
        
    
           
            case 'getNonProjectMembers':
                $projectController->getNonProjectMembersAction();
                break;
        
            case 'createTask':
                $taskController->createTaskWithMemberAction();
                break;
        
        case'updateStatusTask':
            $taskController->updateStatusTaskAction();
            break;
    default:
    $userController->loginAction();
        break;
}
?>
