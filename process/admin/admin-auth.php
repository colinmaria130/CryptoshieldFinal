<?php

require_once "../../config/conn.php";
    require_once "../../config/mailer.php";
    require_once "../../config/checkers.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login-account"])) {
        $email_address = filter_var($_POST["email-address"], FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars(trim($_POST["password"]));

        if(empty($email_address) || empty($password)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../login.php?role=admin");
            exit();
        }

        else if(!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Invalid email address format!"
            ];

            header("Location: ../../login.php?role=admin");
            exit();
        }

        else {
            try {
                $check_account = $conn->prepare("SELECT * FROM admin_accounts_tbl WHERE email_address = :email_address LIMIT 1");
                $check_account->execute([":email_address" => $email_address]);

                if($check_account->rowCount() === 1) {
                    $account_data = $check_account->fetch(PDO::FETCH_OBJ);

                    if(password_verify($password, $account_data->admin_password)) {
                        $admin_id = $account_data->admin_id;
                        $_SESSION["admin-id"] = $admin_id;

                        header("Location: ../../admin/home.php");
                        exit();

                    }

                    else {
                        $_SESSION["query-status"] = [
                            "status" => "danger",
                            "message" => "Invalid email address or password!"
                        ];

                        header("Location: ../../login.php?role=admin");
                        exit();
                    }
                }

                else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Invalid email address or password!"
                    ];

                    header("Location: ../../login.php?role=admin");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: ../../login.php");
                exit();
            }
        }
    }

?>