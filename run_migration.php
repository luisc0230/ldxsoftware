<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/models/Database.php';

$db = new Database();
$conn = $db->getConnection();

$sql = file_get_contents(__DIR__ . '/database/migrations/update_courses_schema.sql');

if ($conn->multi_query($sql)) {
    do {
        // Store first result set
        if ($result = $conn->store_result()) {
            $result->free();
        }
        // Check if there are more result sets
    } while ($conn->next_result());
    echo "Migration executed successfully.";
} else {
    echo "Error executing migration: " . $conn->error;
}
?>
