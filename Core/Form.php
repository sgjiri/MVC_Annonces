<?php

namespace App\Core;

/**
 * Classe Form permettant de construire des formulaires HTML de manière dynamique et structurée.
 */
class Form
{
    /**
     * @var string Stocke le code HTML du formulaire en cours de construction.
     */
    private $formCode = "";

    /**
     * Retourne le formulaire généré.
     *
     * @return string Le code HTML du formulaire.
     */
    public function createForm()
    {
        // Retourne le code HTML généré pour le formulaire.
        return $this->formCode;
    }

    /**
     * Valide si les champs obligatoires du formulaire sont remplis.
     *
     * @param array $form Tableau contenant les données reçues du formulaire.
     * @param array $fields Tableau listant les champs à valider.
     * @return bool Retourne vrai si tous les champs sont remplis, faux sinon.
     */
    public static function validate(array $form, array $fields)
    {
        // Parcourt chaque champ requis.
        foreach ($fields as $field) {
            // Vérifie si le champ est absent ou vide dans les données du formulaire.
            if (!isset($form[$field]) || empty($form[$field])) {
                // Retourne faux si un champ est vide ou absent.
                return false;
            }
        }
        // Tous les champs sont remplis, retourne vrai.
        return true;
    }

    /**
     * Ajoute des attributs à une balise HTML.
     *
     * @param array $attributs Tableau associatif des attributs et de leurs valeurs.
     * @return string Chaîne de caractères représentant les attributs HTML.
     */
    private function addAttribut(array $attributs): string
    {
        // Initialise la chaîne des attributs.
        $str = "";
        // Liste des attributs qui ne nécessitent pas de valeur.
        $short = ['checked', 'disabled', 'readonly', 'multiple', 'required', 'autofocus', 'novalidate', 'formnovalidate'];
        // Parcourt le tableau d'attributs.
        foreach ($attributs as $attribut => $value) {
            // Ajout des attributs courts si nécessaire.
            if (in_array($attribut, $short) && $value == true) {
                $str .= " $attribut";
            } else {
                // Ajoute l'attribut et sa valeur.
                $str .= " $attribut=\"$value\"";
            }
        }
        // Retourne la chaîne des attributs.
        return $str;
    }

    /**
     * Commence la construction du formulaire en générant la balise ouvrante.
     *
     * @param string $methode La méthode HTTP du formulaire (post,get...).
     * @param string $action L'URL de traitement du formulaire.
     * @param array $attributs Les attributs supplémentaires du formulaire.
     * @return self Retourne l'instance courante pour chainer les méthodes.
     */
    public function startForm(string $methode = "post", string $action = "#", array $attributs = []): self
    {
        // Crée la balise form avec action et méthode.
        $this->formCode .= "<form action='$action' method='$methode'";
        // Ajoute les attributs à la balise form.
        $this->formCode .= $attributs ? $this->addAttribut($attributs) . '>' : '>';
        // Retourne l'instance courante pour permettre le chainage.
        return $this;
    }

    /**
     * Finalise la construction du formulaire en générant la balise fermante.
     *
     * @return self Retourne l'instance courante pour chainer les méthodes.
     */
    public function endForm(): self
    {
        $token = md5(uniqid());
        $this->formCode .="<input type='hidden' name = 'token' value='$token'>";
        // Ajoute la balise fermante du formulaire.
        $this->formCode .= "</form>";
        $_SESSION["token"] =$token;
        return $this;
    }

    /**
     * Ajoute un label pour un champ de formulaire.
     *
     * @param string $for L'attribut 'for' du label, qui doit correspondre à l'id du champ associé.
     * @param string $text Le texte du label à afficher.
     * @param array $attributs Les attributs supplémentaires du label.
     * @return self Retourne l'instance courante pour chainer les méthodes.
     */
    public function addLabelFor(string $for, string $text, array $attributs = []): self
    {
        // Ouvre la balise label et y ajoute l'attribut 'for'.
        $this->formCode .= "<label for='$for'";
        // Ajoute les attributs au label.
        $this->formCode .= $attributs ? $this->addAttribut($attributs) : "";
        // Ajoute le texte du label et ferme la balise.
        $this->formCode .= ">$text</label>";
        // Retourne l'instance courante pour permettre le chainage.
        return $this;
    }

    /**
     * Ajoute un champ input à la construction du formulaire.
     *
     * @param string $type Le type de l'input (text, email, password, etc.).
     * @param string $name Le nom de l'input qui correspondra à la clé dans le tableau $_POST ou $_GET.
     * @param array $attributs Les attributs supplémentaires de l'input.
     * @return self Retourne l'instance courante pour chainer les méthodes.
     */
    public function addInput(string $type, string $name, array $attributs = []): self
    {
        // Ouvre la balise input avec type et nom.
        $this->formCode .= "<input type='$type' name='$name'";
        // Ajoute les attributs à la balise input.
        $this->formCode .= $attributs ? $this->addAttribut($attributs) . ">" : ">";
        // Retourne l'instance courante pour permettre le chainage.
        return $this;
    }

    /**
     * Ajoute une zone de texte (textarea) au formulaire.
     *
     * @param string $name Le nom du textarea qui correspondra à la clé dans le tableau $_POST ou $_GET.
     * @param string $value La valeur par défaut du textarea.
     * @param array $attributs Les attributs supplémentaires du textarea.
     * @return self Retourne l'instance courante pour chainer les méthodes.
     */
    public function addTextarea(string $name, string $value = "", array $attributs = []): self
    {
        // Ouvre la balise textarea avec son nom.
        $this->formCode .= "<textarea name='$name'";
        // Ajoute les attributs au textarea.
        $this->formCode .= $attributs ? $this->addAttribut($attributs) : "";
        // Ferme la balise ouvrante et ajoute la valeur par défaut puis ferme le textarea.
        $this->formCode .= ">$value</textarea>";
        // Retourne l'instance courante pour permettre le chainage.
        return $this;
    }

    /**
     * Ajoute un menu déroulant (select) au formulaire.
     *
     * @param string $name Le nom du select qui correspondra à la clé dans le tableau $_POST ou $_GET.
     * @param array $options Tableau associatif des options de la liste déroulante, où la clé est la valeur de l'option et la valeur est le texte à afficher.
     * @param array $attributs Les attributs supplémentaires du select.
     * @return self Retourne l'instance courante pour chainer les méthodes.
     */
    public function addSelect(string $name, array $options, array $attributs = []): self
    {
        // Ouvre la balise select avec son nom.
        $this->formCode .= "<select name='$name'";
        // Ajoute les attributs au select.
        $this->formCode .= $attributs ? $this->addAttribut($attributs) . ">" : ">";
        // Parcourt les options du select et les ajoute.
        foreach ($options as $value => $text) {
            $this->formCode .= "<option value=\"$value\">$text</option>";
        }
        // Ferme la balise select.
        $this->formCode .= "</select>";
        // Retourne l'instance courante pour permettre le chainage.
        return $this;
    }

    /**
     * Ajoute un bouton au formulaire.
     *
     * @param string $text Le texte à afficher sur le bouton.
     * @param array $attributs Les attributs supplémentaires du bouton.
     * @return self Retourne l'instance courante pour chainer les méthodes.
     */
    public function addButton(string $text, $attributs = []): self
    {
        // Ouvre la balise button.
        $this->formCode .= "<button ";
        // Ajoute les attributs au bouton.
        $this->formCode .= $attributs ? $this->addAttribut($attributs) : "";
        // Ajoute le texte et ferme la balise button.
        $this->formCode .= ">$text</button>";
        return $this;
    }
}