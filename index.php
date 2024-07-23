<?php
session_start();

// Verificar autenticación del usuario
if (!isset($_SESSION['user_id'])) {
    header("Location: Views/login.php");
    exit;
}
#### Controladores ####

include "Controllers/template_Controller.php";
include "Controllers/EmployeeController.php";
include "Controllers/ClientsController.php";
include "Controllers/ProvidersController.php";
include "Controllers/FileController.php";
include "Controllers/ContacsClientsController.php";

#### Models ####

include "Models/BLemployee.php";
include "Models/BLproviders.php";
include "Models/BLclients.php";
include "Models/BLfiles.php";
include "Models/BLcontacsClients.php";

$template = new ControllerTemplate;
$template->controllerTemplate();
