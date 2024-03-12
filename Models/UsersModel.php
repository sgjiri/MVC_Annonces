<?php
namespace App\Models;

class UsersModel extends Model
{
    protected $id; // Identifiant de l'utilisateur
    protected $email; // Adresse e-mail de l'utilisateur
    protected $password; // Mot de passe de l'utilisateur
    protected $roles;
    

    public function __construct()
    {
        // Extraire le nom de classe de l'espace de noms et définir le nom de la table
        $class = str_replace(__NAMESPACE__ ."\\", "", __CLASS__);
        $this->table = strtolower(str_replace("Model","",$class));
    }

    /**
     * Récupérer un user par rapport de son e-mail 
     *
     * @param string $email
     * @return mixed
     */
    public function findOneByEmail(string $email)
    {
        return $this->runQuery("SELECT * FROM $this->table WHERE email = ?", [$email])->fetch();
    }

    /**
     * Cette méthode crée la Session utilisateur 
     *
     * @return void
     */
    public function setSession()
    {
        $_SESSION["user"] = [
            "id" => $this ->id,
            "email" => $this->email,
            "roles" => $this->roles
        ];
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

    /**
     * Get the value of roles
     */ 
    public function getRoles():array
    {
        $roles = $this->roles;
        $roles[] = "ROLE_USER";
        return array_unique($roles);
    }

    /**
     * Set the value of roles
     *
     * @return  self
     */ 
    public function setRoles($roles)
    {
        $this->roles = json_decode($roles);

        return $this;
    }
}