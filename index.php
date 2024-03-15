<?php
  $root = '/storage/emulated/0';
  $path = urldecode($_SERVER['REQUEST_URI']);
  if(is_file($root . $path)){
    header('Content-Dispotition: inline');
    header('Content-Type: ' . mime_content_type($root . $path));
    echo file_get_contents($root . $path);
    exit(0);
  }
  $icons = [
    'directory' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M64 480H448c35.3 0 64-28.7 64-64V160c0-35.3-28.7-64-64-64H288c-10.1 0-19.6-4.7-25.6-12.8L243.2 57.6C231.1 41.5 212.1 32 192 32H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64z"/></svg>',
    'file' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128z"/></svg>',
  ];
  $files = scandir($root . $path);
  function get_type($file){
    if(is_dir($file))
      return 'directory';
    return 'file';
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width"/>
    <style>
      svg {
        width: 20px;
        height: 20px;
      }
    </style>
  </head>
  <body>
    <table border="0">
      <?php
        if($files){
          foreach($files as $file){
            $file .= is_dir($root . $path . $file) ? '/' : '';
            echo "<tr>"
                ."<td>{$icons[get_type($root.$path.$file)]}</td>"
                ."<td><a href=\"$path$file\">$file</a></td>"
                ."</tr>";
          }
        }
      ?>
    </table>
  </body>
</html>