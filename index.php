<?php
  $root = '/storage/emulated/0';
  $path = urldecode($_SERVER['REQUEST_URI']);
  if(is_file($root . $path)){
    header('Content-Dispotition: inline');
    header('Content-Type: ' . mime_content_type($root . $path));
    echo file_get_contents($root . $path);
    exit(0);
  }
  $files = scandir($root . $path);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width"/>
  </head>
  <body>
    <?php
      if($files){
        foreach($files as $file){
          $file .= is_dir($root . $path . $file) ? '/' : '';
          echo "<a href=\"$path$file\">$file</a><br/>";
        }
      }
    ?>
  </body>
</html>