<?php
// src/Controller/HomeController.php
namespace App\Controller;

use App\Entity\Book;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $searchTerm = $request->query->get('q', '');

        if ($searchTerm) {
            $books = $this->entityManager->getRepository(Book::class)->findByTitleOrAuthor($searchTerm);
        } else {
            $books = $this->entityManager->getRepository(Book::class)->findAll();
        }

        $categories = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('home.html.twig', [
            'categories' => $categories,
            'books' => $books,
            'searchTerm' => $searchTerm,
        ]);
    }
}