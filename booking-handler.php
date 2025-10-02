<?php
// Booking form handler
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
        $pickup = trim($_POST['pickup'] ?? '');
        $drop = trim($_POST['drop'] ?? '');
        $pickup_date = $_POST['pickup_date'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $vehicle_type = $_POST['vehicle_type'] ?? '';
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // Validation
        $errors = [];
        
        if (empty($pickup)) $errors[] = "Pickup address is required";
        if (empty($drop)) $errors[] = "Drop address is required";
        if (empty($pickup_date)) $errors[] = "Pickup date is required";
        if (empty($phone)) $errors[] = "Phone number is required";
        if (empty($vehicle_type)) $errors[] = "Vehicle type is required";
        if (empty($fullname)) $errors[] = "Full name is required";
        if (empty($email)) $errors[] = "Email is required";
        
        // Validate email format
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        
        // Validate phone number (basic validation)
        if (!empty($phone) && !preg_match('/^[0-9]{10,15}$/', preg_replace('/[^0-9]/', '', $phone))) {
            $errors[] = "Invalid phone number format";
        }
        
        // Validate pickup date (should be today or future)
        if (!empty($pickup_date) && strtotime($pickup_date) < strtotime('today')) {
            $errors[] = "Pickup date cannot be in the past";
        }

        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'errors' => $errors
            ]);
            exit;
        }

        // Prepare SQL statement
        $sql = "INSERT INTO bookings (pickup_address, drop_address, pickup_date, phone, vehicle_type, fullname, email) 
                VALUES (:pickup, :drop, :pickup_date, :phone, :vehicle_type, :fullname, :email)";
        
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':pickup', $pickup);
        $stmt->bindParam(':drop', $drop);
        $stmt->bindParam(':pickup_date', $pickup_date);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':vehicle_type', $vehicle_type);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
        
        // Execute the statement
        if ($stmt->execute()) {
            $booking_id = $conn->lastInsertId();
            
            // Success response
            echo json_encode([
                'success' => true,
                'message' => 'Booking submitted successfully!',
                'booking_id' => $booking_id,
                'data' => [
                    'pickup' => $pickup,
                    'drop' => $drop,
                    'pickup_date' => $pickup_date,
                    'vehicle_type' => $vehicle_type,
                    'fullname' => $fullname,
                    'email' => $email
                ]
            ]);
            
            // Optional: Send confirmation email here
            // sendBookingConfirmationEmail($email, $fullname, $booking_id);
            
        } else {
            throw new Exception("Failed to save booking");
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

// Optional: Email confirmation function
function sendBookingConfirmationEmail($email, $name, $booking_id) {
    $subject = "Booking Confirmation - Devesh Logistics";
    $message = "
    <html>
    <head>
        <title>Booking Confirmation</title>
    </head>
    <body>
        <h2>Thank you for your booking!</h2>
        <p>Dear $name,</p>
        <p>Your booking has been received successfully.</p>
        <p><strong>Booking ID:</strong> $booking_id</p>
        <p>We will contact you shortly to confirm the details.</p>
        <br>
        <p>Best regards,<br>Devesh Logistics Team</p>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@deveshlogistics.com" . "\r\n";
    
    return mail($email, $subject, $message, $headers);
}
?>