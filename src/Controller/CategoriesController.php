<?php

namespace App\Controller;

use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/{slug}', name: 'list')]
    public function list(Categories $category): Response
    {
        //on va chercher la liste des produit de la categorie
        $products = $category->getProducts();

        return $this->render('categories/list.html.twig', compact('category', 'products'));

       // OU BIEN
        // return $this->render('categories/list.html.twig', [
        // 'category'=> $category,
        // 'products'=> $products
        //]);

    }

}