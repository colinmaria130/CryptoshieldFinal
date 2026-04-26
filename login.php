<?php
  session_start();

  $roles = [
    "admin", "official"
  ];

  $login_title = [
    "admin" => "Login as Admin",
    "official" => "Login as Official"
  ];

  $action_links = [
    "admin" => "process/admin/admin-auth.php",
    "official" => "process/officials/officials-auth.php"
  ];


  $role = htmlspecialchars(trim($_GET["role"]));

  if(!in_array($role, $roles)) {
    header("Location: index.php");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>CryptoShield: <?php echo htmlspecialchars($login_title[$role]); ?></title>

  <!-- Icon -->
  <link href="assets/assets/img/logo.png" rel="icon">

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/styles.css" rel="stylesheet">
</head>

<body>
  
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="select_user.php" class="logo d-flex align-items-center w-auto">
                  <img src="assets/imgs/logo.png" alt="" width="150" height="150">
                </a>
              </div>

              <div class="card mb-3">
                <div class="card-body">

                  <?php if (!empty($_SESSION["query-status"])) { ?>
                    <div id="notification" class="alert alert-<?= htmlspecialchars($_SESSION["query-status"]["status"]); ?> text-center" role="alert">
                      <?= htmlspecialchars($_SESSION["query-status"]["message"]); ?>
                    </div>
                    <?php unset($_SESSION["query-status"]); ?>
                  <?php } ?>

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">
                      <?php echo htmlspecialchars($login_title[$role]); ?>
                    </h5>
                    <p class="text-center small">
                      Enter your email & password to login.
                    </p>
                  </div>

                  <form action="<?php echo htmlspecialchars($action_links[$role]); ?>" class="row g-3 needs-validation" method="POST" autocomplete="off">

                    <div class="col-12">

                      <div class="form-floating mb-3">

                        <input type="email" class="form-control" id="floatingInput" name="email-address" placeholder="Email Address" required>
                        <label for="floatingInput"> Email address </label>

                      </div>
                    </div>

                    <div class="col-12">

                      <div class="form-floating mb-3">

                        <input type="password" class="form-control login-password" id="password" name="password" placeholder="Password" required>
                        <label for="password"> Password </label>

                      </div>
                    </div>

                    <div class="col-12">

                      <div class="form-check">

                        <input class="form-check-input" type="checkbox" id="showPassword" onclick="togglePasswords('login-password')">
                        <label class="form-check-label" for="showPassword"> Show Password </label>

                      </div>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit" name="login-account"> Login </button>
                    </div>

                  </form>

                </div>
              </div>

              <div class="credits">
                Designed by <a href="#">CyberSis</a>
              </div>

            </div>
          </div>
        </div>
      </section>
    </div>
  </main>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <script src="assets/js/main.js"></script>
  <script src="assets/js/notification.js"></script>
  <script src="assets/js/passwords.js"></script>

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
