<!DOCTYPE html>

<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="googlebot-news" content="noindex,nofollow" />
    <meta name="googlebot" content="noindex,nofollow" />
    <meta name="robots" content="noindex,nofollow" />
    <meta name="author" content="George Marinos" />
    <meta name="description" content="Εκδηλώσεις ΕΚΝΕΧΑ">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans&subset=greek-ext,latin-ext' rel='stylesheet' type='text/css'>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <title>StinPlateia - Post Exists</title>

</head>

<?php
//error_reporting(E_ERROR | E_PARSE);
session_start();

// Define a function to sanitize input
function sanitize($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

$title = $_POST["title"];
$content = $_POST["content"];

$target_name = $_FILES["img_upload"]["name"];
$target_nametemp = $_FILES["img_upload"]["tmp_name"];

$extension = pathinfo($target_name, PATHINFO_EXTENSION);
$new_filename = $title . '.' . $extension;

$upload_dir = __DIR__ . "/img/" . $new_filename;

if (!file_exists("txt/" . $title . ".txt") && !empty($title) && !empty($content)) {
    $newfile = fopen("txt/" . $title . ".txt", "w");
    $newfile;
    move_uploaded_file($target_nametemp, $upload_dir);
    fwrite($newfile, $content);
    fclose($newfile);
    header("Location: index.php");
    die();
} else if (file_exists("txt/" . $title . ".txt")) {
    echo '<div id="err"><p>Post Exists</p></div>';
    header("Refresh:5; url=form.php");
    die();
} else if (empty($title) || empty($content)) {
    echo '<div id="err"><p>Empty Fields</p></div>';
    header("Refresh:5; url=form.php");
    die();
}
