<?php

namespace App\Core;

// On importe la classe PDO pour pouvoir l'utiliser
use PDO;
use PDOException;

class Db extends PDO
{
    // Instance unique de la classe
    private static $instance;

    // Informations de connexion à la base de données
    private const DBHOST = 'localhost'; // Adresse du serveur de la base de données
    private const DBUSER = 'root'; // Nom d'utilisateur pour se connecter à la base de données
    private const DBPASS = ''; // Mot de passe pour se connecter à la base de données
    private const DBNAME = 'poo-annonces'; // Nom de la base de données

    private function __construct()
    {
        // Définition de la chaîne de connexion à la base de données
        $_dsn = "mysql:dbname=" . self::DBNAME . ";host=" . self::DBHOST;

        // On appelle le constructeur de la classe PDO pour établir la connexion à la base de données
        try {
            parent::__construct($_dsn, self::DBUSER, self::DBPASS);

            // Configuration des attributs de la connexion PDO
            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8"); // Configuration du jeu de caractères
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Configuration du mode de récupération des données
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configuration du mode de gestion des erreurs

        } catch (PDOException $e) {
            // En cas d'erreur, on affiche le message d'erreur et on arrête l'exécution du script
            die($e->getMessage());
        }
    }

    // Méthode statique pour obtenir une instance unique de la classe Db
    public static function getInstace(): self
    {
        // Si l'instance n'a pas déjà été créée, on la crée
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}