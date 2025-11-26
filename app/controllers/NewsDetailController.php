<?php

class NewsDetailController {
    private $db;
    private $news;
    private $category;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->news = new News($this->db);
        $this->category = new Category($this->db);
    }
    
    public function detail($id_news) {
        // Get news detail
        $newsItem = $this->news->getById($id_news);
        
        // Check if news exists and is published
        if (!$newsItem || $newsItem['status'] != 'published') {
            $_SESSION['error'] = 'Berita tidak ditemukan atau belum dipublikasikan';
            header('Location: ' . BASE_URL);
            exit;
        }
        
        // Increment views
        $this->incrementViews($id_news);
        
        // Get related news (same category, exclude current, limit 3)
        $relatedNews = $this->news->getAllNews('published', $newsItem['id_category'], 'created_at', 'desc', '', '', 4);
        $relatedNews = array_filter($relatedNews, function($item) use ($id_news) {
            return $item['id_news'] != $id_news;
        });
        $relatedNews = array_slice($relatedNews, 0, 3);
        
        // Get all categories for navbar
        $categories = $this->category->getAll();
        
        require_once __DIR__ . '/../../views/frontend/news-detail.php';
    }
    
    private function incrementViews($id_news) {
        $query = "UPDATE news SET views = views + 1 WHERE id_news = :id_news";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_news', $id_news);
        $stmt->execute();
    }
}
