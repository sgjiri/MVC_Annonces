<?php
namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\AnnoncesModel;

class AdminController extends Controller
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {
        //On vérifie si l'utilisateur a le rôle admin. 
        if($this->isAdmin()){
            $this->twig->display("admin/index.html.twig", [], "admin");
        }
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function ad(){
        if($this->isAdmin()){
            $adsModel = new AnnoncesModel;
            $ads = $adsModel->findAll();
            
            $this->twig->display("admin/ad.html.twig", compact("ads"), "admin");
        }
    }


    /**
     * Cette méthode vérifie si on est admin 
     *
     * @return true
     */
    private function isAdmin()
    {
        //On vérifie si on est connecté et si le rôle est admin 
        if(isset($_SESSION["user"]) && in_array("ROLE_ADMIN", $_SESSION["user"]["roles"])){
            //Le rôle de l'utilisateur est admin  
            return true;
        }else{
            //Le rôle de l'utilisateur n'est pas admin. 
            $_SESSION["erreur"] = "Vous n'avez pas l'accès à cette partie de site";        
            header("Location: /PHP/MVC_Annonces/");
            return false;
        }
    }

    /**
     * Supprime une annonce si l'utilisateur a le rôle admin 
     *
     * @param [type] $id
     * @return void
     */
    public function deleteAd(int $id){
        if($this->isAdmin()){
            $ad = new AnnoncesModel;
            $ad->delete($id);
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
    }

    /**
     * Cette méthode active ou désactive une annonce 
     *
     * @param integer $id
     * @return void
     */
    public function activeAd(int $id){
        if($this->isAdmin()){
            $adModel = new AnnoncesModel;
            $adArray = $adModel->find($id);

            if($adArray){
                $ad = $adModel->hydrate($adArray);

                // if($annonce->getActif()){
                //     $annonce->setActif(0);
                // }else{
                //     $annonce->setActif(1);
                // }

                
                $ad -> setActif($ad->getActif()? 0 : 1);
                $ad->update();
            }
        }
    }
}