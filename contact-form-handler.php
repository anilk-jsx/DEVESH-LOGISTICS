<?php
// Contact form handler
require_once 'config/database.php';

header('Content-Type: application/json');

// Enable CORS if needed
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get database connection
        $conn = getDBConnection();
        
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        // Sanitize and validate input data
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Validation
        $errors = [];
        
        if (empty($name)) $errors[] = "Name is required";
        if (empty($email)) $errors[] = "Email is required";
        if (empty($subject)) $errors[] = "Subject is required";
        if (empty($message)) $errors[] = "Message is required";
        
        // Validate email format
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        
        // Validate message length
        if (!empty($message) && strlen($message) < 10) {
            $errors[] = "Message should be at least 10 characters long";
        }

        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'errors' => $errors
            ]);
            exit;
        }

        // Prepare SQL statement
        $sql = "INSERT INTO contact_messages (name, email, subject, message) 
                VALUES (:name, :email, :subject, :message)";
        
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);
        
        // Execute the statement
        if ($stmt->execute()) {
            $message_id = $conn->lastInsertId();
            
            // Success response
            echo json_encode([
                'success' => true,
                'message' => 'Your message has been sent successfully!',
                'message_id' => $message_id,
                'data' => [
                    'name' => $name,
                    'email' => $email,
                    'subject' => $subject
                ]
            ]);
            
            // Optional: Send auto-reply email
            // sendAutoReplyEmail($email, $name);
            
        } else {
            throw new Exception("Failed to save message");
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid request method'
    ]);
}

// Optional: Auto-reply email function
function sendAutoReplyEmail($email, $name) {
    $subject = "Thank you for contacting Devesh Logistics";
    $message = "
    <html>
    <head>
        <title>Thank you for your message</title>
    </head>
    <body>
        <h2>Thank you for contacting us!</h2>
        <p>Dear $name,</p>
        <p>We have received your message and will get back to you as soon as possible.</p>
        <p>Our team typically responds within 24 hours during business days.</p>
        <br>
        <p>Best regards,<br>Devesh Logistics Team</p>
        <p>Email: devesh_solutions@rediffmail.com</p>
        <p>Phone: +91-8018201966</p>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@deveshlogistics.com" . "\r\n";
    
    return mail($email, $subject, $message, $headers);
}
?>