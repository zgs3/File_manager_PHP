<?php
$parentDir = ('..' . $_SERVER['REQUEST_URI']);

if (isset($_GET['path'])) {
  $currentDir = './' . $_GET['path'];
} else {
  $currentDir = './';
}

// Checking file type. File / directory
function returnFileType($file)
{
  if (is_file($file)) {
    return ('File');
  } else if (is_dir($file)) {
    return ('Directory');
  }
}

// Checking file type. Returning file name or a path for directory opening
function returnFile($dir, $file)
{
  if (is_dir($dir . $file)) {
    if (str_contains($dir . $file, ' ')) {
      return ("<a href=?path=" . str_replace(' ', '%20', ltrim($dir, './')) . str_replace(' ', '%20', $file) . "/>" . $file . "</a>");
    } else {
      return ("<a href=?path=" . ltrim($dir, './') . $file . "/>" . $file . "</a>");
    }
  } else {
    return $file;
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>File Manager</title>
  <style><?php require 'C:\xampp\htdocs\file_manager_php\styles\styles.css' ?></style>
</head>
<body>
  
<div class="tableBlock">
      <table>
        <tr class="tableHeaderRow">
          <th>File Type</th>
          <th>File Name</th>
        </tr>
        <?php

        // TABLE DISPLAYING FILES ---------- ---------- ---------- ----------
        $currentFiles = array_values(array_diff(scandir($currentDir), array('.', '..')));

        for ($i = 0; $i < count($currentFiles); $i++) {
          echo ("
              <tr>
                <td>" . returnFileType($currentDir . $currentFiles[$i]) . "</td>
                <td>" . returnFile($currentDir, $currentFiles[$i]) . "</td>
              </tr>"
          );
        }
        ?>
      </table>
    </div>
  </div>

</body>
</html>