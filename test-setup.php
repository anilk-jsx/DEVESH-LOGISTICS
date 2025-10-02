<?php
// Test file to check your setup
echo "<h2>Devesh Logistics - System Check</h2>";

// Check PHP version
echo "<h3>✅ PHP Information</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "PHP Extensions: <br>";

$required_extensions = ['pdo', 'pdo_mysql', 'mysqli'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext - Loaded<br>";
    } else {
        echo "❌ $ext - Not Loaded<br>";
    }
}

echo "<hr>";

// Test database connection
echo "<h3>🗄️ Database Connection Test</h3>";

try {
    // Try to include the database config
    if (file_exists('config/database.php')) {
        require_once 'config/database.php';
        echo "✅ Database config file found<br>";
        
        // Test connection
        $conn = getDBConnection();
        if ($conn) {
            echo "✅ Database connection successful!<br>";
            
            // Test if tables exist
            $stmt = $conn->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (in_array('bookings', $tables) && in_array('contact_messages', $tables)) {
                echo "✅ Required tables found: " . implode(', ', $tables) . "<br>";
                
                // Test sample queries
                $bookingCount = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch()['count'];
                $messageCount = $conn->query("SELECT COUNT(*) as count FROM contact_messages")->fetch()['count'];
                
                echo "📊 Current data:<br>";
                echo "- Bookings: $bookingCount<br>";
                echo "- Messages: $messageCount<br>";
                
            } else {
                echo "❌ Required tables not found. Please import the SQL file.<br>";
                echo "Found tables: " . implode(', ', $tables) . "<br>";
            }
            
        } else {
            echo "❌ Database connection failed<br>";
        }
        
    } else {
        echo "❌ Database config file not found. Please check config/database.php<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Check file permissions and structure
echo "<h3>📁 File Structure Check</h3>";

$required_files = [
    'config/database.php',
    'database/devesh_logistics.sql',
    'booking-handler.php',
    'contact-form-handler.php',
    'admin.html',
    'admin-api.php'
];

foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "✅ $file - Found<br>";
    } else {
        echo "❌ $file - Missing<br>";
    }
}

echo "<hr>";

// Show next steps
echo "<h3>🚀 Next Steps</h3>";
echo "<ol>";
echo "<li>If all checks are ✅, your system is ready!</li>";
echo "<li>Test booking form: <a href='book.html' target='_blank'>book.html</a></li>";
echo "<li>Test contact form: <a href='contact.html' target='_blank'>contact.html</a></li>";
echo "<li>Access admin panel: <a href='admin.html' target='_blank'>admin.html</a></li>";
echo "</ol>";

echo "<hr>";
echo "<p><strong>Server Information:</strong><br>";
echo "Server: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Current Time: " . date('Y-m-d H:i:s') . "</p>";
?>

<style>
body { 
    font-family: Arial, sans-serif; 
    margin: 20px; 
    background: #f5f5f5; 
}
h2 { 
    color: #f44336; 
    border-bottom: 2px solid #f44336; 
    padding-bottom: 10px; 
}
h3 { 
    color: #333; 
    margin-top: 20px; 
}
</style>