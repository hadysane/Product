<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/product')]
class ProductController extends AbstractController
{
    private $_em;

    public function __construct(ManagerRegistry $registry)
    {
        $this->_em = $registry;
    }



    #[Route('/', name: 'product_index', methods:['GET'])]
    public function index(ProductRepository $productRepository): Response
    {

        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

    /**
    * @Route("/new", name="product_new", methods={"GET", "POST"})
    */
    public function createPost(Request $request){
        $product = new Product();

        // $form = $this->createFormBuilder($product)
        //     ->add('name', TextType::class)
        //     ->add('description', TextType::class)
        //     ->add('prix', IntegerType::class)
        //     ->add('save', SubmitType::class, ['label' => 'Create product'])
        //     ->getForm();

        $form = $this->createForm(ProductType::class, $product); 

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->_em->getManager();

            $entityManager->persist($product);
            $entityManager->flush();
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/{id}/edit", name="product_edit", methods={"GET","POST"})
     * 
     */
    public function editProduct(Request $request, Product $product){
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->_em->getManager()->flush();
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/{id}", name="product_delete", methods={"POST"} )
     */
    public function delete(Request $request, Product $product)
    {

        //permet de valider le token pour ne pas confondre avec le show product 
        $this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'));
        $entityManager = $this->_em->getManager();
        $entityManager->remove($product);
        $entityManager->flush();
        return $this->redirectToRoute('product_index');
    }
}
