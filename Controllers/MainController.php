<?php

namespace App\Controllers;

/**
 * Classe MainController
 * 
 * Cette classe est responsable de la gestion des principales actions de l'application.
 * Elle étend la classe de base Controller.
 */
class MainController extends Controller
{

    
    /**
     * Fonction index
     * 
     * Cette fonction est le point d'entrée de l'application.
     * Elle définit le modèle à utiliser comme "home" et affiche la vue main/index sans aucune donnée.
     * La vue rendue est nommée "home".
     *
     * @return void
     */
    public function index()
    {
        $this->twig->display('main/index.html.twig', [], 'home');
    }
}