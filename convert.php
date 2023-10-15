<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["image"])) {
        $image = $_FILES["image"]["tmp_name"];

        // Check if the file is a valid image
        $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
        if (in_array(mime_content_type($image), $allowedTypes)) {
            $pdf = new Imagick();
            
            try {
                $pdf->readImage($image);
                $pdf->setImageFormat("pdf");
                $pdf->writeImages("output.pdf", true);
                
                // Provide the converted PDF for download
                header('Content-Type: application/pdf');
                header("Content-Disposition: attachment; filename=\"output.pdf\"");
                readfile("output.pdf");
                
                // Clean up by deleting the temporary PDF file
                unlink("output.pdf");
            } catch (Exception $e) {
                echo "Conversion error: " . $e->getMessage();
            }
        } else {
            echo "Invalid file type. Only JPEG, PNG, and GIF are allowed.";
        }
    } else {
        echo "No file was uploaded.";
    }
} else {
    echo "Form was not submitted.";
}
