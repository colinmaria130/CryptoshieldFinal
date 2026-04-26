<main class="main" id="main">
    <?php include_once "includes/pagetitle.php" ?>

    <section class="section">

        <!-- Admin Folders Section -->
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-dark text-white fw-bold fs-3 d-flex justify-content-between">

                Admin Archived Folders

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
                                                        WHERE fl.is_archive = :is_archive
                                                        GROUP BY fl.folder_id
                                                        ORDER BY modified_at DESC");
                    $get_folders->execute([":is_archive" => "Yes"]);

                    if ($get_folders->rowCount() > 0) {
                        while ($folder_data = $get_folders->fetch()) {
                    ?>
                            <div class="col-6 col-md-3">

                                <div class="card shadow-lg border-0 rounded-4 text-center p-3 folder-card h-100 position-relative" style="transition: 0.3s; cursor: pointer;">

                                    <!-- Three-dot dropdown -->
                                    <div class="dropdown position-absolute top-0 end-0 m-2">

                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" type="button" id="folderMenuBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="folderMenuBtn">

                                            <li>
                                                <form action="../process/admin/file-management.php" method="POST" id="retrieve-folder-form">
                                                    <input type="hidden" name="folder-id" value="<?php echo htmlspecialchars(base64_encode($folder_data["folder_id"])); ?>">
                                                    <input type="hidden" name="retrieve-folder" value="1">

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item"
                                                        onclick="confirmAction(
                                                    event, 
                                                    this.form, 
                                                    'retrieve-folder-form',
                                                    'Retrieve Folder, <?php echo htmlspecialchars($folder_data['folder_name']); ?>?', 
                                                    'warning',
                                                    'Are you sure you want to retrieve this folder and its contents?',
                                                    'Retrieve',
                                                    '#198754'
                                                    )"
                                                        title="Retrieve Folder">
                                                        <i class="bi bi-arrow-counterclockwise text-success"></i> Retrieve
                                                    </button>
                                                </form>
                                            </li>

                                            <li>
                                                <form action="../process/admin/file-management.php" method="POST" id="delete-folder-form">
                                                    <input type="hidden" name="folder-id" value="<?php echo htmlspecialchars(base64_encode($folder_data["folder_id"])); ?>">
                                                    <input type="hidden" name="delete-folder" value="1">

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item"
                                                        onclick="confirmAction(
                                                    event, 
                                                    this.form, 
                                                    'delete-folder-form',
                                                    'Delete Folder, <?php echo htmlspecialchars($folder_data['folder_name']); ?>?', 
                                                    'warning',
                                                    'Are you sure you want to delete this folder permanently?',
                                                    'Delete',
                                                    '#dc3545'
                                                    )"
                                                        title="Delete Folder">
                                                        <i class="bi bi-trash text-danger"></i> Delete
                                                    </button>
                                                </form>
                                            </li>

                                        </ul>

                                    </div>

                                    <div class="card-body d-flex flex-column align-items-center justify-content-center">

                                        <!-- Folder Icon -->
                                        <div class="folder-icon mb-3">
                                            <i class="bi bi-folder-fill fs-1 text-warning"></i>
                                        </div>

                                        <!-- Folder Name -->
                                        <h5 class="fw-bold mb-1">
                                            <?php echo htmlspecialchars($folder_data["folder_name"]); ?>
                                        </h5>

                                        <!-- File Count -->
                                        <p class="small text-muted mb-3">
                                            <?php echo htmlspecialchars($folder_data["files_count"] . " Files"); ?>
                                        </p>

                                        <!-- Access Status -->
                                        <div class="access-status w-100 text-start px-3">

                                            <p class="mb-1 fw-semibold text-secondary fs-6"> Allow Access To: </p>

                                            <?php
                                            // Map DB columns to display names
                                            $accessRoles = [
                                                'brgy_captain_access'   => 'Brgy. Captain',
                                                'brgy_secretary_access' => 'Brgy. Secretary',
                                                'brgy_treasurer_access' => 'Brgy. Treasurer',
                                                'brgy_kagawad_access'   => 'Brgy. Kagawad',
                                                'sk_chairman_access'    => 'S.K. Chairman',
                                                'sk_kagawad_access'     => 'S.K. Kagawad'
                                            ];

                                            $hasAccess = false;
                                            foreach ($accessRoles as $column => $label) {
                                                if (!empty($folder_data[$column]) && strtolower($folder_data[$column]) === 'yes') {
                                                    $hasAccess = true;
                                                    echo '<p class="mb-0 text-success" style="font-size: 13px;">&#10003; ' . htmlspecialchars($label) . '</p>';
                                                }
                                            }

                                            if (!$hasAccess) {
                                                echo '<span class="text-muted">None</span>';
                                            }
                                            ?>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        <?php
                        }
                    } else {
                        ?>
                        <h1 class="text-center mt-5"> No Archived Folders. </h1>
                    <?php
                    }
                    ?>

                </div>

            </div>

        </div>

        <!-- Admin Files Section -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-dark text-white fw-bold fs-3 d-flex justify-content-between">
            Admin Archived Files
          </div>

          <div class="card-body pb-0">

            <div class="table-responsive">
              <table class="table align-middle table-hover shadow-sm rounded datatable">
                <thead class="bg-dark text-white">
                  <tr>
                    <th class="text-center"> File Name </th>
                    <th class="text-center"> File Size </th>
                    <th class="text-center"> Archived At </th>
                    <th class="text-center"> Actions </th>
                  </tr>
                </thead>
                <tbody class="bg-white">
                  <?php
                  $get_folder_files = $conn->prepare("
                    SELECT * FROM admin_files_tbl
                    WHERE is_archive = :is_archive
                    ORDER BY uploaded_at DESC
                ");
                
                  $get_folder_files->execute([":is_archive" => "Yes"]);

                  while ($file_data = $get_folder_files->fetch()) { ?>
                    <tr class="text-center border-bottom">

                      <td class="fw-bold text-primary">
                        <?php echo htmlspecialchars($file_data["file_name"]); ?>
                      </td>

                      <td class="fw-bold">
                        <?php echo htmlspecialchars($file_data["file_size"] . " MB"); ?>
                      </td>

                      <td>
                        <?php echo htmlspecialchars(format_timestamp($file_data["modified_at"])); ?>
                      </td>

                      <td>
                        <div class="d-flex justify-content-center gap-1">

                          <form action="../process/admin/file-management.php" method="POST" id="retrieve-file-form">

                            <input type="hidden" name="file-id" value="<?php echo htmlspecialchars(base64_encode($file_data["file_id"])); ?>">
                            <input type="hidden" name="retrieve-file" value="1">

                            <button
                            type="submit"
                            class="btn btn-success btn-sm"
                            onclick="confirmAction(
                            event, 
                            this.form, 
                            'retrieve-file-form',
                            'Retrieve File: <?php echo htmlspecialchars($file_data['file_name']); ?>?', 
                            'warning',
                            'Are you sure you want to retrieve this file?',
                            'Retrieve',
                            '#198754'
                            )"
                                title="Retrieve File">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>

                          </form>

                          <form action="../process/admin/file-management.php" method="POST" id="delete-file-form">

                            <input type="hidden" name="file-id" value="<?php echo htmlspecialchars(base64_encode($file_data["file_id"])); ?>">
                            <input type="hidden" name="stored-name" value="<?php echo htmlspecialchars(base64_encode($file_data["stored_file_name"])); ?>">
                            <input type="hidden" name="processed-name" value="<?php echo htmlspecialchars(base64_encode($file_data["processed_file_name"])); ?>">

                            <input type="hidden" name="delete-file" value="1">

                            <button
                            type="submit"
                            class="btn btn-danger btn-sm"
                            onclick="confirmAction(
                            event, 
                            this.form, 
                            'delete-file-form',
                            'Delete File: <?php echo htmlspecialchars($file_data['file_name']); ?>?', 
                            'warning',
                            'Are you sure you want to delete this file permanently??',
                            'Delete',
                            '#dc3545'
                            )"
                                title="Delete File">
                                <i class="bi bi-trash"></i>
                            </button>

                          </form>

                        </div>

                      </td>
                      
                    </tr>

                  <?php } ?>
                </tbody>
              </table>
            </div>


          </div>

        </div>

        <!-- Officials Folders Section -->
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-dark text-white fw-bold fs-3 d-flex justify-content-between">

                Officials Archived Folders

            </div>

            <div class="card-body mt-3">

                <div class="row gx-4 gy-4">

                    <?php
                    $get_officials_folders = $conn->prepare("SELECT 
                                                            oft.*,
                                                            COUNT(ofs.file_id) AS 'files_count'
                                                        FROM officials_folder_tbl oft
                                                        LEFT JOIN officials_files_Tbl ofs
                                                        ON oft.folder_id = ofs.folder_id
                                                        WHERE oft.is_archive = :is_archive
                                                        GROUP BY oft.folder_id
                                                        ORDER BY oft.modified_at DESC");
                    $get_officials_folders->execute([":is_archive" => "Yes"]);

                    if ($get_officials_folders->rowCount() > 0) {
                        while ($officials_folder_data = $get_officials_folders->fetch()) {
                    ?>
                            <div class="col-6 col-md-3">

                                <div class="card shadow-lg border-0 rounded-4 text-center p-3 folder-card h-100 position-relative" style="transition: 0.3s; cursor: pointer;">

                                    <!-- Three-dot dropdown -->
                                    <div class="dropdown position-absolute top-0 end-0 m-2">

                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" type="button" id="folderMenuBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="folderMenuBtn">

                                            <li>
                                                <form action="../process/officials/file-management.php" method="POST" id="retrieve-folder-form">
                                                    <input type="hidden" name="folder-id" value="<?php echo htmlspecialchars(base64_encode($officials_folder_data["folder_id"])); ?>">
                                                    <input type="hidden" name="retrieve-folder" value="1">

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item"
                                                        onclick="confirmAction(
                                                    event, 
                                                    this.form, 
                                                    'retrieve-folder-form',
                                                    'Retrieve Folder, <?php echo htmlspecialchars($officials_folder_data['folder_name']); ?>?', 
                                                    'warning',
                                                    'Are you sure you want to retrieve this folder and its contents?',
                                                    'Retrieve',
                                                    '#198754'
                                                    )"
                                                        title="Retrieve Folder">
                                                        <i class="bi bi-arrow-counterclockwise text-success"></i> Retrieve
                                                    </button>
                                                </form>
                                            </li>

                                            <li>
                                                <form action="../process/officials/file-management.php" method="POST" id="delete-folder-form">
                                                    <input type="hidden" name="folder-id" value="<?php echo htmlspecialchars(base64_encode($officials_folder_data["folder_id"])); ?>">
                                                    <input type="hidden" name="delete-folder" value="1">

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item"
                                                        onclick="confirmAction(
                                                    event, 
                                                    this.form, 
                                                    'delete-folder-form',
                                                    'Delete Folder, <?php echo htmlspecialchars($officials_folder_data['folder_name']); ?>?', 
                                                    'warning',
                                                    'Are you sure you want to delete this folder permanently?',
                                                    'Delete',
                                                    '#dc3545'
                                                    )"
                                                        title="Delete Folder">
                                                        <i class="bi bi-trash text-danger"></i> Delete
                                                    </button>
                                                </form>
                                            </li>

                                        </ul>

                                    </div>

                                    <div class="card-body d-flex flex-column align-items-center justify-content-center">

                                        <!-- Folder Icon -->
                                        <div class="folder-icon mb-3">
                                            <i class="bi bi-folder-fill fs-1 text-warning"></i>
                                        </div>

                                        <!-- Folder Name -->
                                        <h5 class="fw-bold mb-1">
                                            <?php echo htmlspecialchars($officials_folder_data["folder_name"]); ?>
                                        </h5>

                                        <!-- File Count -->
                                        <p class="small text-muted mb-3">
                                            <?php echo htmlspecialchars($officials_folder_data["files_count"] . " Files"); ?>
                                        </p>

                                        <!-- Access Status -->
                                        <div class="access-status w-100 text-start px-3">

                                            <p class="mb-1 fw-semibold text-secondary fs-6"> Allow Access To: </p>

                                            <?php
                                            // Map DB columns to display names
                                            $accessRoles = [
                                                'brgy_captain_access'   => 'Brgy. Captain',
                                                'brgy_secretary_access' => 'Brgy. Secretary',
                                                'brgy_treasurer_access' => 'Brgy. Treasurer',
                                                'brgy_kagawad_access'   => 'Brgy. Kagawad',
                                                'sk_chairman_access'    => 'S.K. Chairman',
                                                'sk_kagawad_access'     => 'S.K. Kagawad'
                                            ];

                                            $hasAccess = false;
                                            foreach ($accessRoles as $column => $label) {
                                                if (!empty($officials_folder_data[$column]) && strtolower($officials_folder_data[$column]) === 'yes') {
                                                    $hasAccess = true;
                                                    echo '<p class="mb-0 text-success" style="font-size: 13px;">&#10003; ' . htmlspecialchars($label) . '</p>';
                                                }
                                            }

                                            if (!$hasAccess) {
                                                echo '<span class="text-muted">None</span>';
                                            }
                                            ?>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        <?php
                        }
                    } else {
                        ?>
                        <h1 class="text-center mt-5"> No Archived Folders. </h1>
                    <?php
                    }
                    ?>

                </div>

            </div>

        </div>

        <!-- Officials Files Section -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-dark text-white fw-bold fs-3 d-flex justify-content-between">
            Officials Archived Files
          </div>

          <div class="card-body pb-0">

            <div class="table-responsive">
              <table class="table align-middle table-hover shadow-sm rounded datatable">
                <thead class="bg-dark text-white">
                  <tr>
                    <th class="text-center"> File Name </th>
                    <th class="text-center"> File Size </th>
                    <th class="text-center"> Uploaded By </th>
                    <th class="text-center"> Archived At </th>
                    <th class="text-center"> Actions </th>
                  </tr>
                </thead>
                <tbody class="bg-white">
                  <?php
                  $get_folder_files = $conn->prepare("
                    SELECT 
                    of.*,
                    CONCAT(oa.first_name, ' ', oa.last_name) AS 'uploader' 
                    FROM officials_files_tbl of
                    LEFT JOIN officials_accounts_tbl oa
                    ON of.uploaded_by = oa.official_id
                    WHERE of.is_archive = :is_archive
                    ORDER BY of.modified_at DESC
                ");
                
                  $get_folder_files->execute([":is_archive" => "Yes"]);

                  while ($file_data = $get_folder_files->fetch()) { ?>
                    <tr class="text-center border-bottom">

                      <td class="fw-bold text-primary text-truncate" style="max-width: 300px;">
                        <?php echo htmlspecialchars($file_data["file_name"]); ?>
                      </td>

                      <td class="fw-bold">
                        <?php echo htmlspecialchars($file_data["file_size"] . " MB"); ?>
                      </td>

                      <td class="fw-bold">
                        <?php echo htmlspecialchars($file_data["uploader"]); ?>
                      </td>

                      <td>
                        <?php echo htmlspecialchars(format_timestamp($file_data["modified_at"])); ?>
                      </td>

                      <td>
                        <div class="d-flex justify-content-center gap-1">

                          <form action="../process/officials/file-management.php" method="POST" id="retrieve-file-form">

                            <input type="hidden" name="file-id" value="<?php echo htmlspecialchars(base64_encode($file_data["file_id"])); ?>">
                            <input type="hidden" name="retrieve-file" value="1">

                            <button
                            type="submit"
                            class="btn btn-success btn-sm"
                            onclick="confirmAction(
                            event, 
                            this.form, 
                            'retrieve-file-form',
                            'Retrieve File: <?php echo htmlspecialchars($file_data['file_name']); ?>?', 
                            'warning',
                            'Are you sure you want to retrieve this file?',
                            'Retrieve',
                            '#198754'
                            )"
                                title="Retrieve File">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>

                          </form>

                          <form action="../process/officials/file-management.php" method="POST" id="delete-file-form">

                            <input type="hidden" name="file-id" value="<?php echo htmlspecialchars(base64_encode($file_data["file_id"])); ?>">
                            <input type="hidden" name="stored-name" value="<?php echo htmlspecialchars(base64_encode($file_data["stored_file_name"])); ?>">
                            <input type="hidden" name="processed-name" value="<?php echo htmlspecialchars(base64_encode($file_data["processed_file_name"])); ?>">

                            <input type="hidden" name="delete-file" value="1">

                            <button
                            type="submit"
                            class="btn btn-danger btn-sm"
                            onclick="confirmAction(
                            event, 
                            this.form, 
                            'delete-file-form',
                            'Delete File: <?php echo htmlspecialchars($file_data['file_name']); ?>?', 
                            'warning',
                            'Are you sure you want to delete this file permanently??',
                            'Delete',
                            '#dc3545'
                            )"
                                title="Delete File">
                                <i class="bi bi-trash"></i>
                            </button>

                          </form>

                        </div>

                      </td>
                      
                    </tr>

                  <?php } ?>
                </tbody>
              </table>
            </div>


          </div>

        </div>

    </section>

</main>