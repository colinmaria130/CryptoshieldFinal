<?php
    require_once "../../config/conn.php";
    require_once "encryption-process.php";

    $file_directory = "../../uploads/user-files/";

    // Add Folder
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save-folder"])) {
        $folder_name = htmlspecialchars($_POST["folder-name"]);
        $brgy_captain_access = isset($_POST["brgy-captain-access"]) ? "Yes" : "No";
        $brgy_secretary_access = isset($_POST["brgy-secretary-access"]) ? "Yes" : "No";
        $brgy_treasurer_access = isset($_POST["brgy-treasurer-access"]) ? "Yes" : "No";
        $brgy_kagawad_access = isset($_POST["brgy-kagawad-access"]) ? "Yes" : "No";
        $sk_chairman_access = isset($_POST["sk-chairman-access"]) ? "Yes" : "No";
        $sk_kagawad_access = isset($_POST["sk-kagawad-access"]) ? "Yes" : "No";


        if (empty($folder_name)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../admin/home.php?page=files-and-folders");
            exit();
        } 
        
        else {
            try {
                $check_existing_folder = $conn->prepare("SELECT * FROM admin_folder_tbl WHERE folder_name = :folder_name LIMIT 1");
                $check_existing_folder->execute([":folder_name" => $folder_name]);

                if ($check_existing_folder->rowCount() === 0) {

                    $create_folder = $conn->prepare("
                        INSERT INTO admin_folder_tbl
                        (folder_name, brgy_captain_access, brgy_secretary_access, brgy_treasurer_access, brgy_kagawad_access, sk_chairman_access, sk_kagawad_access)
                        VALUES
                        (:folder_name, :brgy_captain, :brgy_secretary, :brgy_treasurer, :brgy_kagawad, :sk_chairman, :sk_kagawad)
                    ");

                    $create_folder->execute([
                        ":folder_name"    => $folder_name,
                        ":brgy_captain"   => $brgy_captain_access,
                        ":brgy_secretary" => $brgy_secretary_access,
                        ":brgy_treasurer" => $brgy_treasurer_access,
                        ":brgy_kagawad"   => $brgy_kagawad_access,
                        ":sk_chairman"    => $sk_chairman_access,
                        ":sk_kagawad"     => $sk_kagawad_access
                    ]);

                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "Folder added successfully!"
                    ];

                    header("Location: ../../admin/home.php?page=files-and-folders");
                    exit();
                } else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "This folder already exists! Please try again."
                    ];

                    header("Location: ../../admin/home.php?page=files-and-folders");
                    exit();
                }
            } catch (PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: ../../admin/home.php?page=files-and-folders");
                exit();
            }
        }
    }

    // Update Folder
    else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update-folder"])) {
        $folder_id = htmlspecialchars(trim(base64_decode($_POST["folder-id"])));
        $folder_name = htmlspecialchars($_POST["folder-name"]);
        $brgy_captain_access = isset($_POST["brgy-captain-access"]) ? "Yes" : "No";
        $brgy_secretary_access = isset($_POST["brgy-secretary-access"]) ? "Yes" : "No";
        $brgy_treasurer_access = isset($_POST["brgy-treasurer-access"]) ? "Yes" : "No";
        $brgy_kagawad_access = isset($_POST["brgy-kagawad-access"]) ? "Yes" : "No";
        $sk_chairman_access = isset($_POST["sk-chairman-access"]) ? "Yes" : "No";
        $sk_kagawad_access = isset($_POST["sk-kagawad-access"]) ? "Yes" : "No";

        if (empty($folder_id) || empty($folder_name)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../admin/home.php?page=files-and-folders");
            exit();
        } else {
            try {
                $check_folder_name = $conn->prepare("SELECT * FROM admin_folder_tbl WHERE folder_name = :folder_name AND folder_id != :folder_id");
                $check_folder_name->execute([
                    ":folder_name" => $folder_name,
                    ":folder_id" => $folder_id
                ]);

                if ($check_folder_name->rowCount() === 0) {
                    $update_folder = $conn->prepare("UPDATE admin_folder_tbl
                        SET folder_name = :folder_name,
                            brgy_captain_access = :brgy_captain_access,
                            brgy_secretary_access = :brgy_secretary_access,
                            brgy_treasurer_access = :brgy_treasurer_access,
                            brgy_kagawad_access = :brgy_kagawad_access,
                            sk_chairman_access = :sk_chairman_access,
                            sk_kagawad_access = :sk_kagawad_access
                        WHERE folder_id = :folder_id
                    ");

                    $update_folder->execute([
                        ":folder_name" => $folder_name,
                        ":brgy_captain_access" => $brgy_captain_access,
                        ":brgy_secretary_access" => $brgy_secretary_access,
                        ":brgy_treasurer_access" => $brgy_treasurer_access,
                        ":brgy_kagawad_access" => $brgy_kagawad_access,
                        ":sk_chairman_access" => $sk_chairman_access,
                        ":sk_kagawad_access" => $sk_kagawad_access,
                        ":folder_id" => $folder_id
                    ]);

                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "Folder updated successfully!"
                    ];

                    header("Location: ../../admin/home.php?page=files-and-folders");
                    exit();
                } else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "This folder already exists! Please try again."
                    ];

                    header("Location: ../../admin/home.php?page=files-and-folders");
                    exit();
                }
            } catch (PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: ../../admin/home.php?page=files-and-folders");
                exit();
            }
        }
    }

    // Archive Folder
    else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["archive-folder"])) {
        $folder_id = htmlspecialchars(trim(base64_decode($_POST["folder-id"])));

        if (empty($folder_id)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../admin/home.php?page=files-and-folders");
            exit();
        } else {
            try {
                $check_folder = $conn->prepare("SELECT * FROM admin_folder_tbl WHERE folder_id = :folder_id LIMIT 1");
                $check_folder->execute([":folder_id" => $folder_id]);

                if ($check_folder->rowCount() === 1) {
                    $archive_folder = $conn->prepare("UPDATE admin_folder_tbl SET is_archive = :is_archive WHERE folder_id = :folder_id");
                    $archive_folder->execute([":is_archive" => "Yes", ":folder_id" => $folder_id]);

                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "Folder archived successfully!"
                    ];

                    header("Location: ../../admin/home.php?page=files-and-folders");
                    exit();
                } else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Folder not found!"
                    ];

                    header("Location: ../../admin/home.php?page=files-and-folders");
                    exit();
                }
            } catch (PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: ../../admin/home.php?page=files-and-folders");
                exit();
            }
        }
    }

    // Retrieve Folder
    else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["retrieve-folder"])) {
        $folder_id = htmlspecialchars(trim(base64_decode($_POST["folder-id"])));

        if (empty($folder_id)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../admin/home.php?page=archive");
            exit();
        } else {
            try {
                $check_folder = $conn->prepare("SELECT * FROM admin_folder_tbl WHERE folder_id = :folder_id LIMIT 1");
                $check_folder->execute([":folder_id" => $folder_id]);

                if ($check_folder->rowCount() === 1) {
                    $retrieve_folder = $conn->prepare("UPDATE admin_folder_tbl SET is_archive = :is_archive WHERE folder_id = :folder_id");
                    $retrieve_folder->execute([":is_archive" => "No", ":folder_id" => $folder_id]);

                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "Folder retrieved successfully!"
                    ];

                    header("Location: ../../admin/home.php?page=archive");
                    exit();
                } else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Folder not found!"
                    ];

                    header("Location: ../../admin/home.php?page=archive");
                    exit();
                }
            } catch (PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: ../../admin/home.php?page=archive");
                exit();
            }
        }
    }

    // Delete Folder
    else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete-folder"])) {
        $folder_id = htmlspecialchars(trim(base64_decode($_POST["folder-id"])));

        if (empty($folder_id)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../admin/home.php?page=archive");
            exit();
        } else {
            try {
                $check_folder = $conn->prepare("SELECT * FROM admin_folder_tbl WHERE folder_id = :folder_id LIMIT 1");
                $check_folder->execute([":folder_id" => $folder_id]);

                if ($check_folder->rowCount() === 1) {
                    $get_file_contents = $conn->prepare("SELECT
                                                                    *
                                                                FROM admin_files_tbl
                                                                WHERE is_archive = :is_archive AND folder_id = :folder_id");
                    $get_file_contents->execute([
                        ":is_archive" => "No",
                        ":folder_id" => $folder_id
                    ]);

                    while($files = $get_file_contents->fetch()) {
                        unlink($file_directory . $files["stored_file_name"]);
                        unlink($file_directory . $files["processed_file_name"]);

                        $delete_file = $conn->prepare("DELETE FROM admin_files_tbl WHERE file_id = :file_id");
                        $delete_file->execute([":file_id" => $files["file_id"]]);
                    }

                    $delete_folder = $conn->prepare("DELETE FROM admin_folder_tbl WHERE folder_id = :folder_id");
                    $delete_folder->execute([":folder_id" => $folder_id]);

                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "Folder deleted successfully!"
                    ];

                    header("Location: ../../admin/home.php?page=archive");
                    exit();
                } else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Folder not found!"
                    ];

                    header("Location: ../../admin/home.php?page=archive");
                    exit();
                }
            } catch (PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: ../../admin/home.php?page=archive");
                exit();
            }
        }
    }

    // Upload File
    else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["upload-file"])) {

        $file_path = "../../uploads/user-files/";

        $folder_id = htmlspecialchars(trim(base64_decode($_POST["folder-id"])));
        $original_name = basename($_FILES["uploaded-file"]["name"]);
        $file_size = $_FILES["uploaded-file"]["size"];
        $mime_type = mime_content_type($_FILES["uploaded-file"]["tmp_name"]);

        $allow_brgy_captain   = isset($_POST["allow-brgy-captain-download"]) ? "Yes" : "No";
        $allow_brgy_secretary = isset($_POST["allow-brgy-secretary-download"]) ? "Yes" : "No";
        $allow_brgy_treasurer = isset($_POST["allow-brgy-treasurer-download"]) ? "Yes" : "No";
        $allow_brgy_kagawad   = isset($_POST["allow-brgy-kagawad-download"]) ? "Yes" : "No";
        $allow_sk_chairman    = isset($_POST["allow-sk-chairman-download"]) ? "Yes" : "No";
        $allow_sk_kagawad     = isset($_POST["allow-sk-kagawad-download"]) ? "Yes" : "No";

        $encoded_folder_id = base64_encode($folder_id);

        if (isRestrictedFileType($original_name, $mime_type)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "File type not allowed! Please try again."
            ];

            header("Location: ../../admin/home.php?page=folder-contents&folder-id=$encoded_folder_id");
            exit();
        }

        $file_content = file_get_contents($_FILES["uploaded-file"]["tmp_name"]);

        if (!$file_content) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Unable to read file content! Please try again."
            ];

            header("Location: ../../admin/home.php?page=folder-contents&folder-id=$encoded_folder_id");
            exit();
        } else {
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

                header("Location: ../../admin/home.php?page=folder-contents&folder-id=$encoded_folder_id");
                exit();
            } else {
                $iv = "";
                $processed_data = encryptFile($file_content, $aes_key, $cipher_algo, $options, $iv_length, $iv);

                if (file_put_contents($processed_file_path, $processed_data) === false) {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Unable to save the processed file! Please try again."
                    ];

                    header("Location: ../../admin/home.php?page=folder-contents&folder-id=$encoded_folder_id");
                    exit();
                } 
                
                else {
                    $insert_uploaded_files = $conn->prepare("INSERT INTO admin_files_tbl (
                            folder_id, file_name, stored_file_name, processed_file_name, file_size, 
                            brgy_captain_download, brgy_secretary_download, brgy_treasurer_download, 
                            brgy_kagawad_download, sk_chairman_download, sk_kagawad_download
                        ) VALUES (
                            :folder_id, :file_name, :stored_name, :processed_name, :file_size, 
                            :allow_brgy_captain, :allow_brgy_secretary, :allow_brgy_treasurer, 
                            :allow_brgy_kagawad, :allow_sk_chairman, :allow_sk_kagawad
                        )");

                    $insert_uploaded_files->execute([
                        ":folder_id"            => $folder_id,
                        ":file_name"            => $original_name,
                        ":stored_name"          => $original_file,
                        ":processed_name"       => $processed_name,
                        ":file_size"            => $file_size,
                        ":allow_brgy_captain"   => $allow_brgy_captain,
                        ":allow_brgy_secretary" => $allow_brgy_secretary,
                        ":allow_brgy_treasurer" => $allow_brgy_treasurer,
                        ":allow_brgy_kagawad"   => $allow_brgy_kagawad,
                        ":allow_sk_chairman"    => $allow_sk_chairman,
                        ":allow_sk_kagawad"     => $allow_sk_kagawad
                    ]);


                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "File uploaded successfully!"
                    ];

                    header("Location: ../../admin/home.php?page=folder-contents&folder-id=$encoded_folder_id");
                    exit();
                }
            }
        }
    }

    // Update File Permission
    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update-file-permissions"])) {
        $folder_id = htmlspecialchars(trim(base64_decode($_POST["folder-id"])));
        $file_id = htmlspecialchars(trim(base64_decode($_POST["file-id"])));
        $encoded_folder_id = base64_encode($folder_id);

        $allow_brgy_captain   = isset($_POST["allow-brgy-captain-download"]) ? "Yes" : "No";
        $allow_brgy_secretary = isset($_POST["allow-brgy-secretary-download"]) ? "Yes" : "No";
        $allow_brgy_treasurer = isset($_POST["allow-brgy-treasurer-download"]) ? "Yes" : "No";
        $allow_brgy_kagawad   = isset($_POST["allow-brgy-kagawad-download"]) ? "Yes" : "No";
        $allow_sk_chairman    = isset($_POST["allow-sk-chairman-download"]) ? "Yes" : "No";
        $allow_sk_kagawad     = isset($_POST["allow-sk-kagawad-download"]) ? "Yes" : "No";

        if(empty($folder_id) || empty($file_id)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../admin/home.php?page=folder-contents&folder-id=$encoded_folder_id");
            exit();
        }

        else {
            try {
                $check_file = $conn->prepare("SELECT * FROM admin_files_tbl WHERE file_id = :file_id LIMIT 1");
                $check_file->execute([":file_id" => $file_id]);

                if($check_file->rowCount() === 1) {
                    $update_file_permissions = $conn->prepare("UPDATE admin_files_tbl
                                                            SET brgy_captain_download = :allow_brgy_captain,
                                                            brgy_secretary_download = :allow_brgy_secretary,
                                                            brgy_treasurer_download = :allow_brgy_treasurer,
                                                            brgy_kagawad_download = :allow_brgy_kagawad,
                                                            sk_chairman_download = :allow_sk_chairman,
                                                            sk_kagawad_download = :allow_sk_kagawad
                                                            WHERE file_id = :file_id");
                    $update_file_permissions->execute([
                        ":allow_brgy_captain"   => $allow_brgy_captain,
                        ":allow_brgy_secretary" => $allow_brgy_secretary,
                        ":allow_brgy_treasurer" => $allow_brgy_treasurer,
                        ":allow_brgy_kagawad"   => $allow_brgy_kagawad,
                        ":allow_sk_chairman"    => $allow_sk_chairman,
                        ":allow_sk_kagawad"     => $allow_sk_kagawad,
                        ":file_id" => $file_id
                    ]);

                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "File permissions updated successfully!"
                    ];

                    header("Location: ../../admin/home.php?page=folder-contents&folder-id=$encoded_folder_id");
                    exit();
                }

                else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Invalid file! Please try again."
                    ];

                    header("Location: ../../admin/home.php?page=folder-contents&folder-id=$encoded_folder_id");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: ../../admin/home.php?page=folder-contents&folder-id=$encoded_folder_id");
                exit();
            }
        }
    }

    // Archive File
    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["archive-file"])) {
        $folder_id = htmlspecialchars(trim(base64_decode($_POST["folder-id"])));

        $folder_id = isset($_POST["folder-id"])
        ? htmlspecialchars(trim(base64_decode($_POST["folder-id"])))
        : null;

        $file_id = htmlspecialchars(trim(base64_decode($_POST["file-id"])));
        
        $page_path = isset($_POST["folder-id"])
        ? "folder-contents&folder-id=" . base64_encode($folder_id)
        : "archive";


        if(empty($file_id)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../admin/home.php?page=$page_path");
            exit();
        }

        else {
            try {
                $check_file = $conn->prepare("SELECT * FROM admin_files_tbl WHERE file_id = :file_id LIMIT 1");
                $check_file->execute([":file_id" => $file_id]);

                if($check_file->rowCount() === 1) {
                    
                    $archive_file = $conn->prepare("UPDATE admin_files_tbl SET is_archive = :is_archive WHERE file_id = :file_id");
                    $archive_file->execute([":is_archive" => "Yes", ":file_id" => $file_id]);
                    
                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "File archived successfully!"
                    ];

                    header("Location: ../../admin/home.php?page=$page_path");
                    exit();
                }

                else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Invalid file! Please try again."
                    ];

                    header("Location: ../../admin/home.php?page=$page_path");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: ../../admin/home.php?page=$page_path");
                exit();
            }
        }
    }

    // Retrieve File
    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["retrieve-file"])) {

        $file_id = htmlspecialchars(trim(base64_decode($_POST["file-id"])));

        if(empty($file_id)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../admin/home.php?page=archive");
            exit();
        }

        else {
            try {
                $check_file = $conn->prepare("SELECT * FROM admin_files_tbl WHERE file_id = :file_id LIMIT 1");
                $check_file->execute([":file_id" => $file_id]);

                if($check_file->rowCount() === 1) {
                    
                    $retrieve_file = $conn->prepare("UPDATE admin_files_tbl SET is_archive = :is_archive WHERE file_id = :file_id");
                    $retrieve_file->execute([":is_archive" => "No", ":file_id" => $file_id]);
                    
                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "File retrieved successfully!"
                    ];

                    header("Location: ../../admin/home.php?page=archive");
                    exit();
                }

                else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Invalid file! Please try again."
                    ];

                    header("Location: ../../admin/home.php?page=archive");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: ../../admin/home.php?page=archive");
                exit();
            }
        }
    }

    // Assign Folder
    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["assign-folder"])) {
        $file_id = htmlspecialchars(trim(base64_decode($_POST["file-id"])));
        $folder_id = htmlspecialchars(trim($_POST["folder-id"]));

        if(empty($file_id) || empty($folder_id)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../admin/home.php?page=files-and-folders");
            exit();
        }

        else {
            try {
                $check_file = $conn->prepare("SELECT * FROM admin_files_tbl WHERE file_id = :file_id");
                $check_file->execute([":file_id" => $file_id]);

                $check_folder = $conn->prepare("SELECT * FROM admin_folder_tbl WHERE folder_id = :folder_id");
                $check_folder->execute([":folder_id" => $folder_id]);

                if($check_file->rowCount() === 0) {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Invalid file! Please try again."
                    ];

                    header("Location: ../../admin/home.php?page=files-and-folders");
                    exit();
                }

                else if($check_folder->rowCount() === 0) {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Invalid folder! Please try again."
                    ];

                    header("Location: ../../admin/home.php?page=files-and-folders");
                    exit();
                }

                else {
                    $assign_folder = $conn->prepare("UPDATE admin_files_tbl SET folder_id = :folder_id WHERE file_id = :file_id");
                    $assign_folder->execute([
                        ":folder_id" => $folder_id,
                        ":file_id" => $file_id
                    ]);

                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "File assigned to folder successfully!"
                    ];

                    $encoded_folder_id = base64_encode($folder_id);

                    header("Location: ../../admin/home.php?page=folder-contents&folder-id=$encoded_folder_id");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: ../../admin/home.php?page=files-and-folders");
                exit();
            }
            
        }
    }

    // Delete File
    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete-file"])) {

        $file_id = htmlspecialchars(trim(base64_decode($_POST["file-id"])));
        $stored_name = htmlspecialchars(trim(base64_decode($_POST["stored-name"])));
        $processed_name = htmlspecialchars(trim(base64_decode($_POST["processed-name"])));

        if(empty($file_id) || empty($stored_name) || empty($processed_name)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../admin/home.php?page=archive");
            exit();
        }

        else {
            try {
                $check_file = $conn->prepare("SELECT * FROM admin_files_tbl WHERE file_id = :file_id LIMIT 1");
                $check_file->execute([":file_id" => $file_id]);

                if($check_file->rowCount() === 1) {

                    unlink($file_directory . $stored_name);
                    unlink($file_directory . $processed_name);
                    
                    $delete_file = $conn->prepare("DELETE FROM admin_files_tbl WHERE file_id = :file_id");
                    $delete_file->execute([":file_id" => $file_id]);
                    
                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "File deleted successfully!"
                    ];

                    header("Location: ../../admin/home.php?page=archive");
                    exit();
                }

                else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Invalid file! Please try again."
                    ];

                    header("Location: ../../admin/home.php?page=archive");
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: ../../admin/home.php?page=archive");
                exit();
            }
        }
    }

?>
