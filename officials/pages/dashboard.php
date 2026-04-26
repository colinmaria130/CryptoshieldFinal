<?php
    // Folders Count
    $get_folders_count = $conn->prepare("SELECT COUNT(*) AS 'folder_count' FROM officials_folder_tbl WHERE created_by = :official_id AND is_archive = :is_archive");
    $get_folders_count->execute([":official_id" => $official_id, ":is_archive" => "No"]);
    $folder_count = $get_folders_count->fetch()["folder_count"];

    // Uploaded Files Count
    $get_files_count = $conn->prepare("SELECT COUNT(*) AS 'files_count' FROM officials_files_tbl WHERE uploaded_by = :official_id AND folder_id IS NOT NULL");
    $get_files_count->execute([":official_id" => $official_id]);
    $files_count = $get_files_count->fetch()["files_count"];

    // Shared Files Count
    $get_shared_files_count = $conn->prepare("SELECT COUNT(*) AS 'shared_files_count' FROM file_sharing_tbl WHERE sender = :official_id");
    $get_shared_files_count->execute([":official_id" => $official_id]);
    $shared_files_count = $get_shared_files_count->fetch()["shared_files_count"];
?>

<main id="main" class="main">

    <?php include_once "includes/pagetitle.php"; ?>

    <section class="section dashboard">

        <div class="container">

            <!-- Stats Cards -->
            <div class="row gy-4 mb-4">

                <!-- Folders -->
                <div class="col-xxl-4 col-md-6">

                    <div class="card info-card revenue-card">

                        <div class="card-body">
                            <h5 class="card-title"> My Folders </span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-folder"></i>
                                </div>
                                <div class="ps-3">
                                    <h6> <?php echo htmlspecialchars($folder_count); ?> </h6>
                                    <span class="text-muted small pt-2 ps-1"> Folders </span>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Files -->
                <div class="col-xxl-4 col-md-6">

                    <div class="card info-card revenue-card">

                        <div class="card-body">
                            <h5 class="card-title"> Uploaded Files </span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-file-earmark-lock"></i>
                                </div>
                                <div class="ps-3">
                                    <h6> <?php echo htmlspecialchars($files_count); ?> </h6>
                                    <span class="text-muted small pt-2 ps-1"> Files </span>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Shared Files -->
                <div class="col-xxl-4 col-md-6">

                    <div class="card info-card revenue-card">

                        <div class="card-body">
                            <h5 class="card-title"> Shared Files </span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-share"></i>
                                </div>
                                <div class="ps-3">
                                    <h6> <?php echo htmlspecialchars($shared_files_count); ?> </h6>
                                    <span class="text-muted small pt-2 ps-1"> Files </span>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Admin Folders Section -->
            <div class="card shadow-sm mb-4">

                <div class="card-header bg-dark text-white fw-bold fs-3 d-flex justify-content-between">
                    Admin Folders
                </div>

                <div class="card-body mt-3">

                    <div class="row gx-4 gy-4">

                        <?php
                        $get_folders = $conn->prepare("SELECT 
                                                            fl.*,
                                                            COUNT(CASE WHEN fs.is_archive = 'No' THEN 1 END) AS 'files_count'
                                                        FROM admin_folder_tbl fl
                                                        LEFT JOIN admin_files_tbl fs
                                                        ON fl.folder_id = fs.folder_id
                                                        GROUP BY fl.folder_id
                                                        ORDER BY modified_at DESC");
                        $get_folders->execute();

                        if ($get_folders->rowCount() > 0) {
                            while ($folder_data = $get_folders->fetch()) {
                        ?>
                                <div class="col-6 col-md-3">

                                    <div class="card shadow-lg border-0 rounded-4 text-center p-3 folder-card h-100 position-relative" style="transition: 0.3s; cursor: pointer;">

                                        <div class="card-body d-flex flex-column align-items-center justify-content-center">

                                            <!-- Folder Icon -->
                                            <div class="folder-icon mb-1 mt-2">
                                                <i class="bi bi-folder-fill fs-1 text-warning"></i>
                                            </div>

                                            <!-- Folder Name -->
                                            <h5 class="fw-bold mb-0">
                                                <?php echo htmlspecialchars($folder_data["folder_name"]); ?>
                                            </h5>

                                            <!-- File Count -->
                                            <p class="small text-muted mb-3">
                                                <?php echo htmlspecialchars($folder_data["files_count"] . " Files"); ?>
                                            </p>

                                            <?php
                                            if ($folder_data[$admin_folder_permission] === "Yes") {
                                            ?>
                                                <a class="btn btn-primary btn-sm w-100 mb-0" href="home.php?page=admin-folder-contents&folder-id=<?php echo htmlspecialchars(base64_encode($folder_data["folder_id"])); ?>">
                                                    <i class="bi bi-folder2-open me-2 text-light"></i> Open Folder
                                                </a>

                                            <?php
                                            } else {
                                            ?>
                                                <p class="fw-bold text-danger small">
                                                    You can't access this folder.
                                                </p>
                                            <?php
                                            }
                                            ?>

                                        </div>

                                    </div>

                                </div>

                            <?php
                            }
                        } else {
                            ?>
                            <h1 class="text-center mt-5"> No Folders Yet. </h1>
                        <?php
                        }
                        ?>

                    </div>

                </div>

            </div>

            <!-- Officials Folders Section -->
            <div class="card shadow-sm mb-4">

                <div class="card-header bg-dark text-white fw-bold fs-3 d-flex justify-content-between">
                    Brgy. Official's Folders
                </div>

                <div class="card-body mt-3">

                    <div class="row gx-4 gy-4">

                        <?php
                        $get_officials_folders = $conn->prepare("SELECT 
                                                            ofl.*,
                                                            COUNT(CASE WHEN ofs.is_archive = 'No' THEN 1 END) AS 'files_count',
                                                            CONCAT(oa.first_name, ' ', oa.last_name) 'created_by'
                                                        FROM officials_folder_tbl ofl
                                                        LEFT JOIN officials_files_tbl ofs
                                                        ON ofl.folder_id = ofs.folder_id AND ofs.is_archive = :is_archive
                                                        LEFT JOIN officials_accounts_tbl oa
                                                        ON ofl.created_by = oa.official_id
                                                        WHERE ofl.is_archive = 'No'
                                                        GROUP BY ofl.folder_id
                                                        ORDER BY ofl.modified_at DESC");
                        $get_officials_folders->execute([":is_archive" => "No"]);

                        if ($get_officials_folders->rowCount() > 0) {
                            while ($folder_data = $get_officials_folders->fetch()) {
                        ?>
                                <div class="col-6 col-md-3">

                                    <div class="card shadow-lg border-0 rounded-4 text-center p-3 folder-card h-100 position-relative" style="transition: 0.3s; cursor: pointer;">

                                        <div class="card-body d-flex flex-column align-items-center justify-content-center">

                                            <!-- Folder Icon -->
                                            <div class="folder-icon mb-1 mt-2">
                                                <i class="bi bi-folder-fill fs-1 text-warning"></i>
                                            </div>

                                            <!-- Folder Name -->
                                            <h5 class="fw-bold mb-0">
                                                <?php echo htmlspecialchars($folder_data["folder_name"]); ?>
                                            </h5>

                                            <!-- File Count -->
                                            <p class="small text-muted mb-0">
                                                <?php echo htmlspecialchars($folder_data["files_count"] . " Files"); ?>
                                            </p>

                                            <!-- File Count -->
                                            <p class="small text-muted mb-3">
                                                Created By: <?php echo htmlspecialchars($folder_data["created_by"]); ?>
                                            </p>

                                            <?php
                                            if ($folder_data[$admin_folder_permission] === "Yes") {
                                            ?>
                                                <a class="btn btn-primary btn-sm w-100 mb-0" href="home.php?page=folder-contents&folder-id=<?php echo htmlspecialchars(base64_encode($folder_data["folder_id"])); ?>">
                                                    <i class="bi bi-folder2-open me-2 text-light"></i> Open Folder
                                                </a>

                                            <?php
                                            } else {
                                            ?>
                                                <p class="fw-bold text-danger small">
                                                    You can't access this folder.
                                                </p>
                                            <?php
                                            }
                                            ?>

                                        </div>

                                    </div>

                                </div>

                            <?php
                            }
                        } else {
                            ?>
                            <h1 class="text-center mt-5"> No Folders Yet. </h1>
                        <?php
                        }
                        ?>

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>