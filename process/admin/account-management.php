<?php
    require_once "../../config/conn.php";
    require_once "../../config/mailer.php";
    require_once "../../config/checkers.php";

    // Add Official Account
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add-official-account"])) {
        $position = htmlspecialchars($_POST["position"]);
        $first_name = htmlspecialchars($_POST["first-name"]);
        $middle_name = htmlspecialchars($_POST["middle-name"]);
        $last_name = htmlspecialchars($_POST["last-name"]);
        $date_of_birth = htmlspecialchars(trim($_POST["date-of-birth"]));
        $gender = htmlspecialchars($_POST["gender"]);
        $email_address = filter_var($_POST["email-address"], FILTER_SANITIZE_EMAIL);
        $phone_number = htmlspecialchars(trim($_POST["phone-number"]));
        $address = htmlspecialchars($_POST["address"]);

        if(empty($position) || empty($first_name) || empty($last_name) || empty($email_address) || empty($gender) || empty($phone_number) || empty($address)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../admin/home.php?page=officials-accounts");
            exit();
        }

        else if(!in_array($position, $allowed_officials)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Invalid official position! Please try again."
            ];

            header("Location: ../../admin/home.php?page=officials-accounts");
            exit();
        }

        else if(!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Invalid email address format! Please try again."
            ];

            header("Location: ../../admin/home.php?page=officials-accounts");
            exit();
        }

        else if(!preg_match($date_pattern, $date_of_birth)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Invalid date format! Please try again."
            ];

            header("Location: ../../admin/home.php?page=officials-accounts");
            exit();
        }

        else if(!in_array($gender, $allowed_genders)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Invalid gender! Please try again."
            ];

            header("Location: ../../admin/home.php?page=officials-accounts");
            exit();
        }

        else if(!preg_match($cellphone_pattern, $phone_number)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Invalid phone number! Please try again."
            ];

            header("Location: ../../admin/home.php?page=officials-accounts");
            exit();
        }

        else {
            try {
                $conn->beginTransaction();

                $check_email_address = $conn->prepare("SELECT * FROM officials_accounts_tbl WHERE email_address = :email_address LIMIT 1");
                $check_email_address->execute([":email_address" => $email_address]);

                if($check_email_address->rowCount() === 0) {
                    $insert_official_account = $conn->prepare("INSERT INTO officials_accounts_tbl(first_name, middle_name, last_name, email_address, official_password, position)
                                                            VALUES(:first_name, :middle_name, :last_name, :email_address, :official_password, :position)
                                                            ");
                    $insert_official_account->execute([
                        ":first_name" => $first_name,
                        ":middle_name" => $middle_name,
                        ":last_name" => $last_name,
                        ":email_address" => $email_address,
                        ":official_password" => password_hash(strtoupper($last_name), PASSWORD_BCRYPT),
                        ":position" => $position
                    ]);

                    $full_name = $first_name . " " . $last_name;
                    $official_id = $conn->lastInsertId();

                    $insert_official_info = $conn->prepare("INSERT INTO officials_info_tbl(official_id, date_of_birth, gender, phone_number, full_address)
                                                        VALUES(:official_id, :date_of_birth, :gender, :phone_number, :full_address)
                                                        ");
                    $insert_official_info->execute([
                        ":official_id" => $official_id,
                        ":date_of_birth" => $date_of_birth,
                        ":gender" => $gender,
                        ":phone_number" => $phone_number,
                        ":full_address" => $address
                    ]);

                    send_manager_creation_email($email_address, $full_name, $position, strtoupper($last_name));
                    $conn->commit();

                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "Official account added successfully!"
                    ];

                    header("Location: ../../admin/home.php?page=officials-accounts");
                    exit();
                }

                else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "This account already exists! Please try again."
                    ];

                    header("Location: ../../admin/home.php?page=officials-accounts");
                    exit();
                }
            }

            catch(PDOException $e) {
                $conn->rollBack();

                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: ../../admin/home.php?page=officials-accounts");
                exit();
            }
        }
    }

    // Delete Official Account
    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete-account"])) {
        $official_id = htmlspecialchars(trim(base64_decode($_POST["official-id"])));

        if(empty($official_id)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../admin/home.php?page=officials-accounts");
            exit();
        }

        else {
            try {
                $check_account = $conn->prepare("SELECT * FROM officials_accounts_tbl WHERE official_id = :official_id LIMIT 1");
                $check_account->execute([":official_id" => $official_id]);

                if($check_account->rowCount() === 1) {
                    $delete_account = $conn->prepare("DELETE FROM officials_accounts_tbl WHERE official_id = :official_id");
                    $delete_account->execute([":official_id" => $official_id]);

                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "Account deleted successfully!"
                    ];

                    header("Location: ../../admin/home.php?page=officials-accounts");
                    exit();
                }

                else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Account not found! Please try again."
                    ];

                    header("Location: ../../admin/home.php?page=officials-accounts");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured!"
                ];

                header("Location: ../../admin/home.php?page=officials-accounts");
                exit();
            }
        }
    }
?>