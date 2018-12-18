<!DOCTYPE html>
<html>
<head>
</head>
<body>
  <?php
  if (!empty($_GET)) {
    $user = $_GET['user'];
    header("Location: user/{$user}");
    die();
  }
  ?>
  <p>wassup</p>
</body>
</html>
