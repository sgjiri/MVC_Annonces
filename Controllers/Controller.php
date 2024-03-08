<?php
namespace App\Controllers;

/**
 * Classe abstraite Controller
 * 
 * Cette classe est une classe de base abstraite pour les contrôleurs de l'application.
 * Elle fournit des fonctionnalités communes aux contrôleurs.
 */
abstract class Controller
{
    protected $template = "default";

    /**
     * Fonction render
     *
     * Cette fonction rend la vue spécifiée avec les données fournies.
     *
     * @param string $fichier Le nom du fichier de la vue à rendre
     * @param array $donnees Les données à transmettre à la vue (par défaut vide)
     * @return void
     */
    public function render(string $fichier, array $donnees = [])
    {
        // On extrait les données pour les rendre accessibles comme des variables distinctes
        extract($donnees);

        // On démarre la mise en tampon de sortie
        ob_start();

        // On inclut le fichier de la vue
        require_once ROOT."/Views/".$fichier.".php";

        // On récupère le contenu mis en tampon
        $contenu = ob_get_clean();

        // On inclut le template de page
        require_once ROOT."/Views/".$this->template.".php";
    }
}