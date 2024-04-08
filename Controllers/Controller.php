<?php
namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class Controller
{
    protected $loader;
    protected $twig;
    protected static $base = "/PHP/MVC_Annonces"; // Chemin de base de l'application

    public function __construct()
    {
        // Paramétrage du dossier contenant les templates
        $this->loader = new FilesystemLoader(ROOT . "/Views");
        
        // Paramétrage de l'environnement Twig
        $this->twig = new Environment($this->loader);

        // Ajouter la variable de base 'base' à toutes les vues Twig
        $this->twig->addGlobal('base', self::$base);

        // Ajoutez les informations de session comme variable globale à Twig
        $this->twig->addGlobal('session', $_SESSION);
    }
}