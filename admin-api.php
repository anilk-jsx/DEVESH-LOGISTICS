<?php
// Admin API for managing bookings and messages
require_once 'config/database.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

try {
    $conn = getDBConnection();
    
    if (!$conn) {
        throw new Exception("Database connection failed");
    }

    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    switch ($action) {
        case 'stats':
            getStats($conn);
            break;
            
        case 'bookings':
            getBookings($conn);
            break;
            
        case 'messages':
            getMessages($conn);
            break;
            
        case 'view_message':
            viewMessage($conn);
            break;
            
        case 'update_booking_status':
            updateBookingStatus($conn);
            break;
            
        case 'update_message_status':
            updateMessageStatus($conn);
            break;
            
        case 'delete_booking':
            deleteBooking($conn);
            break;
            
        case 'delete_message':
            deleteMessage($conn);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

function getStats($conn) {
    try {
        // Get booking statistics
        $bookingStats = $conn->query("
            SELECT 
                COUNT(*) as total_bookings,
                SUM(CASE WHEN booking_status = 'pending' THEN 1 ELSE 0 END) as pending_bookings,
                SUM(CASE WHEN booking_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
                SUM(CASE WHEN booking_status = 'completed' THEN 1 ELSE 0 END) as completed_bookings
            FROM bookings
        ")->fetch();
        
        // Get message statistics
        $messageStats = $conn->query("
            SELECT 
                COUNT(*) as total_messages,
                SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_messages,
                SUM(CASE WHEN status = 'read' THEN 1 ELSE 0 END) as read_messages,
                SUM(CASE WHEN status = 'replied' THEN 1 ELSE 0 END) as replied_messages
            FROM contact_messages
        ")->fetch();
        
        $stats = array_merge($bookingStats, $messageStats);
        
        echo json_encode([
            'success' => true,
            'stats' => $stats
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function getBookings($conn) {
    try {
        $stmt = $conn->query("
            SELECT * FROM bookings 
            ORDER BY created_at DESC
        ");
        
        $bookings = $stmt->fetchAll();
        
        echo json_encode([
            'success' => true,
            'bookings' => $bookings
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function getMessages($conn) {
    try {
        $stmt = $conn->query("
            SELECT * FROM contact_messages 
            ORDER BY created_at DESC
        ");
        
        $messages = $stmt->fetchAll();
        
        echo json_encode([
            'success' => true,
            'messages' => $messages
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function viewMessage($conn) {
    try {
        $id = $_GET['id'] ?? '';
        
        if (empty($id)) {
            throw new Exception("Message ID is required");
        }
        
        $stmt = $conn->prepare("SELECT * FROM contact_messages WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $message = $stmt->fetch();
        
        if (!$message) {
            throw new Exception("Message not found");
        }
        
        // Mark as read if it's new
        if ($message['status'] == 'new') {
            $updateStmt = $conn->prepare("UPDATE contact_messages SET status = 'read' WHERE id = :id");
            $updateStmt->bindParam(':id', $id);
            $updateStmt->execute();
            $message['status'] = 'read';
        }
        
        echo json_encode([
            'success' => true,
            'message' => $message
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function updateBookingStatus($conn) {
    try {
        $id = $_POST['id'] ?? '';
        $status = $_POST['status'] ?? '';
        
        if (empty($id) || empty($status)) {
            throw new Exception("ID and status are required");
        }
        
        $validStatuses = ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Invalid status");
        }
        
        $stmt = $conn->prepare("UPDATE bookings SET booking_status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Booking status updated successfully'
            ]);
        } else {
            throw new Exception("Failed to update booking status");
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function updateMessageStatus($conn) {
    try {
        $id = $_POST['id'] ?? '';
        $status = $_POST['status'] ?? '';
        
        if (empty($id) || empty($status)) {
            throw new Exception("ID and status are required");
        }
        
        $validStatuses = ['new', 'read', 'replied'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Invalid status");
        }
        
        $stmt = $conn->prepare("UPDATE contact_messages SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Message status updated successfully'
            ]);
        } else {
            throw new Exception("Failed to update message status");
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function deleteBooking($conn) {
    try {
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            throw new Exception("Booking ID is required");
        }
        
        $stmt = $conn->prepare("DELETE FROM bookings WHERE id = :id");
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Booking deleted successfully'
            ]);
        } else {
            throw new Exception("Failed to delete booking");
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function deleteMessage($conn) {
    try {
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            throw new Exception("Message ID is required");
        }
        
        $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = :id");
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Message deleted successfully'
            ]);
        } else {
            throw new Exception("Failed to delete message");
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>