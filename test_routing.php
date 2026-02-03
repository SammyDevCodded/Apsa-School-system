<?php
// Simple test script to verify routing is working

echo "Testing routing functionality...\n";

// Simulate a request to the root path
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['REQUEST_METHOD'] = 'GET';

// Include the router script
require_once 'public/router.php';

echo "Routing test completed.\n";
echo "If you see this message without errors, routing should be working.\n";