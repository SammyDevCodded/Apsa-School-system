<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once APP_PATH . '/Core/Router.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Route Test</title>
</head>
<body>
    <h1>Route Test</h1>
    <p>Testing if routes are properly configured.</p>";

// Simulate the router
$router = new App\Core\Router();

// Add a simple test route
$router->get('/test', function() {
    echo "<p>Test route executed successfully!</p>";
});

// Add the storage uploads route
$router->get('/storage/uploads/([^/]+)', function($filename) {
    echo "<p>Storage uploads route matched!</p>";
    echo "<p>Filename: " . htmlspecialchars($filename) . "</p>";
    
    $filePath = ROOT_PATH . '/storage/uploads/' . $filename;
    echo "<p>File path: " . htmlspecialchars($filePath) . "</p>";
    
    if (file_exists($filePath)) {
        echo "<p style='color: green;'>File exists!</p>";
        // Set appropriate content type
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp'
        ];
        
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        echo "<p>Content-Type: " . htmlspecialchars($mimeType) . "</p>";
    } else {
        echo "<p style='color: red;'>File does not exist!</p>";
    }
});

echo "<h2>Available Routes</h2>";
$routes = $router->getRoutes();
echo "<pre>";
print_r(array_keys($routes['GET']));
echo "</pre>";

echo "<h2>Testing Route Matching</h2>";
$testUri = '/storage/uploads/student_1760470904_68eea77842134.jpeg';

// Manual route matching (simplified version of what Router::route does)
$method = 'GET';
$uri = rtrim($testUri, '/');

if (strpos($uri, '/') !== 0) {
    $uri = '/' . $uri;
}

echo "<p>Testing URI: " . htmlspecialchars($uri) . "</p>";

$matched = false;
foreach ($routes[$method] as $routeUri => $route) {
    // Convert route pattern to regex
    $pattern = preg_replace('/\(([^\)]+)\)/', '([^/]+)', $routeUri);
    $pattern = '#^' . $pattern . '$#';
    
    echo "<p>Checking pattern: " . htmlspecialchars($pattern) . "</p>";
    
    if (preg_match($pattern, $uri, $matches)) {
        echo "<p style='color: green;'>Route matched!</p>";
        echo "<p>Matches: " . htmlspecialchars(print_r($matches, true)) . "</p>";
        
        // Remove the full match
        array_shift($matches);
        
        if (is_callable($route['callback'])) {
            echo "<p>Executing callback...</p>";
            try {
                call_user_func_array($route['callback'], $matches);
            } catch (Exception $e) {
                echo "<p style='color: red;'>Error executing callback: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
        $matched = true;
        break;
    }
}

if (!$matched) {
    echo "<p style='color: red;'>No route matched.</p>";
}

echo "</body>
</html>";
?>