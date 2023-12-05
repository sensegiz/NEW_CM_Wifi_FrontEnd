<?php
$_FILES['file']['name'] = $_GET['filename'];

if (isset($_FILES['file']['name'])) {
    if (0 < $_FILES['file']['error']) {
        echo 'Error during file upload' . $_FILES['file']['error'];
    } else {
        
            move_uploaded_file($_FILES['file']['tmp_name'], 'daily_report/' . $_FILES['file']['name']);
            echo 'File successfully uploaded: '. $_FILES['file']['name'];
        
    }
} else {
    echo 'Please choose a file';
}


?>
