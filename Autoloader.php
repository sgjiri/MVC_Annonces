<?php
// Déclaration d'un espace de noms pour organiser le code, ici 'App'.
namespace App;

/**
 * Class Autoloader
 * Cette classe sert à charger automatiquement les classes nécessaires sans avoir à les inclure manuellement.
 */
class Autoloader
{
    /**
     * Enregistre l'autoloader.
     * Cette méthode statique ajoute notre méthode autoload à la pile d'autoloading PHP.
     */
    static function register()
    {
        // spl_autoload_register permet d'enregistrer plusieurs fonctions (ou méthodes statiques de classe)
        // qui seront exécutées par PHP lorsqu'une classe est utilisée et non déclarée.
        spl_autoload_register([
            __CLASS__, // Fait référence à la classe actuelle 'Autoloader'.
            "autoload" // Nom de la méthode qui sera automatiquement appelée pour charger les classes.
        ]);
    }

    /**
     * Charge une classe en utilisant son nom complet.
     * @param string $class Le nom complet de la classe à charger.
     */
    static function autoload($class)
    {
        // On récupère dans $class la totalité du nom de la classe avec son namespace.
        // Exemple: Si la classe est 'App\Client\Compte', $class contiendra 'App\Client\Compte'.

        // On retire 'App\' du début du nom de la classe (le namespace de base).
        $class = str_replace(__NAMESPACE__ . "\\", "", $class);

        // On remplace les backslashes (\) par des slashes (/) pour le chemin du fichier.
        // Ceci est nécessaire car les namespaces sont normalement séparés par des backslashes,
        // mais les chemins de fichier utilisent des slashes.
        $class = str_replace("\\", "/", $class);

        // On construit le chemin vers le fichier de classe en partant du répertoire actuel.
        $fichier = __DIR__ . "/" . $class . ".php";

        // On vérifie si le fichier de classe existe avant de l'inclure.
        // Cela évite des erreurs si on essaie de charger une classe qui n'existe pas.
        if(file_exists($fichier)){
            require_once $fichier; // On inclut le fichier de la classe si trouvé.
        }
        
    }
}