<?php
// Déclaration de l'espace de noms où se situe le contrôleur
namespace App\Controllers;

// Importation de la classe Form située dans le répertoire App/Core
use App\Core\Form;
use App\Models\UsersModel;

// Déclaration de la classe UsersController qui étend la classe de base Controller
class UsersController extends Controller
{
    /**
     * Méthode permettant de gérer la connexion des utilisateurs.
     *
     * Cette méthode crée un formulaire de connexion et l'envoie à la vue.
     * Elle utilise la classe Form pour construire le formulaire.
     *
     * @return void Rien n'est retourné car la méthode gère l'affichage.
     */
    public function login()
    {
        $formData = $this->getFormData(['email', 'password']);
        $email = $formData['email'];
        $pass = $formData['password'];

        if (Form::validate($_POST, ["email", "password"])) {
            //Le formulaire est complet 
            //On va chercher dans la base de données l'utilisateur avec l'e-mail entré. 
            $userModel = new UsersModel;
            $userArray = $userModel->findOneByEmail(strip_tags($_POST["email"]));
            //Si l'utilisateur n'existe pas 
            if (!$userArray) {
                //Je stocke les informations de $_POST dans une autre variable session. Pour pouvoir Les récupérer plus tard, après la réactualisation de la page.
                $_SESSION['form_data'] = $_POST;
                $this->setFlash('error', "L'adresse email et/ou le mot de passe est incorrect");
                header("Location: /PHP/MVC_Annonces/users/login");
                exit;
            }
            //L'utilisateur existe 
            $user = $userModel->hydrate($userArray);
            //On vérifie si le mot de passe est correct. 
            if (password_verify($_POST["password"], $user->getPassword())) {
                //Mot de passe est bon. 
                $user->setSession();
                header("Location: /PHP/MVC_Annonces");
                exit;
            } else {
                //Je stocke les informations de $_POST dans une autre variable session. Pour pouvoir Les récupérer plus tard, après la réactualisation de la page.
                $_SESSION['form_data'] = $_POST;
                //Mauvaise mot de passe 
                $this->setFlash('error', "L'adresse email et/ou le mot de passe est incorrect");
                header("Location: /PHP/MVC_Annonces/users/login");
                exit;
            }
        } else {
            //Le formulaire n'est pas complet 
            if (!empty($_POST)) {
                //Je stocke les informations de $_POST dans une autre variable session. Pour pouvoir Les récupérer plus tard, après la réactualisation de la page.
                $_SESSION['form_data'] = $_POST;
                $this->setFlash("error", "Le formulaire est incomplet");
                header("Location: /PHP/MVC_Annonces/users/login");
                exit;
            }
        }

        // Création d'une nouvelle instance de la classe Form
        $form = new Form;

        // Construction du formulaire de connexion
        $form->startForm("post", "#", ["class" => "form", "id" => "formulaire"])
            ->addLabelFor("email", "E-mail :") // Ajout du label pour l'email
            ->addInput("email", "email", ["class" => "form-control", "id" => "email", "value" => $email]) // Création du champ email
            ->addLabelFor("pass", "Mot de passe: ") // Ajout du label pour le mot de passe
            ->addInput("password", "password", ["id" => "pass", "class" => "form-control", "value" => $pass]) // Création du champ mot de passe
            ->addButton("Me connecter", ["class" => "btn btn-primary"]) // Ajout du bouton de connexion
            ->endForm(); // Fin de la construction du formulaire

        // Envoi du formulaire à la vue à travers la méthode render héritée de la classe Controller
        $flashErrorMessage = $this->getFlash('error');
        $this->twig->display("users/login.html.twig", [
            "loginForm" => $form->createForm(),
            "flashErrorMessage" => $flashErrorMessage
        ]);
    }



    public function register()
    {
        
        // On vérifie si le formulaire est valide 
        if (Form::validate($_POST, ["email", "password"])) {
            //Le formulaire est valide. 
            $email = strip_tags($_POST["email"]);
            // Vérifie si l'email est valide, sinon ajoute une erreur.
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                //Je stocke les informations de $_POST dans une autre variable session. Pour pouvoir Les récupérer plus tard, après la réactualisation de la page.
                $_SESSION['form_data'] = $_POST;
                $this->setFlash('error', "L'adresse email est incorrecte");
                header("Location: /PHP/MVC_Annonces/users/register");
                exit;
            }
            $pass = $_POST['password'];
            // Expression régulière pour valider la complexité du mot de passe.
            $pattern = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/';
            // Vérifie si le mot de passe correspond à l'expression régulière.
            if (!preg_match($pattern, $pass)) {
                //Je stocke les informations de $_POST dans une autre variable session. Pour pouvoir Les récupérer plus tard, après la réactualisation de la page.
                $_SESSION['form_data'] = $_POST;
                $this->setFlash('error', "Le mot de passe doit contenir au moins une lettre majuscule, une minuscule, un chiffre, un caractère spécial et être d'au moins 8 caractères de longueur.");
                header("Location: /PHP/MVC_Annonces/users/register");
                exit;
            }
            $passConfirm = $_POST['passwordConfirm'];
            if ($pass != $passConfirm) {
                //Je stocke les informations de $_POST dans une autre variable session. Pour pouvoir Les récupérer plus tard, après la réactualisation de la page.
                $_SESSION['form_data'] = $_POST;
                $this->setFlash("error", "Les mots de passe sont différents");
                header("Location: /PHP/MVC_Annonces/users/register");
                exit;
            }
            //On chiffre le mot de passe. 
            $pass = password_hash($pass, PASSWORD_ARGON2ID);


            $user = new UsersModel;
            $user->hydrate(["email" => $email, "password" => $pass]);
            $user->create();
        } else {
            //Le formulaire n'est pas complet 
            if (!empty($_POST)) {
                //Je stocke les informations de $_POST dans une autre variable session. Pour pouvoir Les récupérer plus tard, après la réactualisation de la page.
                $_SESSION['form_data'] = $_POST;
                $this->setFlash("error", "Le formulaire est incomplet");
                header("Location: /PHP/MVC_Annonces/users/register");
                exit;
            }
            $email = isset($_POST["email"]) ? strip_tags($_POST["email"]) : "";
        }
        // Création d'une nouvelle instance de la classe Form
        $form = new Form;
        $formData = $this->getFormData(['email', 'password', 'passwordConfirm']);
        $email = $formData['email'];
        $pass = $formData['password'];
        $passConfirm = $formData['passwordConfirm'];
        $form->startForm()
            ->addLabelFor("email", "E-mail")
            ->addInput("email", "email", ["class" => "form-control", "id" => "email", "value" => $email]) // Création du champ email
            ->addLabelFor("pass", "Mot de passe")    
            ->addInput("password", "password", ["name" => "password", "id" => "pass", "class" => "form-control", "value" => $pass])
            ->addLabelFor("passConfirm", "Confirmation de mot de passe")
            ->addInput("password", "passwordConfirm", ["name" => "passwordConfirm", "id" => "passConfirm", "class" => "form-control", "value" => $passConfirm])
            ->addButton("M'inscrire", ["class" => "btn btn-primary"])
            ->endForm();

        // Correction de "createForme" en "createForm" pour générer le formulaire
        $flashErrorMessage = $this->getFlash("error");
        $this->twig->display("users/register.html.twig", ["registerForm" => $form->createForm(), "flashErrorMessage" => $flashErrorMessage]);
    }

    /**
     * Déconnecter l'utilisateur 
     *
     * @return exit 
     */
    public function logout()
    {
        unset($_SESSION["user"]);
        header("Location: /PHP/MVC_Annonces/");
        exit;
    }
}
