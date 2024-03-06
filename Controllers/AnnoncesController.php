<?php

namespace App\Controllers;

class AnnoncesController extends Controller
{
    public function index(){
        $donnees = ["a","b"];
        include_once ROOT."/Views/annonces/index.php";
    }
}