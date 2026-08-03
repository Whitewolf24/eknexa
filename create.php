<?php
session_start();
umask(0022);
ob_start();
?>
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

<body>
    <?php
    function sanitize($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }
    
      function safe_filename($data)
    {
        $data = trim($data);
        $data = str_replace(' ', '_', $data);
        $data = preg_replace('/[^A-Za-z0-9_\-]/', '', $data);
        return $data;
    }

    function ensure_permissions($path, $permissions)
    {
        if (is_dir($path)) {
            chmod($path, $permissions);
            $items = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($items as $item) {
                chmod($item, $permissions);
            }
        } elseif (is_file($path)) {
            chmod($path, $permissions);
        }
    }
    
       function reject($message)
    {
        echo '<div id="err"><p>' . htmlspecialchars($message) . '</p></div>';
        header("Refresh:5; url=form.php");
        ob_end_flush();
        die();
    }
    
    //////////
    
     if (!empty($_POST['website'])) {
        reject('Something went wrong. Please try again.');
    }
    
    if (
        !isset($_SESSION['form_loaded_at']) ||
        (time() - $_SESSION['form_loaded_at']) < 2
    ) {
        reject('Please try submitting again.');
    }
    
     if (
        empty($_POST['csrf_token']) ||
        empty($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        reject('Session expired. Please reload the form and try again.');
    }
    
    
        if (
        isset($_SESSION['last_post_at']) &&
        (time() - $_SESSION['last_post_at']) < 10
    ) {
        reject('You are posting too fast. Please wait a moment.');
    }
    
  
    //////////

    $raw_title = $_POST["title"] ?? '';
    $title = safe_filename($raw_title);
    $title = substr($title, 0, 100);
    $content = sanitize($_POST["content"] ?? '');

    $upload_dir = "data/img/";
    $txt_dir = "data/txt/";
    $file_path = $txt_dir . $title . ".txt";
    $upload_file = null;
    

    if (is_dir($upload_dir)) {
        ensure_permissions($upload_dir, 0755);
    } else {
        echo '<div id="err"><p>Image upload directory does not exist. Please check the server setup.</p></div>';
        header("Refresh:5; url=form.php");
        ob_end_flush();
        die();
    }

    if (is_dir($txt_dir)) {
        ensure_permissions($txt_dir, 0755);
    } else {
        echo '<div id="err"><p>Text directory does not exist. Please check the server setup.</p></div>';
        header("Refresh:5; url=form.php");
        ob_end_flush();
        die();
    }

    if (isset($_FILES["img_upload"]) && $_FILES["img_upload"]["error"] === UPLOAD_ERR_OK) {
        $target_name = $_FILES["img_upload"]["name"];
        $target_nametemp = $_FILES["img_upload"]["tmp_name"];
        $extension = pathinfo($target_name, PATHINFO_EXTENSION);
        $new_filename = $title . '.' . $extension;
        $upload_file = $upload_dir . $new_filename;
        $tmp_name = $_FILES["img_upload"]["tmp_name"];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $detected_mime = finfo_file($finfo, $tmp_name);
        
        $allowed_mimes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];
        
        $extension = $allowed_mimes[$detected_mime];
        $new_filename = $title . '.' . $extension;
        $upload_file = $upload_dir . $new_filename;

       if ($_FILES["img_upload"]["size"] > 5 * 1024 * 1024) {
            reject('Image too large (max 5MB).');
        }
        
          if (@getimagesize($tmp_name) === false) {
            reject('Uploaded file is not a valid image.');
        }
 
        if (!isset($allowed_mimes[$detected_mime])) {
            reject('Only JPG, PNG, GIF, or WEBP images are allowed.');
        }
        
        finfo_close($finfo);
        
        if (!move_uploaded_file($target_nametemp, $upload_file)) {
            echo '<div id="err"><p>Failed to upload image. Please check permissions.</p></div>';
            header("Refresh:5; url=form.php");
            ob_end_flush();
            die();
        }

        ensure_permissions($upload_file, 0644);
    }

    if (!file_exists($file_path) && !empty($title) && !empty($content)) {
        $newfile = fopen($file_path, "w");
        if ($newfile === false) {
            echo '<div id="err"><p>Failed to create file. Please check permissions.</p></div>';
            header("Refresh:5; url=form.php");
            ob_end_flush();
            die();
        }
        fwrite($newfile, $content);
        fclose($newfile);

        ensure_permissions($file_path, 0644);
        
        unset($_SESSION['csrf_token']);
        unset($_SESSION['form_loaded_at']);
        $_SESSION['last_post_at'] = time();

        header("Location: index.php");
        ob_end_flush();
        die();
    } else if (file_exists($file_path)) {
        echo '<div id="err"><p>Post Exists</p></div>';
        header("Refresh:5; url=form.php");
        ob_end_flush();
        die();
    } else if (empty($title) || empty($content)) {
        echo '<div id="err"><p>Empty Fields</p></div>';
        header("Refresh:5; url=form.php");
        ob_end_flush();
        die();
    }
    ?>
</body>

</html>