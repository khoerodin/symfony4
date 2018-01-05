<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findAll();

        return $this->render('products/index.html.twig', ['products' => $products]);

    }

    /**
     * @Route("/product/new", name="product_new")
     */
    public function newAction()
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();
        return $this->render('products/new.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/product/save", name="product_save")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveAction(Request $request)
    {
        $name = $request->request->get('name');
        $price = $request->request->get('price');
        $description = $request->request->get('description');
        $category = $request->request->get('category');

        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($category);

        $product = new Product();
        $product->setName($name);
        $product->setPrice($price);
        $product->setDescription($description);
        $product->setCategory($category);

        $em->persist($product);
        $em->flush();

        return $this->redirectToRoute('product');
    }

    /**
     * @Route("/product/view/{id}", name="product_show")
     * @param Product $product
     * @return Response
     */
    public function showAction(Product $product)
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();
        return $this->render('products/view.html.twig', ['product' => $product, 'categories' => $categories]);
    }

    /**
     * @Route("/product/update")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $id = $request->request->get('id');
        $name = $request->request->get('name');
        $price = $request->request->get('price');
        $description = $request->request->get('description');
        $category = $request->request->get('category');

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        $category = $em->getRepository(Category::class)->find($category);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $product->setName($name);
        $product->setPrice($price);
        $product->setDescription($description);
        $product->setCategory($category);

        $em->flush();

        return $this->redirectToRoute('product');
    }

    /**
     * @Route("/product/greather-than/{price}", name="product_greather_than")
     * @param $price
     * @return Response
     */
    public function greatherThanAction($price)
    {
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAllGreaterThanPrice($price);

        return $this->render('greather.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/product/delete/{id}")
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('product');
    }
}
