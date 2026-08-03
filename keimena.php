<?php
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
    <title>StinPlateia - Posts</title>
</head>

<body>
    <?php

    function ensure_permissions($path, $permissions)
    {
        if (is_dir($path)) {

            chmod($path, $permissions);

            $items = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($items as $item) {
                if ($item->isDir()) {
                    chmod($item, $permissions);
                } elseif ($item->isFile()) {
                    chmod($item, $permissions);
                }
            }
        } elseif (is_file($path)) {
            chmod($path, $permissions);
        }
    }

    // Updated directory paths
    $data_dir = "data/";
    $txt_dir = $data_dir . "/txt/";
    $img_dir = $data_dir . "/img/";

    // Ensure directories have correct permissions
    ensure_permissions($txt_dir, 0777);
    ensure_permissions($img_dir, 0777);

    $files = glob($txt_dir . "/*.txt");

    if ($files === false) {
        echo "<div id='err'><p>Failed to read files. Please check permissions.</p></div>";
        die();
    }

    usort($files, function ($a, $b) {
        return filemtime($b) - filemtime($a);
    });

    $total_files = count($files);

    foreach ($files as $index => $filename) {
        ensure_permissions($filename, 0666);

        $is_last = ($index === $total_files - 1) ? ' last' : '';
    ?>
        <div class="yliko<?php echo $is_last; ?>">
            <h1>
                <?php
                $name = pathinfo($filename, PATHINFO_FILENAME);
                echo $name;
                ?>
            </h1>
            <?php
            $image_path_jpg = $img_dir . "/" . $name . ".jpg";
            $image_path_png = $img_dir . "/" . $name . ".png";
            if (file_exists($image_path_jpg)) {
                ensure_permissions($image_path_jpg, 0666);
                echo "<img class='img' src='{$image_path_jpg}'>";
            } elseif (file_exists($image_path_png)) {
                ensure_permissions($image_path_png, 0666);
                echo "<img class='img' src='{$image_path_png}'>";
            } else {
                echo "<img src=''>";
            }
            ?>
            <p><?php echo nl2br(file_get_contents($filename)); ?> </p>
        </div>
    <?php } ?>
</body>

</html>