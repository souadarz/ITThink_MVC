<?php 
require_once (__DIR__.'/../models/User.php');
require_once (__DIR__.'/../models/Project.php');

class AdminController extends BaseController {
    private $UserModel ;
    private $ProjectModel;
    public function __construct(){

        $this->UserModel = new User();
        $this->ProjectModel = new project();
     }

   public function index() {
      
      if(!isset($_SESSION['user_loged_in_id'])){
         header("Location: /login ");
         exit;
      }
     $statistics =  $this->UserModel->getStatistics();
    $this->renderDashboard('admin/index', ["statistics" => $statistics]);
   }
   
   public function categories() {

    $this->renderDashboard('admin/categories');
   }
   public function testimonials() {
 
    $this->renderDashboard('admin/testimonials');
   }
   

   //===users========================

   public function handleUsers(){
    
    // Get filter and search values from GET
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all'; // Default to 'all' if no filter is selected
    $userToSearch = isset($_GET['userToSearch']) ? $_GET['userToSearch'] : ''; // Default to empty if no search term is provided
    // var_dump($userToSearch);die();

    // Call showUsers with both filter and search term
    $users = $this->UserModel->getAllUsers($filter, $userToSearch);
    $this->renderDashboard('admin/users',["users"=> $users]);
   }

    
    // check the post request to remove the user
    public function removeUser(){
      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_user'])) {
         $idUser = $_POST['remove_user'];
         $this->UserModel->removeUser($idUser);
         // Redirect to avoid form resubmission after page reload
         header("Location: /admin/users");
         exit();
      }
   }


    // check the post request to block the user
    public function changerStatus(){
      
      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['block_user_id'])) {
         $idUser = $_POST['block_user_id'];
         $this->UserModel->changeStatus($idUser);
         // Redirect to avoid form resubmission after page reload
         header("Location: /admin/users");
         exit();
    }
   }

    //=========projects====

    public function showProjects() {

      $filter_by_cat = isset($_GET['filter_by_cat']) ? $_GET['filter_by_cat'] : 'all';
      $filter_by_sub_cat = isset($_GET['filter_by_sub_cat']) ? $_GET['filter_by_sub_cat'] : 'all';
      $projectToSearch = isset($_GET['projectToSearch']) ? $_GET['projectToSearch'] : '';
      $filter_by_status = isset($_GET['filter_by_status']) ? $_GET['filter_by_status'] : '';

      $project = $this->ProjectModel->showProjects($filter_by_cat, $filter_by_sub_cat,$filter_by_status, $projectToSearch);
      $this->renderDashboard('admin/projects',["projects"=>$project]);

     }

     public function removePoject(){
     // check the post request to remove the user
     if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_project'])) {
         $idUser = $_POST['id_projet'];
         $this->ProjectModel->removeProject($idUser);
         // Redirect to avoid form resubmission after page reload
         if (strstr($_SERVER['REQUEST_URI'], "Client")) {
             header("Location: my_projects");
         }
         else 
         if (strstr($_SERVER['REQUEST_URI'], "Client")){
             header("Location: projects");
         }
         exit();
     }
   }

    // Add or modify project code 
   public function add_Modify_Project(){
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["save_project"])) {
            $project_title = trim($_POST["project_title_input"]);
            $project_description = trim($_POST["project_description_input"]);
            $project_category = isset($_POST["project_category_input"]) ? trim($_POST["project_category_input"]) : '';
            $project_subcategory = isset($_POST["project_subcategory_input"]) ? trim($_POST["project_subcategory_input"]) : '';
            $project_id = isset($_POST["project_id_input"]) ? trim($_POST["project_id_input"]) : 0;

            // Check if required fields are not empty
            if (!empty($project_title) && !empty($project_description) && !empty($project_category) && !empty($project_subcategory)) {
                  // Add new project if no ID provided
                  if ($project_id == 0) {
                     addProject();
                  }
                  // Modify existing project if ID is provided
                  else {
                     modifyProject();
                  }
               }
            else {
                echo "Please fill in all fields.";
            }
         }
      }
   }


}