<?php

namespace App\Controller;

use App\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to your action: index(EntityManagerInterface $em)
        $em = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName('Product name '  .  microtime());
        $product->setPrice(19.99);
        $product->setDescription('Ergonomic and stylish '  .  microtime());

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new product with name '.$product->getName());
    }

    /**
     * @Route("/product/{id}", name="product_show")
     * @param Product $product
     * @return Response
     */
    public function showAction(Product $product)
    {
        /*$product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }*/

        return new Response('Check out this great product: '.$product->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
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
     * @Route("/product/edit/{id}")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $product->setName('Product name '  .  microtime());
        $em->flush();

        return $this->redirectToRoute('product_show', [
            'id' => $product->getId()
        ]);
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

        return new Response('Deleted product: id '.$id);
    }
}
