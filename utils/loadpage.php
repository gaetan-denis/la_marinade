<?php

function loadPage()
{
    if (isset($_GET['page'])) {
        $page = htmlspecialchars($_GET['page'],ENT_QUOTES);
        $filePath = "../pages/" . $page;
        $file_extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $newFilePath = "../pages/" . $page;

        if (file_exists($newFilePath) && in_array($file_extension, VALID_EXTENSIONS)) {
            include_once $newFilePath;
        } else {
            include_once '../pages/404.php';
        }
    }
}