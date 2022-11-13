<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/{slug}', name: 'list')]
    public function list(Categories $category, ProductsRepository $productsRepository): Response
    {
        //on va chercher la liste des produit de la categorie
        $products = $productsRepository->findProductsPaginated(1, $category->getSlug(), 2);

        return $this->render('categories/list.html.twig', compact('category', 'products'));

       // OU BIEN
        // return $this->render('categories/list.html.twig', [
        // 'category'=> $category,
        // 'products'=> $products
        //]);

    }

}