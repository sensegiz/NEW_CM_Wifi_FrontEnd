<?php

$_FILES['file']['name'] = $argv[1];


if (isset($_FILES['file']['name'])) {
    if (0 < $_FILES['file']['error']) {
        	echo 'Error during file deletion';
    } else {
        
            unlink('/var/www/html/sensegiz-dev/sensegiz-mqtt/daily_report/' . $_FILES['file']['name']);
            echo 'File successfully deleted: '. $_FILES['file']['name'];
        
    }
} else {
    echo 'Please choose a file';
} 


?>