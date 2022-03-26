<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\Product;
use App\Form\MatiereType;
use App\Form\ProductType;
use App\Repository\MatiereRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     * @Route("/edit/{id}", name="edit")
     */
    public function index(Request $request, EntityManagerInterface $manager, ProductRepository $repository, $id = null): Response
    {
        if ($id):
            $product = $repository->find($id);
        else:
            $product = new Product();
        endif;
        $products = $repository->findAll();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()):

            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('app_home');


        endif;


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'form' => $form->createView(),
            'products' => $products
        ]);
    }

    /**
     * @Route("/deleteProduct/{id}", name="deleteProduct")
     *
     */
    public function deleteProduct(Product $product, EntityManagerInterface $manager)
    {

        $manager->remove($product);
        $manager->flush();

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/matiere", name="matiere")
     * @Route("/matiereEdit/{id}", name="matiereEdit")
     *
     */
    public function matiere(Request $request, EntityManagerInterface $manager, MatiereRepository $repository, $id = null)
    {

        if ($id):
           $matiere=$repository->find($id);
        else:
            $matiere = new Matiere();
        endif;

            $matieres = $repository->findAll();

            $form = $this->createForm(MatiereType::class, $matiere);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()):

                $manager->persist($matiere);
                $manager->flush();
                return $this->redirectToRoute('matiere');

            endif;


            return $this->render('home/matiere.html.twig', [
                'form'=>$form->createView(),
                'matieres'=>$matieres
            ]);
        }

    /**
     * @Route("/deleteMatiere/{id}", name="deleteMatiere")
     *
     */
    public function deleteMatiere(EntityManagerInterface $manager,Matiere $matiere)
    {

        $manager->remove($matiere);
        $manager->flush();


        return $this->redirectToRoute('matiere');
    }


}
