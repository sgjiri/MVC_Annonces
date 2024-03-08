<?php

namespace App\Controllers;

use App\Models\AnnoncesModel;

class AnnoncesController extends Controller
{
    /**
     * Cette méthode affichera une page listant tous les annonces de la base de données. 
     *
     * @return void
     */
    public function index(){
        //On instancie le modèle correspondant à la table annonce.
        $annoncesModel = new AnnoncesModel; 

        //On va chercher toutes les annonces 
        $annonces = $annoncesModel->findBy(['actif' => 1]);
        //On génère la vu
        $this->render('annonces/index', compact("annonces"));
    }

    /**
     * Affiche une annonce 
     * @param int  
     * @return void
     */
    public function lire(int $id){
        //On instancie le modèle. 
        $annoncesModel = new AnnoncesModel;
        //On va chercher 1 annonce. 
        $annonce = $annoncesModel->find($id);
        //On génère la vue 
        $this->render('annonces/lire', compact("annonce"));
        
    }
}