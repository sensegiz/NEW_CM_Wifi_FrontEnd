<?php
$base_directory = '/var/www/html/sensegiz-dev/portal/user/user_uploads/';

$file_name = $_GET['file_name'];


if(@unlink($base_directory.$file_name)) 
{
echo "File Deleted"; 
}
else{
echo "File can't be deleted";
}	
?>