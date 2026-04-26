<?php
    require_once "../../config/conn.php";
    require_once "../../config/mailer.php";

    if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["user-id"]) && isset($_GET["verification-token"]) && isset($_GET["email-address"])) {
        $user_id = htmlspecialchars(trim($_GET["user-id"]));
        $verification_token = htmlspecialchars(trim($_GET["verification-token"]));
        $email_address = filter_var($_GET["email-address"], FILTER_SANITIZE_EMAIL);

        if(empty($user_id) || empty($verification_token) || empty($email_address)) {
            header("Location: ../../login.php");
            exit();
        }

        else if(!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../../login.php");
            exit();
        }

        else {
            try {
                $conn->beginTransaction();

                $check_credentials = $conn->prepare("SELECT * FROM user_accounts_tbl WHERE user_id = :user_id AND email_address = :email_address AND verification_token = :token LIMIT 1");
                $check_credentials->execute([
                    ":user_id" => $user_id,
                    ":email_address" => $email_address,
                    ":token" => $verification_token
                ]);

                if($check_credentials->rowCount() === 1) {

                    $account_data = $check_credentials->fetch(PDO::FETCH_OBJ);

                    $verify_account = $conn->prepare("UPDATE user_accounts_tbl SET verification_token = :token, verified_account = :verified_account WHERE user_id = :user_id");
                    $verify_account->execute([
                        ":token" => NULL,
                        ":verified_account" => "Yes",
                        ":user_id" => $user_id
                    ]);

                    $full_name = $account_data->first_name . " " . $account_data->last_name;

                    send_welcome_message($email_address, $full_name);
                    
                    $conn->commit();

                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "Your account is now successfully verified! Please login to continue."
                    ];

                    header("Location: ../../login.php");
                    exit();
                }

                else {
                    header("Location: ../../login.php");
                    exit();
                }
            }

            catch(PDOException $e) {
                $conn->rollBack();

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