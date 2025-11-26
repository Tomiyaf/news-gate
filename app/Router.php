<?php

class Router {
    private $routes = [];

    // Add route
    public function add($path, $controller, $method) {
        $this->routes[$path] = ['controller' => $controller, 'method' => $method];
    }

    // Dispatch route
    public function dispatch() {
        // Get route from query parameter
        $route = $_GET['route'] ?? '/';
        
        // Normalize route
        $route = '/' . ltrim($route, '/');
        
        // Extract base route and ID parameter
        $baseRoute = $route;
        $id = null;
        
        // Check for ID parameter in URL (e.g., /admin/categories/edit?id=1)
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        
        // Debug log
        error_log("[ROUTER] Route: " . $route . " | Base: " . $baseRoute . " | ID: " . ($id ?? 'null'));
        error_log("[ROUTER] Available routes: " . implode(', ', array_keys($this->routes)));
        
        // Check if route exists
        if (array_key_exists($baseRoute, $this->routes)) {
            $routeConfig = $this->routes[$baseRoute];
            error_log("[ROUTER] Found route - Controller: " . $routeConfig['controller'] . " Method: " . $routeConfig['method']);
            
            $controller = new $routeConfig['controller']();
            $method = $routeConfig['method'];
            
            // Call method with ID parameter if exists
            if ($id !== null) {
                $controller->$method($id);
            } else {
                $controller->$method();
            }
        } else {
            // 404 Not Found
            error_log("[ROUTER] 404 - Route not found: " . $route);
            http_response_code(404);
            echo "<h1>404 - Page Not Found</h1><p>Route: " . htmlspecialchars($route) . "</p>";
            echo "<p>Available routes:</p><ul>";
            foreach ($this->routes as $r => $config) {
                echo "<li>" . htmlspecialchars($r) . "</li>";
            }
            echo "</ul>";
        }
    }
}
