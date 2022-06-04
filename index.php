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

// Returning delete button for files, but not folders ---------- ---------- 
function returnDeleteBtn($dir, $file)
{
  if (is_file($dir . $file) && $file !== 'index.php' && $file !== 'styles.css') {
    return ("<form action='' method=POST>
                <input type='hidden' name='fileToDelete' value='" . $file . "' >
                <input type='submit' name='delBtn' value='DELETE' >
              </form>"
    );
  } else {
    return (' - - - - - - - ');
  }
}

// checking if target is file or folder, returning download button ---------- ---------- 
function returnDownloadBtn($dir, $file)
{
  if (is_file($dir . $file) && $file !== 'index.php' && $file !== 'styles.css') {
    return ("<form action='' method=POST>
                <input type='hidden' name='fileToDownload' value='" . $file . "' >
                <input type='submit' name='downloadBtn' value='Download' >
              </form>"
    );
  } else {
    return (' - - - - - - - ');
  }
}

// Deleting files ---------- ---------- ---------- ---------- ----------
if (isset($_POST['delBtn'])) {
  unlink($currentDir . "/" . $_POST['fileToDelete']);
}

// downloading file ---------- ---------- ---------- ---------- ----------
if (isset($_POST['downloadBtn'])) {
  $file = './' . $_GET["path"] . $_POST['fileToDownload'];
  $fileToDownloadEscaped = str_replace("&nbsp;", " ", htmlentities($file, 3, 'utf-8'));
  ob_clean();
  ob_start();
  header('Content-Description: File Transfer');
  header('Content-Type: application/pdf');
  header('Content-Disposition: attachment; filename=' . basename($fileToDownloadEscaped));
  header('Content-Transfer-Encoding: binary');
  header('Content-Length: ' . filesize($fileToDownloadEscaped));
  ob_end_flush();
  readfile($fileToDownloadEscaped);
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>File Manager</title>
  <style>
    <?php require 'C:\xampp\htdocs\file_manager_php\styles\styles.css' ?>
  </style>
</head>

<body>

  <div id="mainContainer">

    <?php
    // SHOW CURRENT PATH ---------- ---------- ---------- ----------
    echo ("<h1>" .  'Current directory: ' . ltrim($currentDir, '.') . "</h1>");
    ?>

    <div class="tableBlock">
      <table>
        <tr class="tableHeaderRow">
          <th>File Type</th>
          <th>File Name</th>
          <th>Options</th>
        </tr>
        <?php

        // TABLE DISPLAYING FILES ---------- ---------- ---------- ----------
        $currentFiles = array_values(array_diff(scandir($currentDir), array('.', '..')));

        for ($i = 0; $i < count($currentFiles); $i++) {
          echo ("
              <tr>
                <td>" . returnFileType($currentDir . $currentFiles[$i]) . "</td>
                <td>" . returnFile($currentDir, $currentFiles[$i]) . "</td>
                <td class='actionsBlock'>" . returnDeleteBtn($currentDir, $currentFiles[$i]) . returnDownloadBtn($currentDir, $currentFiles[$i]) . "</td>
              </tr>"
          );
        }
        ?>
      </table>
    </div>

  </div>

</body>

</html>