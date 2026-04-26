<?php
    include_once "../../config/conn.php";
    require_once "../../private/aes_algo.php";

    $file_path = "../../uploads/user-files/";

    function isRestrictedFileType($filename, $mimeType) {

        $blockedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'mp4', 'mov', 'avi', 'mkv', 'flv', 'webm'];
        $blockedMimes = ['image/', 'video/'];
        
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if(in_array($extension, $blockedExtensions)) return true;

        foreach ($blockedMimes as $mime) {
            if (strpos($mimeType, $mime) === 0) return true;
        }

        return false;
    }

    // === AES Encryption ===
    function aesEncrypt($data, $aes_key, $cipher_algo, $options, $iv_length, &$ivOut) {
        $key = hash('sha256', $aes_key, true);
        $iv = openssl_random_pseudo_bytes($iv_length);
        $ivOut = $iv;
        return $iv . openssl_encrypt($data, $cipher_algo, $key, $options, $iv);
    }

    // === AES Decryption ===
    function aesDecrypt($data, $aes_key, $cipher_algo, $options, $iv_length) {
        $key = hash('sha256', $aes_key, true);
        $iv = substr($data, 0, $iv_length);
        $ciphertext = substr($data, $iv_length);
        return openssl_decrypt($ciphertext, $cipher_algo, $key, $options, $iv);
    }

    // === File Upload Handler ===
    if (isset($_FILES['uploaded-file']) && $_FILES['uploaded-file']['error'] === UPLOAD_ERR_OK && isset($_POST['action'])) {
        $user_id = htmlspecialchars(trim($_SESSION["user-id"]));
        $action = htmlspecialchars(trim($_POST["action"]));
        $original_name = basename($_FILES["uploaded-file"]["name"]);
        $mime_type = mime_content_type($_FILES["uploaded-file"]["tmp_name"]);

        if(isRestrictedFileType($original_name, $mime_type)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "File type not allowed! Please try again."
            ];

            header("Location: ../../users/home.php?page=home");
            exit();
        }

        $file_content = file_get_contents($_FILES["uploaded-file"]["tmp_name"]);

        if(!$file_content) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Unable to read file content! Please try again."
            ];

            header("Location: ../../users/home.php?page=home");
            exit();
        }

        else {
            $unique_id = uniqid();
            $original_file = $unique_id . "_original_" . $original_name;
            $processed_name = $unique_id . "_" . $action . "_" . $original_name;

            $original_file_path = $file_path . $original_file;
            $processed_file_path = $file_path . $processed_name;

            if(!copy($_FILES["uploaded-file"]["tmp_name"], $original_file_path)) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "Unable to save the original file! Please try again."
                ];

                header("Location: ../../users/home.php?page=home");
                exit();
            }

            else {
                if($action === "Encrypt") {
                    $iv = "";
                    $processed_data = aesEncrypt($file_content, $aes_key, $cipher_algo, $options, $iv_length, $iv);
                }

                else if($action === "Decrypt") {
                    $processed_data = aesDecrypt($file_content, $aes_key, $cipher_algo, $options, $iv_length);
                }

                else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Invalid action! Please try again."
                    ];

                    header("Location: ../../users/home.php?page=home");
                    exit();
                }

                if(file_put_contents($processed_file_path, $processed_data) === false) {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Unable to save encrypted/decrypted file! Please try again."
                    ];

                    header("Location: ../../users/home.php?page=home");
                    exit();
                }

                else {
                    $insert_uploaded_files = $conn->prepare("INSERT INTO user_uploaded_files(user_id, original_name, stored_original_name, stored_processed_name, used_method)
                                                            VALUES(:user_id, :original_name, :stored_original, :processed_original, :method)
                                                            ");
                    $insert_uploaded_files->execute([
                        ":user_id" => $user_id,
                        ":original_name" => $original_name,
                        ":stored_original" => $original_file,
                        ":processed_original" => $processed_name,
                        ":method" => $action
                    ]);

                    $action_label = $action === "Encrypt" ? "encrypted" : "decrypted";
                    $message = "File " . $action_label . " successfully!";

                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => $message
                    ];

                    header("Location: ../../users/home.php?page=uploaded-files");
                    exit();
                }
            }
        }
    }
?>