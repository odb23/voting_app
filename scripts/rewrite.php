<?php
session_start();
$requestedUrl = $_GET['url'] ?? '';

$routes = [
    'vote/(\d{4})/(.+)\.php$' => 'vote.php',
];

// Find the matching route
$matchedRoute = '';
foreach ($routes as $route => $file) {
    $pattern = '#^' . $route . '$#';
    if (preg_match($pattern, $requestedUrl, $matches)) {
        $matchedRoute = $file;
        $year = $matches[1];
        $department = urldecode($matches[2]);
        break;
    }
}

// Load the corresponding view or controller
if ($matchedRoute) {
    require_once $matchedRoute;
} else {
    // Handle 404 Not Found
    require_once  '404.php';
}

?>
