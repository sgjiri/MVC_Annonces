<?php

namespace App\Models;

use App\Core\Db;

/**
 * La classe Model est une classe de base pour les modèles de données.
 * Elle hérite de la classe Db pour accéder à la base de données.
 */
class Model extends Db
{
    // Table de la base de données
    protected $table;
    // Instance de DB
    private $db;
    protected $id;


    /**
     * Récupère tous les enregistrements de la table.
     *
     * @return array Les enregistrements de la table.
     */
    public function findAll()
    {
        $query = $this->runQuery("SELECT * FROM " . $this->table);
        return $query->fetchAll();
    }

    /**
     * Récupère les enregistrements de la table correspondant aux critères spécifiés.
     *
     * @param array $criters Les critères de recherche.
     * @return array Les enregistrements de la table correspondant aux critères.
     */
    public function findBy(array $criters)
    {
        $champs = [];
        $valeurs = [];

        // En boucle pour éclater le tableau
        foreach ($criters as $champ => $valeur) {
            $champs[] = "$champ = ?";
            $valeurs[] = $valeur;
        }

        // On transforme le tableau champs en chaîne de caractères.
        $liste_champs = implode(" AND ", $champs);

        // Exécute la requête avec les critères spécifiés.
        return $this->runQuery("SELECT * FROM " . $this->table . " WHERE " . $liste_champs, $valeurs)->fetchAll();
    }

    /**
     * Récupère l'enregistrement de la table avec l'ID spécifié.
     *
     * @param integer $id L'ID de l'enregistrement à récupérer.
     * @return object|null L'enregistrement de la table avec l'ID spécifié, ou null s'il n'existe pas.
     */
    public function find(int $id)
    {
        return $this->runQuery("SELECT * FROM $this->table WHERE id = $id")->fetch();
    }

    /**
     * Crée un nouvel enregistrement dans la table.
     *
     * @param Model $model Le modèle contenant les données de l'enregistrement à créer.
     * @return object|null L'enregistrement créé, ou null en cas d'échec.
     */
    public function create()
    {
        $champs = [];
        $inter = [];
        $valeurs = [];

        // En boucle pour éclater le tableau
        foreach ($this as $champ => $valeur) {
            if ($valeur !== null && $champ != "db" && $champ != "table") {
                $champs[] = $champ;
                $inter[] = "?";
                $valeurs[] = $valeur;
            }
        }

        // On transforme le tableau champs en chaîne de caractères.
        $liste_champs = implode(" , ", $champs);
        $liste_inter = implode(" , ", $inter);

        // Exécute la requête d'insertion avec les valeurs spécifiées.
        return $this->runQuery("INSERT INTO " . $this->table . " (" . $liste_champs . ") VALUES (" . $liste_inter . ")", $valeurs);
    }

    /**
     * Met à jour l'enregistrement de la table avec l'ID spécifié.
     * @param Model $model Le modèle contenant les données de l'enregistrement à mettre à jour.
     * @return object|null L'enregistrement mis à jour, ou null en cas d'échec.
     */
    public function update()
    {
        $champs = [];
        $valeurs = [];

        // En boucle pour éclater le tableau
        foreach ($this as $champ => $valeur) {
            if ($valeur !== null && $champ != "db" && $champ != "table") {
                $champs[] = "$champ = ?";
                $valeurs[] = $valeur;
            }
        }

        $valeurs[] = $this->id;

        // On transforme le tableau champs en chaîne de caractères.
        $liste_champs = implode(" , ", $champs);

        // Exécute la requête de mise à jour avec les valeurs spécifiées.
        return $this->runQuery("UPDATE " . $this->table . " SET " . $liste_champs . "WHERE id = ?", $valeurs);
    }

    /**
     * Supprime l'enregistrement de la table avec l'ID spécifié.
     *
     * @param integer $id L'ID de l'enregistrement à supprimer.
     * @return object|null L'enregistrement supprimé, ou null en cas d'échec.
     */
    public function delete(int $id)
    {
        return $this->runQuery("DELETE FROM $this->table WHERE id=?", [$id]);
    }

    /**
     * Exécute une requête SQL.
     *
     * @param string $sql La requête SQL à exécuter.
     * @param array|null $attributs Les attributs à utiliser dans la requête préparée.
     * @return object Le résultat de la requête.
     */
    public function runQuery(string $sql, array $attributs = null)
    {
        // On récupère l'instance de DB
        $this->db = Db::getInstace();

        // On vérifie si on a des attributs.
        if ($attributs !== null) {
            // Requête préparée
            $query = $this->db->prepare($sql);
            $query->execute($attributs);
            return $query;
        } else {
            // Requête simple.
            return $this->db->query($sql);
        }
    }


public function hydrate($donnees)
{
        foreach($donnees as $key => $value){
            // On récupère le nom du setter correspondant à la clé (key)
            // titre -> setTitre
            $setter = "set".ucfirst($key);

            // On vérifie si le setter existe.
            if(method_exists($this, $setter)){
                // On appelle le setter.
                $this->$setter($value);
            }
        }

        return $this;
    }
}