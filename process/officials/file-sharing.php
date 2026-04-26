<?php
    require_once "../../config/conn.php";
    require_once "encryption-process.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["share-file"])) {
        $file_path = "../../uploads/user-files/";

        $official_id = htmlspecialchars(trim($_SESSION["official-id"]));
        $receiver_id = htmlspecialchars(trim(base64_decode($_POST["receiver-id"])));

        $original_name = basename($_FILES["uploaded-file"]["name"]);
        $file_size = $_FILES["uploaded-file"]["size"];
        $mime_type = mime_content_type($_FILES["uploaded-file"]["tmp_name"]);

        $encoded_receiver_id = base64_encode($receiver_id);

        if (isRestrictedFileType($original_name, $mime_type)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "File type not allowed! Please try again."
            ];

            header("Location: ../../officials/home.php?page=file-sharing&official-id=$encoded_receiver_id");
            exit();
        }

        $file_content = file_get_contents($_FILES["uploaded-file"]["tmp_name"]);

        if (!$file_content) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Unable to read file content! Please try again."
            ];

            header("Location: ../../officials/home.php?page=file-sharing&official-id=$encoded_receiver_id");
            exit();
        } 
        
        else {
            $unique_id = uniqid();
            $original_file = $unique_id . "_original_" . $original_name;
            $processed_name = $unique_id . "_" . $action . "_" . $original_name;

            $original_file_path = $file_path . $original_file;
            $processed_file_path = $file_path . $processed_name;

            $file_size = number_format($file_size / 1048576, 2);

            if (!copy($_FILES["uploaded-file"]["tmp_name"], $original_file_path)) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "Unable to save the original file! Please try again."
                ];

                header("Location: ../../officials/home.php?page=my-folder-contents&folder-id=$encoded_folder_id");
                exit();
            } 
            
            else {
                $iv = "";
                $processed_data = encryptFile($file_content, $aes_key, $cipher_algo, $options, $iv_length, $iv);

                if (file_put_contents($processed_file_path, $processed_data) === false) {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Unable to save the processed file! Please try again."
                    ];

                    header("Location: ../../officials/home.php?page=file-sharing&official-id=$encoded_receiver_id");
                    exit();
                } 
                
                else {
                    try {
                        $conn->beginTransaction();
                        $insert_uploaded_files = $conn->prepare("INSERT INTO officials_files_tbl (
                                folder_id, file_name, stored_file_name, processed_file_name, file_size, 
                                brgy_secretary_download, brgy_treasurer_download, 
                                brgy_kagawad_download, sk_chairman_download, sk_kagawad_download, uploaded_by
                            ) VALUES (
                                :folder_id, :file_name, :stored_name, :processed_name, :file_size, 
                                :allow_brgy_secretary, :allow_brgy_treasurer, 
                                :allow_brgy_kagawad, :allow_sk_chairman, :allow_sk_kagawad, :uploaded_by
                            )");

                        $insert_uploaded_files->execute([
                            ":folder_id"            => $folder_id,
                            ":file_name"            => $original_name,
                            ":stored_name"          => $original_file,
                            ":processed_name"       => $processed_name,
                            ":file_size"            => $file_size,
                            ":allow_brgy_secretary" => "Yes",
                            ":allow_brgy_treasurer" => "Yes",
                            ":allow_brgy_kagawad"   => "Yes",
                            ":allow_sk_chairman"    => "Yes",
                            ":allow_sk_kagawad"     => "Yes",
                            ":uploaded_by" => $official_id
                        ]);

                        $file_id = $conn->lastInsertId();

                        $insert_share = $conn->prepare("INSERT INTO file_sharing_tbl(file_id, sender, receiver) VALUES(:file_id, :sender, :receiver)");
                        $insert_share->execute([
                            ":file_id" => $file_id,
                            ":sender" => $official_id,
                            ":receiver" => $receiver_id
                        ]);

                        $conn->commit();

                        header("Location: ../../officials/home.php?page=file-sharing&official-id=$encoded_receiver_id");
                        exit();
                    }

                    catch(PDOException $e) {
                        $conn->rollBack();
                        $_SESSION["query-status"] = [
                            "status" => "success",
                            "message" => "An unknown error occured! Please try again."
                        ];

                        header("Location: ../../officials/home.php?page=file-sharing&official-id=$encoded_receiver_id");
                        exit();
                    }
                }
            }
        }
    }
?>