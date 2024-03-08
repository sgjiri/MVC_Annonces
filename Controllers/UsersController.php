<?php
namespace App\Controllers;

use App\Core\Form;

class UsersController extends Controller
{
    public function login()
    {
        $form = new Form;
        $form->startForm("get","login.php",["class" => "form", "id" => "formulaire" ])
                ->addLabelFor("email", "E-mail :")
                ->addImput("email", "email", ["class" => "form-control", "id"=>"email"])
                ->addLabelFor("pass", "Mot de passe: ")
                ->addImput("password", "password", ["id" => "pass", "class" => "form-control"])
                ->addButton("Me connecter", ["class" => "btn btn-primary"])
                ->finForm();
        $this->render("users/login",["loginForm" => $form->createForme()]);
    }
}