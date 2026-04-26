<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Select Login Type </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icon -->
    <link href="assets/assets/img/logo.png" rel="icon">
    
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow p-4 text-center" style="width: 380px;">

            <div class="mb-1">
                <a href="index.php">
                    <img src="assets/imgs/logo.png" alt="Logo" width="120" height="120" class="rounded-circle">
                </a>
            </div>

            <h4 class="text-center mb-4 "> Select User </h4>

            <a href="login.php?role=admin" class="btn btn-dark w-100 mb-2">
                <i class="bi bi-shield-lock"></i> Admin
            </a>

            <a href="login.php?role=official" class="btn btn-success w-100 mb-2">
                <i class="bi bi-person-badge"></i> Official
            </a>

        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
                e.preventDefault();
            }
        });
    </script>

</body>

</html>