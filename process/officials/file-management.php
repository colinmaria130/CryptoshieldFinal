<?php
    require_once "../../config/conn.php";
    require_once "encryption-process.php";

    $file_directory = "../../uploads/user-files/";

    // Add Folder
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save-folder"])) {
        $official_id = htmlspecialchars(trim($_SESSION["official-id"]));
        $folder_name = htmlspecialchars($_POST["folder-name"]);
        $position = htmlspecialchars($_POST["position"]);

        if($position === "Brgy. Captain" || $position === "S.K. Chairman") {
            $brgy_secretary_access = isset($_POST["brgy-secretary-access"]) ? "Yes" : "No";
            $brgy_treasurer_access = isset($_POST["brgy-treasurer-access"]) ? "Yes" : "No";
            $brgy_kagawad_access = isset($_POST["brgy-kagawad-access"]) ? "Yes" : "No";
            $sk_chairman_access = isset($_POST["sk-chairman-access"]) ? "Yes" : "Yes";
            $sk_kagawad_access = isset($_POST["sk-kagawad-access"]) ? "Yes" : "No";
        }

        else if($position === "Brgy. Secretary" || $position === "Brgy. Treasurer") {
            $brgy_secretary_access = isset($_POST["brgy-secretary-access"]) ? "Yes" : "Yes";
            $brgy_treasurer_access = isset($_POST["brgy-treasurer-access"]) ? "Yes" : "Yes";
            $brgy_kagawad_access = isset($_POST["brgy-kagawad-access"]) ? "Yes" : "No";
            $sk_chairman_access = isset($_POST["sk-chairman-access"]) ? "Yes" : "Yes";
            $sk_kagawad_access = isset($_POST["sk-kagawad-access"]) ? "Yes" : "No";
        }

        else {
            $brgy_secretary_access = isset($_POST["brgy-secretary-access"]) ? "Yes" : "Yes";
            $brgy_treasurer_access = isset($_POST["brgy-treasurer-access"]) ? "Yes" : "Yes";
            $brgy_kagawad_access = isset($_POST["brgy-kagawad-access"]) ? "Yes" : "Yes";
            $sk_chairman_access = isset($_POST["sk-chairman-access"]) ? "Yes" : "Yes";
            $sk_kagawad_access = isset($_POST["sk-kagawad-access"]) ? "Yes" : "Yes";
        }

        if (empty($folder_name)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../officials/home.php?page=my-files-and-folders");
            exit();
        } 
        
        else {
            try {
                $check_existing_folder = $conn->prepare("SELECT * FROM officials_folder_tbl WHERE folder_name = :folder_name AND created_by = :official_id LIMIT 1");
                $check_existing_folder->execute([":folder_name" => $folder_name, ":official_id" => $official_id]);

                if ($check_existing_folder->rowCount() === 0) {

                    $create_folder = $conn->prepare("
                        INSERT INTO officials_folder_tbl
                        (folder_name, brgy_secretary_access, brgy_treasurer_access, brgy_kagawad_access, sk_chairman_access, sk_kagawad_access, created_by)
                        VALUES
                        (:folder_name, :brgy_secretary, :brgy_treasurer, :brgy_kagawad, :sk_chairman, :sk_kagawad, :official_id)
                    ");

                    $create_folder->execute([
                        ":folder_name"    => $folder_name,
                        ":brgy_secretary" => $brgy_secretary_access,
                        ":brgy_treasurer" => $brgy_treasurer_access,
                        ":brgy_kagawad"   => $brgy_kagawad_access,
                        ":sk_chairman"    => $sk_chairman_access,
                        ":sk_kagawad"     => $sk_kagawad_access,
                        ":official_id" => $official_id
                    ]);

                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "Folder added successfully!"
                    ];

                    header("Location: ../../officials/home.php?page=my-files-and-folders");
                    exit();
                } 
                
                else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "This folder already exists! Please try again."
                    ];

                    header("Location: ../../officials/home.php?page=my-files-and-folders");
                    exit();
                }
            } 
            
            catch (PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: ../../officials/home.php?page=my-files-and-folders");
                exit();
            }
        }
    }

    // Update Folder
    else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update-folder"])) {
        $official_id = htmlspecialchars(trim($_SESSION["official-id"]));
        $folder_id = htmlspecialchars(trim(base64_decode($_POST["folder-id"])));
        $folder_name = htmlspecialchars($_POST["folder-name"]);
        $position = htmlspecialchars($_POST["position"]);

        if($position === "Brgy. Captain" || $position === "S.K. Chairman") {
            $brgy_secretary_access = isset($_POST["brgy-secretary-access"]) ? "Yes" : "No";
            $brgy_treasurer_access = isset($_POST["brgy-treasurer-access"]) ? "Yes" : "No";
            $brgy_kagawad_access = isset($_POST["brgy-kagawad-access"]) ? "Yes" : "No";
            $sk_chairman_access = isset($_POST["sk-chairman-access"]) ? "Yes" : "Yes";
            $sk_kagawad_access = isset($_POST["sk-kagawad-access"]) ? "Yes" : "No";
        }

        else if($position === "Brgy. Secretary" || $position === "Brgy. Treasurer") {
            $brgy_secretary_access = isset($_POST["brgy-secretary-access"]) ? "Yes" : "Yes";
            $brgy_treasurer_access = isset($_POST["brgy-treasurer-access"]) ? "Yes" : "Yes";
            $brgy_kagawad_access = isset($_POST["brgy-kagawad-access"]) ? "Yes" : "No";
            $sk_chairman_access = isset($_POST["sk-chairman-access"]) ? "Yes" : "Yes";
            $sk_kagawad_access = isset($_POST["sk-kagawad-access"]) ? "Yes" : "No";
        }

        else {
            $brgy_secretary_access = isset($_POST["brgy-secretary-access"]) ? "Yes" : "Yes";
            $brgy_treasurer_access = isset($_POST["brgy-treasurer-access"]) ? "Yes" : "Yes";
            $brgy_kagawad_access = isset($_POST["brgy-kagawad-access"]) ? "Yes" : "Yes";
            $sk_chairman_access = isset($_POST["sk-chairman-access"]) ? "Yes" : "Yes";
            $sk_kagawad_access = isset($_POST["sk-kagawad-access"]) ? "Yes" : "Yes";
        }

        if (empty($folder_id) || empty($folder_name)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: ../../officials/home.php?page=my-files-and-folders");
            exit();
        } 
        
        else {
            try {
                $check_folder_name = $conn->prepare("SELECT * FROM officials_folder_tbl WHERE folder_name = :folder_name AND created_by = :official_id AND folder_id != :folder_id");
                $check_folder_name->execute([
                    ":folder_name" => $folder_name,
                    ":official_id" => $official_id,
                    ":folder_id" => $folder_id
                ]);

                if ($check_folder_name->rowCount() === 0) {
                    $update_folder = $conn->prepare("UPDATE officials_folder_tbl
                        SET folder_name = :folder_name,
                            brgy_secretary_access = :brgy_secretary_access,
                            brgy_treasurer_access = :brgy_treasurer_access,
                            brgy_kagawad_access = :brgy_kagawad_access,
                            sk_chairman_access = :sk_chairman_access,
                            sk_kagawad_access = :sk_kagawad_access
                        WHERE folder_id = :folder_id
                    ");

                    $update_folder->execute([
                        ":folder_name" => $folder_name,
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

                    header("Location: ../../officials/home.php?page=my-files-and-folders");
                    exit();
                } 
                
                else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "This folder already exists! Please try again."
                    ];

                    header("Location: ../../officials/home.php?page=my-files-and-folders");
                    exit();
                }
            } 
            
            catch (PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again." . $e->getMessage()
                ];

                header("Location: ../../officials/home.php?page=my-files-and-folders");
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

            header("Location: ../../officials/home.php?page=my-files-and-folders");
            exit();
        } 
        
        else {
            try {
                $check_folder = $conn->prepare("SELECT * FROM officials_folder_tbl WHERE folder_id = :folder_id");
                $check_folder->execute([
                    ":folder_id" => $folder_id
                ]);

                if ($check_folder->rowCount() === 1) {
                    
                    $archive_folder = $conn->prepare("UPDATE officials_folder_tbl SET is_archive = :is_archive WHERE folder_id = :folder_id");
                    $archive_folder->execute([":is_archive" => "Yes", ":folder_id" => $folder_id]);

                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "Folder archived successfully!"
                    ];

                    header("Location: ../../officials/home.php?page=my-files-and-folders");
                    exit();
                } 
                
                else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Folder not found! Please try again."
                    ];

                    header("Location: ../../officials/home.php?page=my-files-and-folders");
                    exit();
                }
            } 
            
            catch (PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again." . $e->getMessage()
                ];

                header("Location: ../../officials/home.php?page=my-files-and-folders");
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
        } 
        
        else {
            try {
                $check_folder = $conn->prepare("SELECT * FROM officials_folder_tbl WHERE folder_id = :folder_id");
                $check_folder->execute([
                    ":folder_id" => $folder_id
                ]);

                if ($check_folder->rowCount() === 1) {
                    
                    $archive_folder = $conn->prepare("UPDATE officials_folder_tbl SET is_archive = :is_archive WHERE folder_id = :folder_id");
                    $archive_folder->execute([":is_archive" => "No", ":folder_id" => $folder_id]);

                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "Folder archived successfully!"
                    ];

                    header("Location: ../../admin/home.php?page=archive");
                    exit();
                } 
                
                else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Folder not found! Please try again."
                    ];

                    header("Location: ../../admin/home.php?page=archive");
                    exit();
                }
            } 
            
            catch (PDOException $e) {
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
        } 
        
        else {
            try {
                $check_folder = $conn->prepare("SELECT * FROM officials_folder_tbl WHERE folder_id = :folder_id LIMIT 1");
                $check_folder->execute([":folder_id" => $folder_id]);

                if ($check_folder->rowCount() === 1) {
                    $get_file_contents = $conn->prepare("SELECT
                                                                    *
                                                                FROM officials_files_tbl
                                                                WHERE is_archive = :is_archive AND folder_id = :folder_id");
                    $get_file_contents->execute([
                        ":is_archive" => "No",
                        ":folder_id" => $folder_id
                    ]);

                    while($files = $get_file_contents->fetch()) {
                        unlink($file_directory . $files["stored_file_name"]);
                        unlink($file_directory . $files["processed_file_name"]);

                        $delete_file = $conn->prepare("DELETE FROM officials_files_tbl WHERE file_id = :file_id");
                        $delete_file->execute([":file_id" => $files["file_id"]]);
                    }

                    $delete_folder = $conn->prepare("DELETE FROM officials_folder_tbl WHERE folder_id = :folder_id");
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
                    "message" => "An unknown error occured! Please try again." .$e->getMessage()
                ];

                header("Location: ../../admin/home.php?page=archive");
                exit();
            }
        }
    }

    // Upload File
    else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["upload-file"])) {

        $file_path = "../../uploads/user-files/";

        $official_id = htmlspecialchars(trim($_SESSION["official-id"]));
        $folder_id = htmlspecialchars(trim(base64_decode($_POST["folder-id"])));
        $original_name = basename($_FILES["uploaded-file"]["name"]);
        $file_size = $_FILES["uploaded-file"]["size"];
        $mime_type = mime_content_type($_FILES["uploaded-file"]["tmp_name"]);

        $position = htmlspecialchars($_POST["position"]);

        if($position === "Brgy. Captain" || $position === "S.K. Chairman") {
            $allow_brgy_secretary = isset($_POST["allow-brgy-secretary-download"]) ? "Yes" : "No";
            $allow_brgy_treasurer = isset($_POST["allow-brgy-treasurer-download"]) ? "Yes" : "No";
            $allow_brgy_kagawad   = isset($_POST["allow-brgy-kagawad-download"]) ? "Yes" : "No";
            $allow_sk_chairman    = isset($_POST["allow-sk-chairman-download"]) ? "Yes" : "Yes";
            $allow_sk_kagawad     = isset($_POST["allow-sk-kagawad-download"]) ? "Yes" : "No";
        }

        else if($position === "Brgy. Secretary" || $position === "Brgy. Treasurer") {
            $allow_brgy_secretary = isset($_POST["allow-brgy-secretary-download"]) ? "Yes" : "Yes";
            $allow_brgy_treasurer = isset($_POST["allow-brgy-treasurer-download"]) ? "Yes" : "Yes";
            $allow_brgy_kagawad   = isset($_POST["allow-brgy-kagawad-download"]) ? "Yes" : "No";
            $allow_sk_chairman    = isset($_POST["allow-sk-chairman-download"]) ? "Yes" : "Yes";
            $allow_sk_kagawad     = isset($_POST["allow-sk-kagawad-download"]) ? "Yes" : "No";
        }

        else {
            $allow_brgy_secretary = isset($_POST["allow-brgy-secretary-download"]) ? "Yes" : "Yes";
            $allow_brgy_treasurer = isset($_POST["allow-brgy-treasurer-download"]) ? "Yes" : "Yes";
            $allow_brgy_kagawad   = isset($_POST["allow-brgy-kagawad-download"]) ? "Yes" : "Yes";
            $allow_sk_chairman    = isset($_POST["allow-sk-chairman-download"]) ? "Yes" : "Yes";
            $allow_sk_kagawad     = isset($_POST["allow-sk-kagawad-download"]) ? "Yes" : "Yes";
        }

        $encoded_folder_id = base64_encode($folder_id);

        if (isRestrictedFileType($original_name, $mime_type)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "File type not allowed! Please try again."
            ];

            ?>
                <script>
                    window.history.back();
                </script>
            <?php
            exit();
        }

        $file_content = file_get_contents($_FILES["uploaded-file"]["tmp_name"]);

        if (!$file_content) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Unable to read file content! Please try again."
            ];

            ?>
                <script>
                    window.history.back();
                </script>
            <?php
            exit();
        } 
        
        else {
            $unique_id = uniqid();
            $original_file = $unique_id . "_original_" . $original_name;
            $processed_name = $unique_id . "_processed_" . $original_name;

            $original_file_path = $file_path . $original_file;
            $processed_file_path = $file_path . $processed_name;

            $file_size = number_format($file_size / 1048576, 2);

            if (!copy($_FILES["uploaded-file"]["tmp_name"], $original_file_path)) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "Unable to save the original file! Please try again."
                ];

                ?>
                    <script>
                        window.history.back();
                    </script>
                <?php
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

                    ?>
                        <script>
                            window.history.back();
                        </script>
                    <?php
                    exit();
                } 
                
                else {
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
                        ":allow_brgy_secretary" => $allow_brgy_secretary,
                        ":allow_brgy_treasurer" => $allow_brgy_treasurer,
                        ":allow_brgy_kagawad"   => $allow_brgy_kagawad,
                        ":allow_sk_chairman"    => $allow_sk_chairman,
                        ":allow_sk_kagawad"     => $allow_sk_kagawad,
                        ":uploaded_by" => $official_id
                    ]);


                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "File uploaded successfully!"
                    ];

                    ?>
                        <script>
                            window.history.back();
                        </script>
                    <?php
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
        $position = htmlspecialchars($_POST["position"]);

        if($position === "Brgy. Captain" || $position === "S.K. Chairman") {
            $allow_brgy_secretary = isset($_POST["allow-brgy-secretary-download"]) ? "Yes" : "No";
            $allow_brgy_treasurer = isset($_POST["allow-brgy-treasurer-download"]) ? "Yes" : "No";
            $allow_brgy_kagawad   = isset($_POST["allow-brgy-kagawad-download"]) ? "Yes" : "No";
            $allow_sk_chairman    = isset($_POST["allow-sk-chairman-download"]) ? "Yes" : "Yes";
            $allow_sk_kagawad     = isset($_POST["allow-sk-kagawad-download"]) ? "Yes" : "No";
        }

        else if($position === "Brgy. Secretary" || $position === "Brgy. Treasurer") {
            $allow_brgy_secretary = isset($_POST["allow-brgy-secretary-download"]) ? "Yes" : "Yes";
            $allow_brgy_treasurer = isset($_POST["allow-brgy-treasurer-download"]) ? "Yes" : "Yes";
            $allow_brgy_kagawad   = isset($_POST["allow-brgy-kagawad-download"]) ? "Yes" : "No";
            $allow_sk_chairman    = isset($_POST["allow-sk-chairman-download"]) ? "Yes" : "Yes";
            $allow_sk_kagawad     = isset($_POST["allow-sk-kagawad-download"]) ? "Yes" : "No";
        }

        else {
            $allow_brgy_secretary = isset($_POST["allow-brgy-secretary-download"]) ? "Yes" : "Yes";
            $allow_brgy_treasurer = isset($_POST["allow-brgy-treasurer-download"]) ? "Yes" : "Yes";
            $allow_brgy_kagawad   = isset($_POST["allow-brgy-kagawad-download"]) ? "Yes" : "Yes";
            $allow_sk_chairman    = isset($_POST["allow-sk-chairman-download"]) ? "Yes" : "Yes";
            $allow_sk_kagawad     = isset($_POST["allow-sk-kagawad-download"]) ? "Yes" : "Yes";
        }

        if(empty($folder_id) || empty($file_id)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        else {
            try {
                $check_file = $conn->prepare("SELECT * FROM officials_files_tbl WHERE file_id = :file_id LIMIT 1");
                $check_file->execute([":file_id" => $file_id]);

                if($check_file->rowCount() === 1) {
                    $update_file_permissions = $conn->prepare("UPDATE officials_files_tbl
                                                            SET
                                                            brgy_secretary_download = :allow_brgy_secretary,
                                                            brgy_treasurer_download = :allow_brgy_treasurer,
                                                            brgy_kagawad_download = :allow_brgy_kagawad,
                                                            sk_chairman_download = :allow_sk_chairman,
                                                            sk_kagawad_download = :allow_sk_kagawad
                                                            WHERE file_id = :file_id");
                    $update_file_permissions->execute([
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

                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();

                }

                else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Invalid file! Please try again."
                    ];

                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            }
        }
    }

    // Archive File
    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["archive-file"])) {

        $file_id = htmlspecialchars(trim(base64_decode($_POST["file-id"])));

        if(empty($file_id)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: " . $_SERVER["HTTP_REFERER"]);
            exit();
        }

        else {
            try {
                $check_file = $conn->prepare("SELECT * FROM officials_files_tbl WHERE file_id = :file_id LIMIT 1");
                $check_file->execute([":file_id" => $file_id]);

                if($check_file->rowCount() === 1) {
                    
                    $archive_file = $conn->prepare("UPDATE officials_files_tbl SET is_archive = :is_archive WHERE file_id = :file_id");
                    $archive_file->execute([":is_archive" => "Yes", ":file_id" => $file_id]);
                    
                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "File archived successfully! Please contact your admin to retrieve your file."
                    ];

                    header("Location: " . $_SERVER["HTTP_REFERER"]);
                    exit();
                }

                else {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Invalid file! Please try again."
                    ];

                    header("Location: " . $_SERVER["HTTP_REFERER"]);
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: " . $_SERVER["HTTP_REFERER"]);
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
                $check_file = $conn->prepare("SELECT * FROM officials_files_tbl WHERE file_id = :file_id LIMIT 1");
                $check_file->execute([":file_id" => $file_id]);

                if($check_file->rowCount() === 1) {
                    
                    $retrieve_file = $conn->prepare("UPDATE officials_files_tbl SET is_archive = :is_archive WHERE file_id = :file_id");
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
                $check_file = $conn->prepare("SELECT * FROM officials_files_tbl WHERE file_id = :file_id LIMIT 1");
                $check_file->execute([":file_id" => $file_id]);

                if($check_file->rowCount() === 1) {

                    unlink($file_directory . $stored_name);
                    unlink($file_directory . $processed_name);
                    
                    $delete_file = $conn->prepare("DELETE FROM officials_files_tbl WHERE file_id = :file_id");
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

    // Assign Folder
    else if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["assign-folder"])) {
        $file_id = htmlspecialchars(trim(base64_decode($_POST["file-id"])));
        $folder_id = htmlspecialchars(trim($_POST["folder-id"]));

        if(empty($file_id) || empty($folder_id)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "Please fill in all fields."
            ];

            header("Location: " . $_SERVER["HTTP_REFERER"]);
            exit();
        }

        else {
            try {
                $check_file = $conn->prepare("SELECT * FROM officials_files_tbl WHERE file_id = :file_id");
                $check_file->execute([":file_id" => $file_id]);

                $check_folder = $conn->prepare("SELECT * FROM officials_folder_tbl WHERE folder_id = :folder_id");
                $check_folder->execute([":folder_id" => $folder_id]);

                if($check_file->rowCount() === 0) {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Invalid file! Please try again."
                    ];

                    header("Location: " . $_SERVER["HTTP_REFERER"]);
                    exit();
                }

                else if($check_folder->rowCount() === 0) {
                    $_SESSION["query-status"] = [
                        "status" => "danger",
                        "message" => "Invalid folder! Please try again."
                    ];

                    header("Location: " . $_SERVER["HTTP_REFERER"]);
                    exit();
                }

                else {
                    $assign_folder = $conn->prepare("UPDATE officials_files_tbl SET folder_id = :folder_id WHERE file_id = :file_id");
                    $assign_folder->execute([
                        ":folder_id" => $folder_id,
                        ":file_id" => $file_id
                    ]);

                    $_SESSION["query-status"] = [
                        "status" => "success",
                        "message" => "File assigned to folder successfully!"
                    ];

                    header("Location: " . $_SERVER["HTTP_REFERER"]);
                    exit();
                }
            }

            catch(PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "An unknown error occured! Please try again."
                ];

                header("Location: " . $_SERVER["HTTP_REFERER"]);
                exit();
            }
            
        }
    }

?>
