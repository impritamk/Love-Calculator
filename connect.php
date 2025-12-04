<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database configuration - InfinityFree
$db_server = 'sql111.infinityfree.com';
$db_username = 'if0_38309488';
$db_password = '4dA6OJJMzp'; // **KEEP THIS CONFIDENTIAL!**
$db_name = 'if0_38309488_pritam';
$table_name = 'pritam';


try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get JSON data from request
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    // Validate data
    if (!isset($data['name1']) || !isset($data['name2']) || !isset($data['relationship']) || !isset($data['percentage'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Prepare SQL statement
    $sql = "INSERT INTO flames_results (name1, name2, relationship, percentage, created_at) 
            VALUES (:name1, :name2, :relationship, :percentage, NOW())";
    
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':name1', $data['name1'], PDO::PARAM_STR);
    $stmt->bindParam(':name2', $data['name2'], PDO::PARAM_STR);
    $stmt->bindParam(':relationship', $data['relationship'], PDO::PARAM_STR);
    $stmt->bindParam(':percentage', $data['percentage'], PDO::PARAM_INT);
    
    // Execute query
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true, 
            'message' => 'Data saved successfully',
            'id' => $pdo->lastInsertId()
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save data']);
    }
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
