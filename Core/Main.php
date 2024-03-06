<?php

namespace App\Core;

use App\Controllers\MainController;

class Main
{
    /**
     * Router
     *
     * @return void
     */
    public function start()
    {

        //htpp://MVC_Annonces/controleur/methode/parametres
        //htpp://MVC_Annonces/annonces/details/brouette
        //htpp://MVC_Annonces/index.php?p=annonces/details/brouette

        //On retire, c'est ce qu'on appelle le trailing slash Hey cortana. éventuel de l'URL 
        // On récupère l'adresse
        $uri = $_SERVER['REQUEST_URI'];

        // On vérifie si elle n'est pas vide et si elle se termine par un /
        if (!empty($uri) && $uri != '/PHP/MVC_Annonces/' && $uri[-1] === '/') {
            // Dans ce cas on enlève le /
            $uri = substr($uri, 0, -1);

            // On envoie une redirection permanente
            http_response_code(301);

            // On redirige vers l'URL dans /
            header('Location: ' . $uri);
        }
        //On gère les paramètres d'URL 
        //p=controleur/methode/paramètres
        // On sépare les paramètres et on les met dans le tableau $params
        $params = explode('/', $_GET['p']);

        // Si au moins 1 paramètre existe
        if ($params[0] != "") {
            // On sauvegarde le 1er paramètre dans $controller en mettant sa 1ère lettre en majuscule, en ajoutant le namespace des controleurs et en ajoutant "Controller" à la fin
            $controller = '\\App\\Controllers\\' . ucfirst(array_shift($params)) . 'Controller';

            // On sauvegarde le 2ème paramètre dans $action si il existe, sinon index
            $action = isset($params[0]) ? array_shift($params) : 'index';

            // On instancie le contrôleur
            $controller = new $controller();

            if (method_exists($controller, $action)) {
                // Si il reste des paramètres, on appelle la méthode en envoyant les paramètres sinon on l'appelle "à vide"
                (isset($params[0])) ? $controller->$action($params) : $controller->$action();
            } else {
                // On envoie le code réponse 404
                http_response_code(404);
                echo "La page recherchée n'existe pas";
            }
        } else {
            // Ici aucun paramètre n'est défini
            // On instancie le contrôleur par défaut (page d'accueil)
            $controller = new MainController();

            // On appelle la méthode index
            $controller->index();
        }
    }
}
