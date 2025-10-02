<?php
// MySQL Host Detection Tool
echo "<h2>🔍 Finding Your MySQL Host</h2>";

echo "<h3>1. Testing Common MySQL Hosts</h3>";

// Test different host configurations
$test_configs = [
    ['host' => 'localhost', 'description' => 'Standard local host'],
    ['host' => '127.0.0.1', 'description' => 'Local IP address'],
    ['host' => '::1', 'description' => 'IPv6 localhost'],
];

$your_password = '@SQlaN_3t7/;'; // Using the password from your config
$your_username = 'root';
$your_port = 3306;

foreach ($test_configs as $config) {
    echo "<div style='padding: 10px; margin: 5px; border: 1px solid #ddd; border-radius: 5px;'>";
    echo "<strong>Testing Host:</strong> {$config['host']} ({$config['description']})<br>";
    
    try {
        $dsn = "mysql:host={$config['host']};port={$your_port}";
        $pdo = new PDO($dsn, $your_username, $your_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "✅ <span style='color: green;'>SUCCESS!</span> This host works.<br>";
        
        // Get server info
        $stmt = $pdo->query("SELECT @@hostname as hostname, @@port as port, VERSION() as version");
        $info = $stmt->fetch();
        
        echo "📋 Server Details:<br>";
        echo "&nbsp;&nbsp;• MySQL Version: {$info['version']}<br>";
        echo "&nbsp;&nbsp;• Server Hostname: {$info['hostname']}<br>";
        echo "&nbsp;&nbsp;• Port: {$info['port']}<br>";
        
        // Check if devesh_logistics database exists
        try {
            $databases = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
            if (in_array('devesh_logistics', $databases)) {
                echo "✅ Database 'devesh_logistics' found!<br>";
            } else {
                echo "⚠️ Database 'devesh_logistics' not found. Available databases: " . implode(', ', $databases) . "<br>";
            }
        } catch (Exception $e) {
            echo "⚠️ Could not check databases: " . $e->getMessage() . "<br>";
        }
        
    } catch (PDOException $e) {
        echo "❌ <span style='color: red;'>FAILED:</span> " . $e->getMessage() . "<br>";
    }
    echo "</div>";
}

echo "<hr>";
echo "<h3>2. Your Current Configuration</h3>";
echo "<div style='background: #f0f8ff; padding: 15px; border-radius: 5px; border-left: 5px solid #2196F3;'>";
echo "<strong>From your config/database.php file:</strong><br>";
echo "• Host: <code>localhost</code><br>";
echo "• Username: <code>root</code><br>";
echo "• Password: <code>[hidden for security]</code><br>";
echo "• Port: <code>3306</code><br>";
echo "• Database: <code>devesh_logistics</code><br>";
echo "</div>";

echo "<h3>3. How to Determine MySQL Host</h3>";
echo "<div style='background: #f9f9f9; padding: 15px; border-radius: 5px;'>";
echo "<h4>For Local MySQL (your case):</h4>";
echo "• Use <code>localhost</code> or <code>127.0.0.1</code><br>";
echo "• This means MySQL is running on your computer<br><br>";

echo "<h4>For Remote MySQL:</h4>";
echo "• Use the server's IP address (e.g., <code>192.168.1.100</code>)<br>";
echo "• Or domain name (e.g., <code>mysql.example.com</code>)<br><br>";

echo "<h4>For Cloud MySQL:</h4>";
echo "• AWS RDS: <code>mydb.123456.us-east-1.rds.amazonaws.com</code><br>";
echo "• Google Cloud: <code>123.456.789.012</code><br>";
echo "• Azure: <code>myserver.mysql.database.azure.com</code><br>";
echo "</div>";

echo "<h3>4. Next Steps</h3>";
echo "<ol>";
echo "<li><strong>If connection succeeded above:</strong> Your host is correct - use <code>localhost</code></li>";
echo "<li><strong>Create database:</strong> Run the SQL file to create tables</li>";
echo "<li><strong>Install web server:</strong> You need Apache + PHP (XAMPP recommended)</li>";
echo "<li><strong>Test your forms:</strong> Try the booking and contact forms</li>";
echo "</ol>";

echo "<h3>5. How to Create Database and Tables</h3>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h4>Option A: Using phpMyAdmin (after installing XAMPP)</h4>";
echo "<ol>";
echo "<li>Install XAMPP and start Apache</li>";
echo "<li>Go to <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>";
echo "<li>Click 'SQL' tab</li>";
echo "<li>Copy contents of database/devesh_logistics.sql and paste</li>";
echo "<li>Click 'Go'</li>";
echo "</ol>";

echo "<h4>Option B: Using MySQL Command Line</h4>";
echo "<ol>";
echo "<li>Find your MySQL installation folder</li>";
echo "<li>Navigate to bin folder (e.g., C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\)</li>";
echo "<li>Run: <code>mysql.exe -u root -p</code></li>";
echo "<li>Enter your password: <code>@SQlaN_3t7/;</code></li>";
echo "<li>Run: <code>CREATE DATABASE devesh_logistics;</code></li>";
echo "<li>Run: <code>USE devesh_logistics;</code></li>";
echo "<li>Import SQL file: <code>source C:\\path\\to\\devesh_logistics.sql</code></li>";
echo "</ol>";
echo "</div>";

?>

<style>
body { 
    font-family: Arial, sans-serif; 
    margin: 20px; 
    background: #f9f9f9; 
    line-height: 1.6;
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
h4 {
    color: #555;
    margin-top: 15px;
}
code {
    background: #e8e8e8;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
}
</style>