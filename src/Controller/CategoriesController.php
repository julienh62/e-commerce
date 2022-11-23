<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/{slug}', name: 'list')]
    public function list(Categories $category, ProductsRepository $productsRepository, Request $request): Response
    {
        //on va chercher le numéro de page dans l'url
        //getInt chercher un entier
        //par défaut page 1 si page pas trouvée
        $page = $request->query->getInt('page', 1);git add .
        //on va chercher la liste des produit de la categorie
        $products = $productsRepository->findProductsPaginated($page, $category->getSlug(), 2);
          // findProductsPaginated 3eme argument 2 est ici le nombre d'article que l'on souhite afficher par page
          //on peut le modifier pour choisir le nbre d'articles par page
         // dd($products);

        return $this->render('categories/list.html.twig', compact('category', 'products'));

       // OU BIEN
        // return $this->render('categories/list.html.twig', [
        // 'category'=> $category,
        // 'products'=> $products
        //]);

    }

}