<?php
include('connectionForeman.php');

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];

    // Prepare the SQL query to fetch the file path from the database
    $query = "SELECT $type FROM _applications WHERE Id = ?";
    $stmt = $con->prepare($query);

    if ($stmt) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($filePath);
        $stmt->fetch();

        // Check if the file path was retrieved and if the file exists
        if ($stmt->num_rows > 0 && file_exists($filePath)) {
            // Set headers to download the file
            header('Content-Type: application/pdf'); // Adjust if the files might not always be PDFs
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            readfile($filePath);
        } else {
            echo "File not found in the database or file system.";
        }
    } else {
        echo "Query preparation failed: " . $con->error;
    }
} else {
    echo "Invalid request.";
}
?>
