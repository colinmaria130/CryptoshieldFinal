<?php
  ob_start();
  require_once "../config/conn.php";
  require_once "../config/format-time.php";
  require_once "includes/page-titles.php";

  $page_name = isset($_GET["page"]) ? $_GET["page"] : "home";
  $image_path = "../uploads/user-images/";
  $files_path = "../uploads/user-files/";

  if(empty($_SESSION["user-id"]) || $_SESSION["user-id"] === "") {
    header("../index.php");
    exit();
  } 
  
  else {
    $user_id = htmlspecialchars(trim($_SESSION["user-id"]));

    $get_user_data = $conn->prepare("SELECT
                                          ua.*, ui.*
                                          FROM user_accounts_tbl ua
                                          LEFT JOIN user_info_tbl ui
                                          ON ua.user_id = ui.user_id
                                          WHERE ua.user_id = :user_id 
                                      ");
    $get_user_data->execute([":user_id" => $user_id]);

    while ($user_data = $get_user_data->fetch(PDO::FETCH_OBJ)) {

      // Primary Data
      $first_name = $user_data->first_name;
      $middle_name = $user_data->middle_name;
      $last_name = $user_data->last_name;
      $email_address = $user_data->email_address;

      // Secondary Data
      $profile_picture = $user_data->profile_picture;
      $date_of_birth = $user_data->date_of_birth;
      $gender = $user_data->gender;
      $phone_number = $user_data->phone_number;
      $address = $user_data->user_address;
      $updated_at = $user_data->updated_at;
    }

    if (empty($profile_picture) || !file_exists($image_path . $profile_picture)) {
      $profile_picture = "default-img.png";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="description">
  <meta content="" name="keywords">

  <?php
  include_once "includes/web-title.php";
  ?>


  <?php
  include_once "includes/css-files.php";
  ?>

  <style>
    .logo img {
      max-height: 70px;
    }

    .fixed-column {
      max-width: 200px; 
      word-wrap: break-word; 
      white-space: normal;  
    }
  </style>

</head>

<body>

  <!-- Header -->
  <?php
  include_once "includes/header.php";
  ?>

  <!-- Sidebar -->
  <?php
  include_once "includes/sidebar.php";
  ?>

  <!-- Main -->
  <?php
  $page_path = "pages/$page_name.php";

  if (file_exists($page_path)) {
    include_once $page_path;
  } else {
    $page_name = "home";
    include_once "pages/main.php";
  }

  ?>

  <!-- Footer -->
  <?php
  include_once "includes/footer.php";
  ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php
  include_once "includes/script-files.php";
  ?>

</body>

</html>