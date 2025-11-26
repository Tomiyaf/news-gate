<?php

class HomeController {
    private $db;
    private $news;
    private $category;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->news = new News($this->db);
        $this->category = new Category($this->db);
    }
    
    public function index() {
        // Get hero news (highest views, published only)
        $heroNews = $this->news->getAllNews('published', '', 'views', 'desc', '', '', 1);
        $heroNews = !empty($heroNews) ? $heroNews[0] : null;
        
        // Get latest news (9 items for 3x3 grid)
        $latestNews = $this->news->getAllNews('published', '', 'created_at', 'desc', '', '', 9);
        
        // Get trending news for sidebar (top 5)
        $trendingNews = $this->news->getTrendingNews(5);
        
        // Get popular categories for sidebar
        $popularCategories = $this->news->getPopularCategories(5);
        
        // Get all categories for navbar
        $categories = $this->category->getAll();
        
        require_once __DIR__ . '/../../views/frontend/home.php';
    }
}
