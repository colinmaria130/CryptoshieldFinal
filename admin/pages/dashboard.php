<?php
    // Brgy. Captain Count
    $get_brgy_captain_count = $conn->prepare("SELECT COUNT(*) AS 'captain_count' FROM officials_accounts_tbl WHERE position = :position");
    $get_brgy_captain_count->execute([":position" => "Brgy. Captain"]);
    $brgy_captain_count = $get_brgy_captain_count->fetch()["captain_count"];

    // Brgy. Secretary Count
    $get_brgy_secretary_count = $conn->prepare("SELECT COUNT(*) AS 'secretary_count' FROM officials_accounts_tbl WHERE position = :position");
    $get_brgy_secretary_count->execute([":position" => "Brgy. Secretary"]);
    $brgy_secretary_count = $get_brgy_secretary_count->fetch()["secretary_count"];

    // Brgy. Kagawad Count
    $get_brgy_kagawad_count = $conn->prepare("SELECT COUNT(*) AS 'kagawad_count' FROM officials_accounts_tbl WHERE position = :position");
    $get_brgy_kagawad_count->execute([":position" => "Brgy. Kagawad"]);
    $brgy_kagawad_count = $get_brgy_kagawad_count->fetch()["kagawad_count"];

    // Folders Count
    $get_folders_count = $conn->prepare("SELECT COUNT(*) AS 'folder_count' FROM admin_folder_tbl");
    $get_folders_count->execute();
    $folder_count = $get_folders_count->fetch()["folder_count"];

    // S.K. Chairman Count
    $get_chairman_count = $conn->prepare("SELECT COUNT(*) AS 'chairman_count' FROM officials_accounts_tbl WHERE position = :position");
    $get_chairman_count->execute([":position" => "S.K. Chairman"]);
    $chairman_count = $get_chairman_count->fetch()["chairman_count"];

    // Brgy. Treasurer Count
    $get_brgy_treasurer_count = $conn->prepare("SELECT COUNT(*) AS 'treasurer_count' FROM officials_accounts_tbl WHERE position = :position");
    $get_brgy_treasurer_count->execute([":position" => "Brgy. Treasurer"]);
    $brgy_treasurer_count = $get_brgy_treasurer_count->fetch()["treasurer_count"];

    // S.K. Kagawad Count
    $get_sk_kagawad_count = $conn->prepare("SELECT COUNT(*) AS 'sk_kagawad_count' FROM officials_accounts_tbl WHERE position = :position");
    $get_sk_kagawad_count->execute([":position" => "S.K. Kagawad"]);
    $sk_kagawad_count = $get_sk_kagawad_count->fetch()["sk_kagawad_count"];

    // Files Count
    $get_files_count = $conn->prepare("SELECT COUNT(*) AS 'files_count' FROM admin_files_tbl");
    $get_files_count->execute();
    $files_count = $get_files_count->fetch()["files_count"];
?>

<main id="main" class="main">

    <?php include_once "includes/pagetitle.php"; ?>

    <section class="section dashboard">

        <div class="container">

            <!-- Stats Cards -->
            <div class="row gy-4 mb-4">

                <!-- Brgy. Captain -->
                <div class="col-xxl-3 col-md-6">

                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title"> Brgy. Captain </span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-fill-check"></i>
                                </div>
                                <div class="ps-3">
                                    <h6> <?php echo htmlspecialchars($brgy_captain_count); ?> </h6>
                                    <span class="text-muted small pt-2 ps-1"> Account </span>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Brgy. Secretary -->
                <div class="col-xxl-3 col-md-6">

                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title"> Brgy. Secretary </span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-journal-text"></i>
                                </div>
                                <div class="ps-3">
                                    <h6> <?php echo htmlspecialchars($brgy_secretary_count); ?> </h6>
                                    <span class="text-muted small pt-2 ps-1"> Account </span>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Brgy. Kagawad -->
                <div class="col-xxl-3 col-md-6">

                    <div class="card info-card customers-card">

                        <div class="card-body">
                            <h5 class="card-title"> Brgy. Kagawad </span></h5>

                            <div class="d-flex align-items-center">

                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people-fill"></i>
                                </div>

                                <div class="ps-3">
                                    <h6> <?php echo htmlspecialchars($brgy_kagawad_count); ?> </h6>
                                    <span class="text-muted small pt-2 ps-1"> Accounts </span>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Folders -->
                <div class="col-xxl-3 col-md-6">

                    <div class="card info-card revenue-card">

                        <div class="card-body">
                            <h5 class="card-title"> Folders </span></h5>

                            <div class="d-flex align-items-center">

                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-folder-check"></i>
                                </div>

                                <div class="ps-3">
                                    <h6> <?php echo htmlspecialchars($folder_count); ?> </h6>
                                    <span class="text-muted small pt-2 ps-1"> Folders </span>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- S.K. Chairman -->
                <div class="col-xxl-3 col-md-6">

                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title"> S.K. Chairman </span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-check"></i>
                                </div>
                                <div class="ps-3">
                                    <h6> <?php echo htmlspecialchars($chairman_count); ?> </h6>
                                    <span class="text-muted small pt-2 ps-1"> Accounts </span>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Brgy. Treasurer -->
                <div class="col-xxl-3 col-md-6">

                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title"> Brgy. Treasurer </span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="ps-3">
                                    <h6> <?php echo htmlspecialchars($brgy_treasurer_count); ?> </h6>
                                    <span class="text-muted small pt-2 ps-1"> Accounts </span>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- S.K. Kagawad -->
                <div class="col-xxl-3 col-md-6">

                    <div class="card info-card customers-card">

                        <div class="card-body">
                            <h5 class="card-title"> S.K. Kagawad </span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-lines-fill"></i>
                                </div>
                                <div class="ps-3">
                                    <h6> <?php echo htmlspecialchars($sk_kagawad_count); ?> </h6>
                                    <span class="text-muted small pt-2 ps-1"> Accounts </span>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Uploaded Files -->
                <div class="col-xxl-3 col-md-6">

                    <div class="card info-card revenue-card">

                        <div class="card-body">
                            <h5 class="card-title"> Uploaded Files </span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-file-check"></i>
                                </div>
                                <div class="ps-3">
                                    <h6> <?php echo htmlspecialchars($files_count); ?> </h6>
                                    <span class="text-muted small pt-2 ps-1"> Files </span>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Folders Section -->
            <div class="card shadow-sm mb-4">

                <div class="card-header bg-dark text-white fw-bold fs-3 d-flex justify-content-between">
                    My Folders
                </div>

                <div class="card-body mt-3">

                    <div class="row gx-4 gy-4">

                        <?php
                        $get_folders = $conn->prepare("SELECT 
                                                            fl.*,
                                                            COUNT(fs.file_id) AS 'files_count'
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
                                            <p class="small text-muted mb-0">
                                                <?php echo htmlspecialchars($folder_data["files_count"] . " Files"); ?>
                                            </p>

                                            <a class="mt-2 btn btn-primary btn-sm w-100 mb-0" href="home.php?page=folder-contents&folder-id=<?php echo htmlspecialchars(base64_encode($folder_data["folder_id"])); ?>">
                                                <i class="bi bi-folder2-open me-2 text-light"></i> Open Folder
                                            </a>

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

            <!-- Folders Section -->
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

                                            <a class="btn btn-primary btn-sm w-100 mb-0" href="home.php?page=officials-folder-contents&folder-id=<?php echo htmlspecialchars(base64_encode($folder_data["folder_id"])); ?>">
                                                <i class="bi bi-folder2-open me-2 text-light"></i> Open Folder
                                            </a>

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