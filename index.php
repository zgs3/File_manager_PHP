<?php
$parentDir = ('..' . $_SERVER['REQUEST_URI']);

if (isset($_GET['path'])) {
  $currentDir = './' . $_GET['path'];
} else {
  $currentDir = './';
}

session_start();
// logout 
if (isset($_GET['action']) and $_GET['action'] == 'logout') {
  session_destroy();
  session_start();
  header("Location: http://localhost/file_manager_php/");
}

// login 
$loginMsg = '';
if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
  if ($_POST['username'] == 'User' && $_POST['password'] == '12345') {
    $_SESSION['logged_in'] = true;
    $_SESSION['timeout'] = time();
    $_SESSION['username'] = $_POST['username'];
  } else {
    $loginMsg = 'Wrong username or password.';
  }
  if ($_SESSION['logged_in'] = true) {
    header("Location: " . $_SERVER['REQUEST_URI']);
  }
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

// Checking file type. Returning file name or a path for directory opening. Adding icon depending on the file format
function returnFileName($dir, $file)
{
  if (is_dir($dir . $file)) {
    if (str_contains($dir . $file, ' ')) {
      return ("<i class='fa-solid fa-folder-open'></i>" . " " . "<a href=?path=" . str_replace(' ', '%20', ltrim($dir, './')) . str_replace(' ', '%20', $file) . "/>" . $file . "</a>");
    } else {
      return ("<i class='fa-solid fa-folder-open'></i>" . " " . "<a href=?path=" . ltrim($dir, './') . $file . "/>" . $file . "</a>");
    }
  } else {
    if (preg_match("/[\.][tT][xX][tT$]/", $file) == 1) {
      return ("<i class='fa-solid fa-file-lines'></i>" . " " . $file);
    } else if (
      (preg_match("/[\.][jJ][pP][gG$]/", $file) == 1) ||
      (preg_match("/[\.][jJ][pP][eE][gG$]/", $file) == 1) ||
      (preg_match("/[\.][pP][nN][gG$]/", $file) == 1)
    ) {
      return ("<i class='fa-solid fa-file-image'></i>" . " " . $file);
    } else if (
      (preg_match("/[\.][gG][iI][fF$]/", $file) == 1) ||
      (preg_match("/[\.][wW][mM][aA$]/", $file) == 1) ||
      (preg_match("/[\.][mM][pP][3-5$]/", $file) == 1)
    ) {
      return ("<i class='fa-solid fa-photo-film'></i>" . " " . $file);
    } else {
      return ("<i class='fa-solid fa-file'></i>" . " " . $file);
    }
  }
}

// Returning delete button for files, but not folders 
function returnDeleteBtn($dir, $file)
{
  if (is_file($dir . $file) && $file !== 'index.php' && $file !== 'styles.css') {
    return ("<form action='' method=POST>
                <input type='hidden' name='fileToDelete' value='" . $file . "' >
                <button type='submit' name='delBtn'><i class='fa-solid fa-trash-can'></i></button>
              </form>"
    );
  } else {
    return ('-');
  }
}

// checking if target is file or folder, returning download button ---------- ---------- 
function returnDownloadBtn($dir, $file)
{
  if (is_file($dir . $file) && $file !== 'index.php' && $file !== 'styles.css') {
    return ("<form action='' method=POST>
                <input type='hidden' name='fileToDownload' value='" . $file . "' >
                <button type='submit' name='downloadBtn'><i class='fa-solid fa-download'></i></button>
              </form>"
    );
  } else {
    return ('-');
  }
}

// Deleting files ---------- ---------- ---------- ---------- ----------
if (isset($_POST['delBtn'])) {
  unlink($currentDir . "/" . $_POST['fileToDelete']);
  header("Location: " . $_SERVER['REQUEST_URI']);
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

// creating file or folder ---------- ---------- ---------- ---------- ----------
$fileArr = array_values(array_diff(scandir($currentDir), array('.', '..')));
$fileCreateErrorMsg = '';
if (isset($_POST['filename']) && in_array($_POST['filename'], $fileArr)) {
  $fileCreateErrorMsg = 'File name already exist!';
} else if (isset($_POST['filename'])) {
  if (str_contains($_POST['filename'], '.')) {
    fopen($currentDir . '/' . $_POST['filename'], 'w');
    header("Location: " . $_SERVER['REQUEST_URI']);
  } else {
    mkdir($currentDir . '/' . $_POST['filename']);
    header("Location: " . $_SERVER['REQUEST_URI']);
  }
}


// uploading files  ---------- ---------- ---------- ---------- ----------
$uploadMsg = '';
if (isset($_FILES['uploadedFile']) && in_array($_FILES['uploadedFile']['name'], $fileArr)) {
  $uploadMsg = 'File already exist!';
} else if ((isset($_FILES['uploadedFile']))) {
  $file_name = $_FILES['uploadedFile']['name'];
  $file_size = $_FILES['uploadedFile']['size'];
  $file_tmp = $_FILES['uploadedFile']['tmp_name'];
  $file_type = $_FILES['uploadedFile']['type'];
  move_uploaded_file($file_tmp, "./" . $currentDir . $file_name);
  header("Location: " . $_SERVER['REQUEST_URI']);
}

// returning file size in mb
function returnFileSize($dir, $file)
{
  if (is_file($dir . $file) && $file !== 'index.php' && $file !== 'styles.css') {
    $sizeInMB = (filesize($dir . $file)) / 1000000;
    return (number_format($sizeInMB, 2) . ' MB'
    );
  } else {
    return ('-');
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>File Manager</title>
  <script src="https://kit.fontawesome.com/8cc9ee3dc9.js" crossorigin="anonymous"></script>
  <style>
    <?php require 'C:\xampp\htdocs\file_manager_php\styles\styles.css' ?>
  </style>
</head>

<body>

  <header <?php isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true
            ? print("style = \"display: flex\"")
            : print("style = \"display: none\"") ?>>
    <div>
      <?php
      if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
        print('<h2>' . 'Welcome back, ' . $_SESSION['username'] . '!' . '</h2>');
      } else {
        NULL;
      } ?>
    </div>
    <div>
      <button>
        <a href="index.php?action=logout"> <i class="fa-solid fa-power-off"></i></a>
      </button>
    </div>
  </header>

  <!-- Login form -->
  <div id="loginForm" <?php isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true
                        ? print("style = \"display: none\"")
                        : print("style = \"display: block\"") ?>>
    <h2>Enter Username and Password</h2>
    <form action="" method="post" <?php isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true
                                    ? print("style = \"display: none\"")
                                    : print("style = \"display: block\"") ?>>
      <input type="text" name="username" placeholder="username = User" required autofocus></br>
      <input type="password" name="password" placeholder="password = 12345" required><br>
      <button type="submit" name="login">Login</button>
      <h4><?php echo ($loginMsg); ?></h4>
    </form>
  </div>

  <div id="mainContainer" <?php isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true
                            ? print("style = \"display: block\"")
                            : print("style = \"display: none\"") ?>>

    <?php
    // SHOW CURRENT PATH ---------- ---------- ---------- ----------
    echo ("<h1>" .  'Current directory: ' . ltrim($currentDir, '.') . "</h1>");
    ?>

    <!-- BACK Button -->
    <div id="backBtnDiv">
      <button <?php (str_contains((dirname($parentDir) . '/'), 'file_manager_php'))
                ? print("style = \"display: block\"")
                : print("style = \"display: none\"") ?>>
        <a class="backBtn" href="<?php echo (dirname($parentDir) . '/') ?>"><i class="fa-solid fa-circle-left"></i></a>
      </button>
    </div>

    <div class="tableBlock">
      <table>
        <tr class="tableHeaderRow">
          <th>File Type</th>
          <th>File Name</th>
          <th>File Size</th>
          <th>Options</th>
        </tr>
        <?php
        $currentFiles = array_values(array_diff(scandir($currentDir), array('.', '..')));
        for ($i = 0; $i < count($currentFiles); $i++) {
          echo ("
              <tr>
                <td>" . returnFileType($currentDir . $currentFiles[$i]) . "</td>
                <td>" . returnFileName($currentDir, $currentFiles[$i]) . "</td>
                <td>" . returnFileSize($currentDir, $currentFiles[$i]) . "</td>
                <td class='actionsBlock'>" . returnDeleteBtn($currentDir, $currentFiles[$i]) . returnDownloadBtn($currentDir, $currentFiles[$i]) . "</td>
              </tr>"
          );
        } ?>
      </table>
    </div>
  </div>

  <footer <?php isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true
            ? print("style = \"display: flex\"")
            : print("style = \"display: none\"") ?>>
    <!-- Creating new file -->
    <div id="createBlock">
      <h3>Create new file</h3>
      <p>Add file extension at the end of the file name to create wanted file format. </p>
      <form action="" method="post">
        <input type="text" name="filename" placeholder="File name" maxlength="20"><br><br>
        <input type="submit">
      </form>
      <div>
        <?php print($fileCreateErrorMsg) ?>
      </div>
    </div>

    <div id="uploadBlock">
      <h3>Upload new file</h3>
      <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="uploadedFile" />
        <input type="submit" />
        <h4><?php echo $uploadMsg; ?></h4>
      </form>
    </div>
  </footer>


</body>

</html>