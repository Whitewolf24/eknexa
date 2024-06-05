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

    <title>StinPlateia - Create Posts</title>

</head>

<body>
    <?php
    session_start();
    include_once 'footer_head.php';

    if (isset($_SESSION['error'])) {
        echo '<div id="err"><p>' . $_SESSION['error'] . '</p></div>';
    }
    ?>
    <form action="create.php" method="POST" id="form" enctype="multipart/form-data">
        <div id="title">
            <label> Title:</label> <input type="text" name="title"></input>
        </div>
        <div id="content">
            <label> Content:</label> <textarea name="content" id="textarea"></textarea>
        </div>
        <input type="file" name="img_upload" id="img_upload">
        <input type="submit" value="Create Post"> </input>
    </form>
    <?php
    include_once 'footer.php';
    ?>
</body>

</html>