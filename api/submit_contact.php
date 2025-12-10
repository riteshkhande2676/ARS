<?php
/**
 * Contact Form Submission Handler
 * ARS ENGINEERS Solar Energy Website
 */

// Enable error reporting for development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to users
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

// Set JSON header
header('Content-Type: application/json');

// Allow CORS for development (remove or restrict in production)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Please use POST.'
    ]);
    exit;
}

// Include database configuration
require_once __DIR__ . '/../config/database.php';

try {
    // Get POST data
    $input = json_decode(file_get_contents('php://input'), true);

    // If JSON decode fails, try to get from $_POST
    if ($input === null) {
        $input = $_POST;
    }

    // Validate required fields
    $required_fields = ['name', 'email', 'phone', 'message'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty($input[$field])) {
            $errors[] = ucfirst($field) . ' is required';
        }
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ]);
        exit;
    }

    // Sanitize and validate input
    $name = trim(strip_tags($input['name']));
    $email = trim(strip_tags($input['email']));
    $phone = trim(strip_tags($input['phone']));
    $subject = isset($input['subject']) ? trim(strip_tags($input['subject'])) : '';
    $message = trim(strip_tags($input['message']));

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email address'
        ]);
        exit;
    }

    // Validate phone number (basic validation for Indian numbers)
    $phone_clean = preg_replace('/[^0-9]/', '', $phone);
    if (strlen($phone_clean) < 10 || strlen($phone_clean) > 15) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid phone number'
        ]);
        exit;
    }

    // Validate message length
    if (strlen($message) < 10) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Message must be at least 10 characters long'
        ]);
        exit;
    }

    // Get database connection
    $db = getDB();

    // Prepare SQL statement
    $sql = "INSERT INTO contact_messages (name, email, phone, subject, message, created_at) 
            VALUES (:name, :email, :phone, :subject, :message, NOW())";

    $stmt = $db->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone_clean, PDO::PARAM_STR);
    $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);

    // Execute query
    if ($stmt->execute()) {
        $message_id = $db->lastInsertId();

        // Optional: Send email notification to admin
        $to = 'arengineers24@gmail.com';
        $email_subject = 'New Contact Form Submission - ARS ENGINEERS';
        $email_body = "New message received from the website:\n\n";
        $email_body .= "Name: $name\n";
        $email_body .= "Email: $email\n";
        $email_body .= "Phone: $phone_clean\n";
        $email_body .= "Subject: $subject\n\n";
        $email_body .= "Message:\n$message\n\n";
        $email_body .= "---\n";
        $email_body .= "Received at: " . date('Y-m-d H:i:s') . "\n";

        $headers = "From: noreply@arsengineers.com\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Uncomment to enable email notifications
        // mail($to, $email_subject, $email_body, $headers);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Thank you for your message! We will get back to you soon.',
            'message_id' => $message_id
        ]);
    } else {
        throw new Exception('Failed to save message');
    }

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while saving your message. Please try again later.'
    ]);
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An unexpected error occurred. Please try again later.'
    ]);
}
?>