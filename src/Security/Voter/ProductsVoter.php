<?php

namespace App\Security\Voter

use App\Entity\Products;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductsVoter extends Voter
// Voter reclame l'voterinterface 
//il y a des méthodes obligatoires à utiliser
// ici supports() et voteonattribute()
{
    const EDIT = 'PRODUCT_EDIT';
    const DELETE = 'PRODUCT_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    protected function supports(string $attribute, $product):bool
    {
        //on verifie si l'attribut n'est pas dans un tableau correspond aux differents parametres
        //si l'attribut n'est pas dans classes EDIT et DELETE , on retourne false
        // ça veut dire , on n'a pas envoyé le bon attriubut (soit PRODUCT_DELETE soit PRODUCT_EDIT)
        if(!in_array($attribute, [self::EDIT, self::DELETE])){
            return false;
        }
        //on verifie si $product est une instance de Products
        if(!$product instanceof Products){
            return false;       
        }
        return true;

       // return in_array($attribute, [self::edit, self::delete]) && $product instanceof Products
    }
    protected function voteOnAttribute($attribute, $product, TokenInterface):bool
    // jeton avec tokeninterface pour verifier l'utilisateur qui veut accéder à la page
    {
        //on recupere l'utilisateur a partir du token
        $user = $token->getUser();

        // on verifie si l'utilisateur est une instance de user interface
        if(!user instanceof UserInterface){
            return false;
        }

        //on verifie si l'utilisateur est admin
    }
}

  
