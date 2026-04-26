<?php
  ob_start();
  require_once "../config/conn.php";
  require_once "../config/format-time.php";
  require_once "includes/officials-permissions.php";

  $page_name = isset($_GET["page"]) ? $_GET["page"] : "dashboard";
  $image_path = "../uploads/user-images/";
  $files_path = "../uploads/user-files/";

  if(empty($_SESSION["official-id"]) || $_SESSION["official-id"] === "") {
    header("Location: ../index.php");
    exit();
  } 
  
  else {
    $official_id = htmlspecialchars(trim($_SESSION["official-id"]));

    $get_user_data = $conn->prepare("SELECT
                                          oa.*, oi.*
                                          FROM officials_accounts_tbl oa
                                          LEFT JOIN officials_info_tbl oi
                                          ON oa.official_id = oi.official_id
                                          WHERE oa.official_id = :official_id
                                      ");
    $get_user_data->execute([":official_id" => $official_id]);

    if($get_user_data->rowCount() === 0) {
      unset($_SESSION["official-id"]);
      header("Location: ../index.php");
      exit();
    }

    while ($user_data = $get_user_data->fetch(PDO::FETCH_OBJ)) {

      // Primary Data
      $first_name = $user_data->first_name;
      $middle_name = $user_data->middle_name;
      $last_name = $user_data->last_name;
      $email_address = $user_data->email_address;
      $position = $user_data->position;

      // Secondary Data
      $profile_picture = $user_data->profile_picture;
      $date_of_birth = $user_data->date_of_birth;
      $gender = $user_data->gender;
      $phone_number = $user_data->phone_number;
      $address = $user_data->full_address;
      $updated = $user_data->updated_at;
    }

    $admin_folder_permission = $admin_folder_permissions[$position];
    $admin_files_permission = $admin_files_permissions[$position];
    $officials_folder_permission = $officials_folder_permissions[$position];
    $officials_file_permission = $officials_file_permissions[$position];

    if (empty($profile_picture) || !file_exists($image_path . $profile_picture)) {
      $profile_picture = "default-img.png";
    }

    require_once "includes/page-titles.php";

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
      max-width: 350px; 
      word-wrap: break-word; 
      white-space: normal;  
    }

    .folder-card {
        transition: transform 0.2s ease;
        cursor: pointer;
    }
    .folder-card:hover {
        transform: scale(1.03);
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

  <!-- Confirm Action -->
    <script>
        function confirmAction(event, formElement, formId, title, icon, message, confirmButtonTitle, confirmButtonBgColor) {

            const form = document.getElementById(formId);

            if (!formElement.checkValidity()) {
                formElement.reportValidity();
                return;
            }

            event.preventDefault();

            Swal.fire({
                title: title,
                text: message,
                icon: icon,
                showCancelButton: true,
                confirmButtonText: confirmButtonTitle,
                cancelButtonText: 'Cancel',
                confirmButtonColor: confirmButtonBgColor,
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    formElement.submit();
                }
            });

            return false;
        }
    </script>

    <script>
      document.addEventListener('contextmenu', function (e) {
          e.preventDefault();
      });

      document.addEventListener('keydown', function (e) {
          if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
              e.preventDefault();
          }
      });
    </script>

</body>

</html>