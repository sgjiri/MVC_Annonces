<?php
namespace App\Models;

class AnnoncesModel extends Model
{
    protected $id; // L'ID de l'annonce. 
    protected $titre; // Le titre de l'annonce.
    protected $description; // La description de l'annonce.
    protected $created_at; // La date et l'heure de création de l'annonce.
    protected $actif; // Indique si l'annonce est active ou non.

    public function __construct()
    {
        $this->table = "annonces"; 
        // Définit le nom de la table à "annonces" pour ce modèle.
    }

    /**
     * Récupère la valeur de l'ID
     */ 
    public function getId()
    {
        return $this->id;
        // Retourne l'ID de l'annonce.
    }

    /**
     * Définit la valeur de l'ID
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;
        // Définit l'ID de l'annonce.

        return $this;
    }

    /**
     * Récupère la valeur du titre
     */ 
    public function getTitre()
    {
        return $this->titre;
        // Retourne le titre de l'annonce.
    }

    /**
     * Définit la valeur du titre
     *
     * @return  self
     */ 
    public function setTitre($titre)
    {
        $this->titre = $titre;
        // Définit le titre de l'annonce.

        return $this;
    }

    /**
     * Récupère la valeur de la description
     */ 
    public function getDescription()
    {
        return $this->description;
        // Retourne la description de l'annonce.
    }

    /**
     * Définit la valeur de la description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;
        // Définit la description de l'annonce.

        return $this;
    }

    /**
     * Récupère la valeur de la date de création
     */ 
    public function getCreated_at()
    {
        return $this->created_at;
        // Retourne la date et l'heure de création de l'annonce.
    }

    /**
     * Définit la valeur de la date de création
     *
     * @return  self
     */ 
    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;
        // Définit la date et l'heure de création de l'annonce.

        return $this;
    }

    /**
     * Récupère la valeur de l'état actif
     */ 
    public function getActif()
    {
        return $this->actif;
        // Retourne si l'annonce est active ou non.
    }

    /**
     * Définit la valeur de l'état actif
     *
     * @return  self
     */ 
    public function setActif($actif)
    {
        $this->actif = $actif;
        // Définit si l'annonce est active ou non.

        return $this;
    }
}