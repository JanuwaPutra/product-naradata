<?php
// This script will save the uploaded Naradata logo to the public/images directory

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if file was uploaded without errors
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/';
        $uploadFile = $uploadDir . 'naradata-logo.png';
        
        // Save the uploaded file
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadFile)) {
            echo "Logo uploaded successfully and saved as naradata-logo.png";
        } else {
            echo "Error: Failed to save the uploaded file.";
        }
    } else {
        echo "Error: " . $_FILES['logo']['error'];
    }
} else {
    echo "Error: Invalid request method.";
}
?> 