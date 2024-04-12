<?php

namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Classe abstraite Controller
 * Base pour tous les contrôleurs de l'application.
 * Fournit des fonctionnalités communes à tous les contrôleurs, telles que la gestion des vues avec Twig.
 */
abstract class Controller
{
    /**
     * @var FilesystemLoader Loader Twig pour charger les templates.
     */
    protected $loader;

    /**
     * @var Environment Environnement Twig pour rendre les templates.
     */
    protected $twig;

    /**
     * @var string Chemin de base utilisé pour la navigation dans l'application.
     */
    protected static $base = "/PHP/MVC_Annonces";

    /**
     * Constructeur de la classe Controller.
     * Initialise l'environnement Twig et configure les variables globales utilisées dans les templates.
     */
    public function __construct()
    {
        // Initialisation du loader Twig avec le chemin vers les templates
        $this->loader = new FilesystemLoader(ROOT . "/Views");

        // Création de l'environnement Twig
        $this->twig = new Environment($this->loader);

        // Ajout de la variable globale 'base' pour un accès facile dans les templates Twig
        $this->twig->addGlobal('base', self::$base);

        // Ajout des informations de session comme variable globale accessible dans les templates Twig
        $this->twig->addGlobal('session', $_SESSION);
    }

    /**
     * Définit un message flash dans la session, accessible pour une seule requête.
     *
     * @param string $key Clé sous laquelle le message est stocké.
     * @param string $message Contenu du message à stocker.
     */
    public function setFlash($key, $message)
    {
        $_SESSION['flash'][$key] = $message;
    }

    /**
     * Récupère et supprime un message flash de la session.
     *
     * @param string $key Clé du message flash à récupérer.
     * @return string|null Le message flash si disponible, sinon null.
     */
    public function getFlash($key)
    {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]); // Suppression du message après lecture pour éviter qu'il ne soit réaffiché
            return $message;
        }
        return null;
    }
    /**
     * Récupère et nettoie les données de formulaire à partir de la session.
     *
     * @param array $fields Les champs à récupérer de la session.
     * @return array Les données nettoyées des champs spécifiés.
     */
    public function getFormData(array $fields): array
    {
        $formData = [];
        foreach ($fields as $field) {
            // Utilisez strip_tags pour nettoyer les données et prévenir les risques XSS.
            $formData[$field] = isset($_SESSION['form_data'][$field]) ? strip_tags($_SESSION['form_data'][$field]) : "";
        }
        // Nettoyez les données de session après les avoir récupérées pour éviter des conflits futurs.
        unset($_SESSION['form_data']);

        return $formData;
    }
}
