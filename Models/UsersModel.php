<?php
namespace App\Models;
use App\Models\Model;

class UsersModel extends Model
{
    protected $id; // Identifiant de l'utilisateur
    protected $email; // Adresse e-mail de l'utilisateur
    protected $password; // Mot de passe de l'utilisateur

    public function __construct()
    {
        // Extraire le nom de classe de l'espace de noms et définir le nom de la table
        $class = str_replace(__NAMESPACE__ ."\\", "", __CLASS__);
        $this->table = strtolower(str_replace("Model","",$class));
    }

    /**
     * Obtient la valeur de l'identifiant
     * 
     * @return int|null
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Définit la valeur de l'identifiant
     *
     * @param int $id
     * @return self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Obtient la valeur de l'adresse e-mail
     * 
     * @return string|null
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Définit la valeur de l'adresse e-mail
     *
     * @param string $email
     * @return self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Obtient la valeur du mot de passe
     * 
     * @return string|null
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Définit la valeur du mot de passe
     *
     * @param string $password
     * @return self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}