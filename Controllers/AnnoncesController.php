<?php

namespace App\Controllers;

use App\Core\Form;
use App\Models\AnnoncesModel;

class AnnoncesController extends Controller
{
    /**
     * Cette méthode affichera une page listant tous les annonces de la base de données. 
     *
     * @return void
     */
    public function index()
    {
        //On instancie le modèle correspondant à la table annonce.
        $annoncesModel = new AnnoncesModel;

        //On va chercher toutes les annonces 
        $annonces = $annoncesModel->findAll();
        //On génère la vu
        $this->twig->display("annonces/index.html.twig", compact("annonces"));
    }

    /**
     * Affiche une annonce 
     * @param int  
     * @return void
     */
    public function lire(int $id)
    {
        //On instancie le modèle. 
        $annoncesModel = new AnnoncesModel;
        $joins = [];
        $selectedColumns = 't.*';

        // Obtention des informations de liaison de la table "annonce"
        $annonceTableInfo = $annoncesModel->getTableInfo();
        var_dump($annonceTableInfo);

        // Parcours des colonnes de la table "annonce"
        foreach ($annonceTableInfo['columns'] as $column) {
            // Vérification si la colonne est une clé étrangère
            if ($column['key']) {
                var_dump("hello");
                // Obtention des informations de la table de jointure
                $joinTableName = $column['name'];
                $joinTableAlias = strtolower(substr($joinTableName, 0, -3));
                var_dump($joinTableAlias);
                $joinCondition = "t.{$column['name']} = {$joinTableAlias}.id";

                // Ajout de la jointure à la liste des jointures
                $joins[] = [
                    'table' => $joinTableName,
                    'alias' => $joinTableAlias,
                    'condition' => $joinCondition
                ];

                // Ajout des colonnes sélectionnées pour la table de jointure
                $selectedColumns .= ", {$joinTableAlias}.*";
            }
        }
        //On va chercher 1 annonce. 
        $annonce = $annoncesModel->findWithJoins($id, $joins, $selectedColumns);
        //On génère la vue 
        $this->twig->display('annonces/lire.html.twig', compact("annonce"));
    }


    /**
     * Cette méthode va me permettre d'ajouter une annonce. 
     *
     * @return void
     */
    public function add()
    {
        //On vérifie si l'utilisateur est connecté 
        if (isset($_SESSION["user"]) && !empty($_SESSION["user"]["id"])) {
            $formData = $this->getFormData(['titre', 'description']);
            $title = $formData['titre'];
            $description = $formData['description'];
            //L'utilisateur est connecté 
            //On vérifie si le formulaire est complet. 
            if (Form::validate($_POST, ["titre", "description"])) {
                //Le formulaire est complet. 
                //On se protège contre les failles XSS
                //On peut utiliser strip_tags, htmlentities, htmlspecialchars
                $title = strip_tags($_POST["titre"]);
                $description = strip_tags($_POST["description"]);
                //En instancie notre modèle. 
                $ad = new AnnoncesModel;
                $ad->setTitre($title)
                    ->setDescription($description)
                    ->setUsers_id($_SESSION["user"]["id"]);
                //On enregistre notre objet. 
                $ad->create();

                //On redirige.

                $this->setFlash("success", "Votre annonce a été enregistrée avec succès");
                header("Location: /PHP/MVC_Annonces");
                exit;
            } else {
                //Le formulaire n'est pas complet 
                if (!empty($_POST)) {
                    //Je stocke les informations de $_POST dans une autre variable session. Pour pouvoir Les récupérer plus tard, après la réactualisation de la page.
                    $_SESSION['form_data'] = $_POST;
                    //J'initialise la variable session pour stocker l'erreur en intérieur de cette variable 
                    $this->setFlash("error", "Le formulaire est incomplet");
                    header("Location: /PHP/MVC_Annonces/annonces/add");
                    exit;
                }
            }
            $form = new Form;
            $form->startForm("post", "#", ["class" => "form", "id" => "addAnnoce", "enctype" => "multipart/formdataSoup"])
                ->addLabelFor("titre", "Titre de l'annonce: ")
                ->addInput("text", "titre", ["class" => "form-control", "id" => "titre", "value" => $title])
                ->addLabelFor("description", "Description de l'annoce: ")
                ->addTextarea("description", $description, ["class" => "form-control", "id" => "description"])
                ->addLabelFor("image", "Image: ")
                ->addInput("file", "image", ["class" => "form-control", "id" => "image"])
                ->addButton("Ajouter", ["class" => "btn btn-primary"])
                ->endForm();
            $flashErrorMessage = $this->getFlash('error');
            $this->twig->display("annonces/add.html.twig", ["addAnnonceForm" => $form->createForm(), "flashErrorMessage" => $flashErrorMessage]);
        } else {
            //L'utilisateur n'est pas connecté 
            $flashErrorMessage = $this->setFlash("error", "Vous devez être connecté pour accéder à cette page");
            header("Location: /PHP/MVC_Annonces/users/login");
        }
    }

    /**
     * Modification d'une annonce 
     *
     * @param integer $id
     * @return void
     */
    public function edit(int $id)
    {
        //On vérifie si l'utilisateur est connecté 
        if (isset($_SESSION["user"]) && !empty($_SESSION["user"]["id"])) {
            //On va vérifier si l'annonce existe dans la base de données 
            //En instancie notre modèle
            $annoncesModel = new AnnoncesModel;
            ///On cherche l'annonce avec l'id $id.
            $annonce = $annoncesModel->find($id);
            //Existe pas, on ne retourne à l'accueil. 
            if (!$annonce) {

                http_response_code(404);
                $_SESSION['erreur'] = "L'annonce recherchée n'existe pas ";
                header("Location: /PHP/MVC_Annonces/annonces");
                exit;
            }

            //On vérifie si l'utilisateur est l'auteur de l'annonce 
            if ($annonce->users_id !== $_SESSION["user"]["id"]) {
                if (!in_array("ROLE_ADMIN", $_SESSION["user"]["roles"])) {
                    $_SESSION["erreur"]  = "Vous n'avez pas l'accès à cette page";
                    header("Location: /PHP/MVC_Annonces/annonces");
                    exit;
                }
            }

            //On traite le formulaire. 
            if (Form::validate($_POST, ["titre", "description"])) {
                //Contre les failles XSS 
                $title = strip_tags($_POST["titre"]);
                $description = strip_tags($_POST["description"]);

                //On stocke l'annonce 
                $adEdit = new AnnoncesModel;
                $adEdit->setId($annonce->id)
                    ->setTitre($title)
                    ->setDescription($description);

                //On met à jour l'annonce 
                $adEdit->update();

                //On redirige. 
                $_SESSION["message"] = "Votre annonce a été modifiée avec succès.";
                header("Location: /PHP/MVC_Annonces");
                exit;
            }

            $form = new Form;
            $form->startForm("post", "#", ["class" => "form", "id" => "addAnnoce"])
                ->addLabelFor("titre", "Titre de l'annonce: ")
                ->addInput("text", "titre", ["class" => "form-control", "id" => "titre", "value" => $annonce->titre])
                ->addLabelFor("description", "Description de l'annoce: ")
                ->addTextarea("description", $annonce->description, ["class" => "form-control", "id" => "description"])
                ->addButton("Ajouter", ["class" => "btn btn-primary"])
                ->endForm();
            //On envoie à la vue 
            $this->twig->display("annonces/edit", ["form" => $form->createForm()]);
        } else {
            //L'utilisateur n'est pas connecté 
            $_SESSION["erreur"] = "Vous devez être connecté pour accéder à cette page";
            header("Location: /PHP/MVC_Annonces/users/login");
            exit;
        }
    }
}
