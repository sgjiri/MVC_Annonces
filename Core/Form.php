<?php

namespace App\Core;

class Form
{
    private $formCode = "";

    /**
     * Génère le formulaire HTML. 
     *
     * @return string
     */
    public function createForme()
    {

        return $this->formCode;
    }


    /**
     * Valide si tous les champs proposés sont remplis. 
     *
     * @param array $form Tableau issue de formulaire. 
     * @param array $fields Tableau listant les champs obligatoires. 
     * @return bool
     */
    public static function validate(array $form, array $fields)
    {
        //On parcourt les fields avec un boucle foreach. 
        foreach ($fields as $field) {
            //Si le chant dans le formulaire est absent ou vide 
            if (!isset($form[$field]) || empty($form[$field])) {
                //On sort en retournant false 
                return false;
            }
        }
        return true;
    }

    /**
     * Ajoute les attributs envoyés à la balise 
     * @param array $attributs Tableau associatif. ["class" => "form-control", "required" => true] On va devoir parcourir ce tableau et pour chaque attribut, on va devoir le créer en intérieur 
     * @return string
     */

    private function addAttribut(array $attributs): string
    {
        //On va initialiser une chaîne de caractères 
        $str = "";
        //On liste les attributs courts. [Les attributs qui n'ont pas besoin d'une valeur, par exemple, required. ]
        $short = ['checked', 'disabled', 'readonly', 'multiple', 'required', 'autofocus', 'novalidate', 'formnovalidate'];
        //En boucle sur le tableau d'attributs. 
        foreach ($attributs as $attribut => $value) {
            //Si l'attribut est dans la liste des attributs courts. 
            if (in_array($attribut, $short) && $value == true) {
                $str .= " $attribut";
            } else {
                // On ajoute attribut = 'valeur'
                $str .= " $attribut = '$value'";
            }
        }
        return $str;
    }

    /**
     * Cette méthode va générer la balise d'ouverture du formulaire. 
     *
     * @param string $methode Méthode du formulaire Post ou get 
     * @param string $action Action du formulaire 
     * @param array $attributs Attributs. 
     * @return Form
     */
    public function startForm(string $methode = "post", string $action = "#", array $attributs = []): self
    {
        //On crée la balise form
        $this->formCode .= "<form action='$action' method='$methode'";
        //On ajoute les éventuels attributs. 
        $this->formCode .= $attributs ?  $this->addAttribut($attributs) . ">" : ">";
        return $this;
    }

    /**
     * Balises de fermeture des formulaires 
     *
     * @return Form
     */
    public function finForm(): self
    {
        $this->formCode .= "</form>";
        return $this;
    }


    /**
     * Ajout de label. 
     *
     * @param string $for
     * @param string $text
     * @param array $attributs
     * @return self
     */
    public function addLabelFor(string $for, string $text, array $attributs = []): self
    {
        //Ouvre la balise 
        $this->formCode .= "<label for='$for'";
        //On ajoute les attributs 
        $this->formCode .= $attributs ? $this->addAttribut($attributs) : "";
        //On ajoute le texte. 
        $this->formCode .= ">$text</label>";
        return $this;
    }

    /**
     * Ajoute input. 
     *
     * @param string $type
     * @param string $name
     * @param array $attributs
     * @return self
     */
    public function addImput(string $type, string $name, array $attributs = []): self
    {
        //On ouvre la balise.
        $this->formCode .= "<input type='$type' name='$name'";
        //On ajoute les attributs.
        $this->formCode .= $attributs ? $this->addAttribut($attributs) . ">" : ">";
        return $this;
    }
    public function addTextarea(string $name, string $value = "", array $attributs = []): self
    {
        //Ouvre la balise 
        $this->formCode .= "<textarea name='$name'";
        //On ajoute les attributs 
        $this->formCode .= $attributs ? $this->addAttribut($attributs) : "";
        //On ajoute le texte. 
        $this->formCode .= ">$value</textarea>";
        return $this;
    }
    public function addSelect(string $name, array $optinons, array $attributs = []): self
    {
        //On crée le select. 
        $this->formCode .= "<select name='$name'";
        //On ajoute les attributs. 
        $this->formCode .= $attributs ? $this->addAttribut($attributs) . ">" : ">";

        foreach ($optinons as $value => $text) {
            $this->formCode .= "<option value='$value'>$text</option>";
        }
        //On ferme la select. 
        $this->formCode .= "</select>";

        return $this;
    }

    public function addButton(string $text, $attributs = []): self
    {
        $this->formCode .= "<button ";
        $this->formCode .= $attributs ? $this->addAttribut($attributs) : "";
        $this->formCode .= ">$text</button>";

        return $this;
    }
}
