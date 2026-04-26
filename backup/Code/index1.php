<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title> CryptoShield: Data Encryption and Security System </title>

    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css" />

    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="assets/css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .navbar {
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
        }

        .navbar .btn {
            color: #fff;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .navbar .btn:hover {
            background-color: #ffffff20;
        }

        header {
            background: linear-gradient(to right, #2c5364, #203a43);
            color: white;
            padding: 60px 0;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .card-header {
            background-color: #212529;
            color: #fff;
            font-weight: bold;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }

        .form-select {
            border-radius: 10px;
        }

        .btn-dark {
            border-radius: 10px;
        }

        .btn-outline-dark {
            border-radius: 10px;
        }

        .result {
            background: #fff;
            border-left: 5px solid #212529;
            font-family: monospace;
            padding: 20px;
            border-radius: 10px;
            word-wrap: break-word;
        }

        footer {
            padding: 20px 0;
            background-color: #f8f9fa;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 600;
        }

        textarea.form-control {
            border-radius: 10px;
        }
    </style>

</head>

<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark">

    <div class="container">

        <a class="btn btn-dark fs-5" href="index.php"> CryptoShield </a>

        <div class="ms-auto">
            <a class="btn" href="#home">
                <i></i> Home
            </a>

            <a class="btn" href="#about">
                <i></i> About
            </a>

            <a class="btn" href="#team">
                <i></i> Team
            </a>

            <a class="btn" href="select_user.php">
                <i></i> Login
            </a>

        </div>

    </div>

</nav>

<!-- Masthead-->
<header class="py-5 text-center" id="home">

    <div class="container">

        <h1 class="mb-4">Secure your files with AES and RSA encryption</h1>
        <a class="btn btn-outline-light" href="#form">Learn More</a>

    </div>
</header>

<section class="py-5" id="showcase">

    <div class="container text-center">

        <h2>🔐 CryptoShield - Data Encryption and Security</h2>
        <p class="lead">Transform your sensitive information using modern cryptographic methods. Keep your data private and protected.</p>

    </div>
</section>

<section class="page-section" id="about">
    <div class="container px-4 px-lg-5">
        <h2 class="text-center mt-0">Our Security Features</h2>
        <hr class="divider" />
        <div class="row gx-4 gx-lg-5">

            <div class="col-lg-5 col-md-6 text-center">
                <div class="mt-5">
                    <div class="mb-2"><i class="bi-shield-lock fs-1 text-primary"></i></div>
                    <h3 class="h4 mb-2">Advanced Encryption</h3>
                    <p class="text-muted mb-0">Your data is protected with industry-leading hybrid encryption algorithms.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <div class="mb-2"><i class="bi-cloud-check fs-1 text-primary"></i></div>
                    <h3 class="h4 mb-2">Safe File Sharing</h3>
                    <p class="text-muted mb-0">Files are encrypted in transit and at rest for maximum security.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <div class="mb-2"><i class="bi-shield-check fs-1 text-primary"></i></div>
                    <h3 class="h4 mb-2">Trusted Protection</h3>
                    <p class="text-muted mb-0">Designed with care to keep your information safe from cyber threats.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<br></br>

    <section class="testimonials text-center bg-light" id="team">
        <div class="container">
            <h2 class="mb-5">Team Members</h2>
            <hr class="divider" />

            <div class="row">
                <div class="col-lg-4">
                    <div class="testimonial-item mx-auto mb-5 mb-lg-0">
                        <a href="https://www.facebook.com/colin.pampango.2024" target="_blank">
                            <img class="img-fluid rounded-circle mb-3" src="assets/imgs/colin.jpg" alt="..." />
                        </a>
                        <h5>Colin Pampango</h5>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testimonial-item mx-auto mb-5 mb-lg-0">
                        <a href="https://www.facebook.com/media/set/?set=a.102955499019446&type=3" target="_blank">
                            <img class="img-fluid rounded-circle mb-3" src="assets/imgs/frinces.jpg" alt="..." />
                        </a>
                        <h5>Frinces Joy A. Patenio</h5>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testimonial-item mx-auto mb-5 mb-lg-0">
                        <a href="https://www.facebook.com/melesa.a.gabayeron" target="_blank">
                            <img class="img-fluid rounded-circle mb-3" src="assets/imgs/melesa.jpg" alt="..." />
                        </a>
                        <h5>Melesa A. Gabayeron</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <br></br>

    <!-- Footer-->
    <div class="footer bg-light" id="about">

        <div class="container">

            <div class="row">

                <div class="col-lg-12 h-100 text-center text-lg-start my-auto p-2">

                    <ul class="list-inline mb-2 text-center">
                        <li class="list-inline-item"><a href="terms_of_use.php"> Terms of Use </a></li>
                        <li class="list-inline-item"> ⋅ </li>
                        <li class="list-inline-item"><a href="privacy_policy.php"> Privacy Policy </a></li>
                    </ul>

                    <p class="text-muted small mb-4 mb-lg-0 text-center"> &copy; CryptoShield. All Rights Reserved. </p>

                </div>

            </div>

        </div>
    </div>

</div>

<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/scripts.js"></script>
<script src="assets/js/copy-to-clipboard.js"></script>
<script src="assets/js/encryption-process.js"></script>
<script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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