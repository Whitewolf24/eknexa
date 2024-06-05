<?php
foreach (glob("txt/*.txt") as $filename) {
?>
    <div class="yliko">
        <h1>
            <?php
            $name = pathinfo($filename, PATHINFO_FILENAME);
            echo $name; ?>
        </h1>
        <?php
        $image_path = "img/" . $name . ".jpg";
        $image_path_png = "img/" . $name . ".png";
        if (file_exists($image_path)) {
            echo "<img class='img' src={$image_path}>";
        }
        if (file_exists($image_path_png)) {
            echo "<img class='img' src={$image_path_png}>";
        } else {
            echo "<img src=''>";
        } ?>
        <p><?php echo file_get_contents($filename) . "\n"; ?> </p>
    </div>
<?php }
