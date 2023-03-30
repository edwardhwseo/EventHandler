<?php

/*******w******** 
    
    Name: Edward Seo
    Description: Contains functions for image uploads

****************/

function resize_medium_and_thumbnail($image){
    $extension = pathinfo($image, PATHINFO_EXTENSION);
    $medium_name = "." . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . basename($image, ".$extension") . "_medium." . $extension;
    $thumbnail_name = "." . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . basename($image, ".$extension") . "_thumbnail." . $extension;

    $image = new \Gumlet\ImageResize($image);
    $image->resizeToWidth(400)
            ->save($medium_name)
            
            ->resizeToWidth(50)
            ->save($thumbnail_name);
}

function file_upload_path($original_filename, $upload_subfolder_name = 'uploads'){
    $current_folder = dirname(__FILE__);

    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];

    return join(DIRECTORY_SEPARATOR, $path_segments);
}

function file_is_an_image($temp_path, $new_path){
    $allowed_mime_types = ['image/gif', 'image/jpeg', 'image/png'];
    $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];

    $actual_file_extension = pathinfo($new_path, PATHINFO_EXTENSION);
    $actual_mime_type = mime_content_type($temp_path);

    $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
    $mime_type_is_valid = in_array($actual_mime_type, $allowed_mime_types);

    return $file_extension_is_valid && $mime_type_is_valid;
}
function file_is_a_pdf($temp_path, $new_path){
    $allowed_mime_type = 'application/pdf';
    $allowed_file_extension = 'pdf';

    $actual_file_extension = pathinfo($new_path, PATHINFO_EXTENSION);
    $actual_mime_type = mime_content_type($temp_path);

    $file_extension_is_valid = $actual_file_extension == $allowed_file_extension ? true : false;
    $mime_type_is_valid = $actual_mime_type == $allowed_mime_type ? true : false;

    return $file_extension_is_valid && $mime_type_is_valid;
}
?>