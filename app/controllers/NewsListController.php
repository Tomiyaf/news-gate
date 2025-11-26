<?php

require_once __DIR__ . '/../models/News.php';
require_once __DIR__ . '/../models/Category.php';

class NewsListController {
    private $db;
    private $news;
    private $category;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->news = new News($this->db);
        $this->category = new Category($this->db);
    }

    // Display news listing page
    public function index() {
        // Get filter params
        $categoryId = $_GET['category'] ?? '';
        $search = $_GET['search'] ?? '';
        $sortBy = $_GET['sort'] ?? 'latest'; // latest, popular, trending
        
        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        // Get news with filters
        $newsItems = $this->news->getPublishedNews($categoryId, $search, $sortBy, $limit, $offset);
        
        // Get total for pagination
        $totalNews = $this->news->countPublishedNews($categoryId, $search);
        $totalPages = ceil($totalNews / $limit);
        
        // Get all categories for filter
        $categories = $this->category->getAll();
        
        // Get selected category info
        $selectedCategory = null;
        if (!empty($categoryId)) {
            $selectedCategory = $this->category->getById($categoryId);
        }
        
        require_once __DIR__ . '/../../views/frontend/news_list.php';
    }
}
