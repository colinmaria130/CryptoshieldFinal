<?php
    require_once "../../config/conn.php";
    require_once "../../config/checkers.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update-profile"])) {

        $admin_id = htmlspecialchars(trim($_SESSION["admin-id"]));
        $first_name = htmlspecialchars($_POST["first-name"]);
        $middle_name = htmlspecialchars($_POST["middle-name"]);
        $last_name = htmlspecialchars($_POST["last-name"]);
        $date_of_birth = htmlspecialchars($_POST["date-of-birth"]);
        $gender = htmlspecialchars($_POST["gender"]);
        $phone_number = htmlspecialchars($_POST["phone-number"]);
        $address = htmlspecialchars($_POST["address"]);

        if(empty($admin_id) || empty($first_name) || empty($last_name) || empty($date_of_birth) || empty($gender) || empty($phone_number) || empty($address)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../admin/home.php?page=user-profile&update-profile=true");
            exit();
        }

        else if(!in_array($gender, $allowed_genders)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Invalid gender type! Please try again."
            ];

            header("Location: ../../admin/home.php?page=user-profile&update-profile=true");
            exit();
        }

        else {
            try {
                $update_account = $conn->prepare("UPDATE admin_accounts_tbl
                                                SET first_name = :first_name,
                                                middle_name = :middle_name,
                                                last_name = :last_name
                                                WHERE admin_id = :admin_id
                                                ");
                $update_account->execute([
                    ":first_name" => $first_name,
                    ":middle_name" => $middle_name,
                    ":last_name" => $last_name,
                    ":admin_id" => $admin_id
                ]);

                $update_profile = $conn->prepare("UPDATE admin_info_tbl
                                                SET date_of_birth = :dob,
                                                gender = :gender,   
                                                phone_number = :phone_number,
                                                full_address = :address
                                                WHERE admin_id = :admin_id
                                                ");
                $update_profile->execute([
                    ":dob" => $date_of_birth,
                    ":gender" => $gender,
                    ":phone_number" => $phone_number,
                    ":address" => $address,
                    ":admin_id" => $admin_id
                ]);

                $_SESSION["query-status"] = [
                    "status" => "success",
                    "message" => "Profile updated successfully!"
                ];

                header("Location: ../../admin/home.php?page=user-profile&update-profile=true");
                exit();
            }

            catch(PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: ../../admin/home.php?page=user-profile&update-profile=true");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update-profile-picture"])) {
        $file_location = "../../uploads/user-images/";

        $admin_id = htmlspecialchars(trim($_SESSION["admin-id"]));
        $uploaded_photo = $_FILES["uploaded-photo"]["name"];
        $tmp_name = $_FILES["uploaded-photo"]["tmp_name"];

        $file_extension = strtolower(pathinfo($uploaded_photo, PATHINFO_EXTENSION));

        if(!in_array($file_extension, $allowed_img_format)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Invalid file type! Please try again."
            ];

            header("Location: ../../admin/home.php?page=user-profile&update-photo=true");
            exit();
        }

        else {
            try {
                
                $unique_filename = 'image_' . $admin_id . '_' . uniqid() . '.' . $file_extension;

                $update_profile_picture = $conn->prepare("UPDATE admin_info_tbl SET profile_picture = :profile_picture WHERE admin_id = :admin_id");
                $update_profile_picture->execute([
                    ":profile_picture" => $unique_filename,
                    ":admin_id" => $admin_id
                ]);

                $file_path = $file_location . $unique_filename;
                move_uploaded_file($tmp_name, $file_path);

                $_SESSION["query-status"] = [
                    "status" => "success",
                    "message" => "Profile picture updated successully!"
                ];

                header("Location: ../../admin/home.php?page=user-profile&update-photo=true");
                exit();
            }

            catch(PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: ../../admin/home.php?page=user-profile&update-photo=true");
                exit();
            }
        }
    }

    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update-password"])) {

        $admin_id = htmlspecialchars(trim($_SESSION["admin-id"]));
        $current_password = htmlspecialchars(trim($_POST["current-password"]));
        $new_password = htmlspecialchars(trim($_POST["new-password"]));
        $confirm_password = htmlspecialchars(trim($_POST["confirm-new-password"]));

        if(empty($admin_id) || empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../admin/home.php?page=user-profile&update-password=true");
            exit();
        }

        else if(!preg_match($password_pattern, $new_password)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Invalid password pattern! Please try again."
            ];

            header("Location: ../../admin/home.php?page=user-profile&update-password=true");
            exit();
        }

        else {
            if($new_password === $confirm_password) {
                try {
                    $check_account = $conn->prepare("SELECT * FROM admin_accounts_tbl WHERE admin_id = :admin_id LIMIT 1");
                    $check_account->execute([":admin_id" => $admin_id]);
                    
                    if($check_account->rowCount() === 1) {
                        $password_data = $check_account->fetch(PDO::FETCH_OBJ);

                        if(password_verify($current_password, $password_data->admin_password)) {
                            $set_new_password = $conn->prepare("UPDATE admin_accounts_tbl SET admin_password = :new_password WHERE admin_id = :admin_id");
                            $set_new_password->execute([
                                ":new_password" => password_hash($new_password, PASSWORD_BCRYPT),
                                ":admin_id" => $admin_id
                            ]);

                            $change_update = $conn->prepare("UPDATE admin_info_tbl
                                                        SET updated_at = CURRENT_TIMESTAMP()
                                                        WHERE admin_id = :admin_id");
                            $change_update->execute([":admin_id" => $admin_id]);

                            $_SESSION["query-status"] = [
                                "status" => "success",
                                "message" => "Password changed successfully!"
                            ];

                            header("Location: ../../admin/home.php?page=user-profile&update-password=true");
                            exit();

                        }

                        else {
                            $_SESSION["query-status"] = [
                                "status" => "danger",
                                "message" => "Invalid password! Please try again."
                            ];

                            header("Location: ../../admin/home.php?page=user-profile&update-password=true");
                            exit();
                        }
                    }

                    else {
                        $_SESSION["query-status"] = [
                            "status" => "danger",
                            "message" => "Invalid account! Please try again."
                        ];

                        header("Location: ../../admin/home.php?page=user-profile&update-password=true");
                        exit();
                    }
                }

                catch(PDOException $e) {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "An unknown error occured! Please try again."
                    ];

                    header("Location: ../../admin/home.php?page=user-profile&update-password=true");
                    exit();
                }
            }

            else {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "Passwords don't match! Please try again."
                ];

                header("Location: ../../admin/home.php?page=user-profile&update-password=true");
                exit();
            }   
        }
    }

    else {
        header("Location: ../../admin/home.php?page=user-profile");
        exit();
    }
?>