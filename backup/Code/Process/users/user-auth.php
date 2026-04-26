<?php
    require_once "../../config/conn.php";
    require_once "../../config/mailer.php";
    require_once "../../config/checkers.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["user-login"])) {
        $email_address = filter_var($_POST["email-address"], FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars(trim($_POST["password"]));

        if(empty($email_address) || empty($password)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../login.php");
            exit();
        }

        else if(!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Invalid email address format!"
            ];

            header("Location: ../../login.php");
            exit();
        }

        else {
            try {
                $check_account = $conn->prepare("SELECT * FROM user_accounts_tbl WHERE email_address = :email_address LIMIT 1");
                $check_account->execute([":email_address" => $email_address]);

                if($check_account->rowCount() === 1) {
                    $account_data = $check_account->fetch(PDO::FETCH_OBJ);

                    if(password_verify($password, $account_data->user_password)) {
                        if($account_data->verified_account === "Yes") {
                            $user_id = $account_data->user_id;
                            $_SESSION["user-id"] = $user_id;

                            header("Location: ../../users/home.php");
                            exit();
                        }

                        else {
                            $_SESSION["query-status"] = [
                                "status" => "warning",
                                "message" => "Account not verified! Please check your inbox to verify your account."
                            ];

                            header("Location: ../../login.php");
                            exit();
                        }
                    }

                    else {
                        $_SESSION["query-status"] = [
                            "status" => "danger",
                            "message" => "Invalid email address or password!"
                        ];

                        header("Location: ../../login.php");
                        exit();
                    }
                }

                else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Invalid email address or password!"
                    ];

                    header("Location: ../../login.php");
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

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["register-account"])) {
        $first_name = htmlspecialchars($_POST["first-name"]);
        $middle_name = htmlspecialchars($_POST["middle-name"]);
        $last_name = htmlspecialchars($_POST["last-name"]);
        $date_of_birth = htmlspecialchars(trim($_POST["date-of-birth"]));
        $gender = htmlspecialchars(trim($_POST["gender"]));
        $phone_number = htmlspecialchars(trim($_POST["phone-number"]));
        $email_address = filter_var($_POST["email-address"], FILTER_SANITIZE_EMAIL);
        $address = htmlspecialchars($_POST["complete-address"]);
        $password = htmlspecialchars(trim($_POST["password"]));
        $confirm_password = htmlspecialchars($_POST["confirm-password"]);

        if(empty($first_name) || empty($last_name) || empty($date_of_birth) || empty($gender) || empty($phone_number) || empty($email_address) || empty($address) || empty($password) || empty($confirm_password)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../register-account.php");
            exit();
        }

        else if(!preg_match($date_pattern, $date_of_birth)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Invalid date format! Please try again."
            ];

            header("Location: ../../register-account.php");
            exit();
        }

        else if(!in_array($gender, $allowed_genders)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Invalid gender! Please try again."
            ];

            header("Location: ../../register-account.php");
            exit();
        }

        else if(!preg_match($cellphone_pattern, $phone_number)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Invalid phone number format! Please try again."
            ];

            header("Location: ../../register-account.php");
            exit();
        }

        else if(!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Invalid email address format! Please try again."
            ];

            header("Location: ../../register-account.php");
            exit();
        }

        else if(!preg_match($password_pattern, $password)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Invalid password format! Please try again."
            ];

            header("Location: ../../register-account.php");
            exit();
        }

        else if($password !== $confirm_password) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Passwords don't match! Please try again."
            ];

            header("Location: ../../register-account.php");
            exit();
        }

        else {
            try {
                $conn->beginTransaction();

                $check_existing_account = $conn->prepare("SELECT * FROM user_accounts_tbl WHERE email_address = :email_address LIMIT 1");
                $check_existing_account->execute([":email_address" => $email_address]);

                if($check_existing_account->rowCount() === 0) {
                    $user_id = generateUserId();

                    $insert_account = $conn->prepare("INSERT INTO user_accounts_tbl(user_id, first_name, middle_name, last_name, email_address, user_password)
                                                    VALUES(:user_id, :first_name, :middle_name, :last_name, :email_address, :user_password)
                                                    ");
                    $insert_account->execute([
                        ":user_id" => $user_id,
                        ":first_name" => $first_name,
                        ":middle_name" => $middle_name,
                        ":last_name" => $last_name,
                        ":email_address" => $email_address,
                        ":user_password" => password_hash($password, PASSWORD_BCRYPT)
                    ]);

                    $insert_account_info = $conn->prepare("INSERT INTO user_info_tbl(user_id, date_of_birth, gender, phone_number, user_address)
                                                        VALUES(:user_id, :date_of_birth, :gender, :phone_number, :user_address)
                                                        ");
                    $insert_account_info->execute([
                        ":user_id" => $user_id,
                        ":date_of_birth" => $date_of_birth,
                        ":gender" => $gender,
                        ":phone_number" => $phone_number,
                        ":user_address" => $address
                    ]);

                    $full_name = $first_name . " " . $last_name;
                    $verification_token = generate_verification_token();
                    $verification_link = "http://localhost/cryptoshield_final/process/users/verify-account.php?user-id=$user_id&verification-token=$verification_token&email-address=$email_address";

                    $add_verification_token = $conn->prepare("UPDATE user_accounts_tbl SET verification_token = :token WHERE user_id = :user_id");
                    $add_verification_token->execute([
                        ":token" => $verification_token,
                        ":user_id" => $user_id
                    ]);

                    verify_account($email_address, $full_name, $verification_link);

                    $conn->commit();

                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "Account successfully created! Now we've sent a verification link to your email address. Please check it now."
                    ];

                    header("Location: ../../login.php");
                    exit();
                }

                else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "This account already exists! Please try again."
                    ];

                    header("Location: ../../register-account.php");
                    exit();
                }
            }

            catch(PDOException $e) {
                $conn->rollBack();

                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: ../../register-account.php");
                exit();
            }
        }
    }
?>