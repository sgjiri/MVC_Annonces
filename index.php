<?php
// On définit une constante contenant le dossier racine
define("ROOT", dirname(__DIR__)."/MVC_Annonces");

// On importe les namespaces nécessaires
use App\Autoloader;
use App\Core\Main;

// On importe l'Autoloader
require_once ROOT.'/Autoloader.php';
require_once './vendor/altorouter/altorouter/AltoRouter.php';
require_once './vendor/autoload.php';
Autoloader::register();

// On instancie Main
$app = new Main();

// On démarre l'application
$app->start();
