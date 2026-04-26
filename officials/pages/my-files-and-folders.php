<main class="main" id="main">
    <?php include_once "includes/pagetitle.php" ?>

    <section class="section">

        <!-- Folders Section -->
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-dark text-white fw-bold fs-3 d-flex justify-content-between">
                My Files and Folders

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-folder">
                    <i class="bi bi-folder-plus"></i>
                    Add Folder
                </button>

            </div>

            <div class="card-body mt-3">

                <div class="row gx-4 gy-4">

                    <?php
                    $get_folders = $conn->prepare("SELECT 
                                                            ofl.*,
                                                            COUNT(CASE WHEN ofs.is_archive = 'No' THEN 1 END) AS 'files_count'
                                                        FROM officials_folder_tbl ofl
                                                        LEFT JOIN officials_files_tbl ofs
                                                        ON ofl.folder_id = ofs.folder_id
                                                        WHERE created_by = :official_id AND ofl.is_archive = :is_archive
                                                        GROUP BY ofl.folder_id
                                                        ORDER BY modified_at DESC");
                    $get_folders->execute([":official_id" => $official_id, ":is_archive" => "No"]);

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
                                                <a class="dropdown-item" href="home.php?page=my-folder-contents&folder-id=<?php echo htmlspecialchars(base64_encode($folder_data["folder_id"])); ?>">
                                                    <i class="bi bi-folder2-open me-2 text-success"></i> Open Folder
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#update-folder-<?php echo htmlspecialchars($folder_data["folder_id"]); ?>">
                                                    <i class="bi bi-pencil-square me-2 text-primary"></i> Edit
                                                </a>
                                            </li>

                                            <li>
                                                <form action="../process/officials/file-management.php" method="POST" id="archive-folder-form">
                                                    <input type="hidden" name="folder-id" value="<?php echo htmlspecialchars(base64_encode($folder_data["folder_id"])); ?>">
                                                    <input type="hidden" name="archive-folder" value="1">

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item"
                                                        onclick="confirmAction(
                                                    event, 
                                                    this.form, 
                                                    'archive-folder-form',
                                                    'Archive Folder: <?php echo htmlspecialchars($folder_data['folder_name']); ?>?', 
                                                    'warning',
                                                    'Are you sure you want to archive this folder and its contents?',
                                                    'Archive',
                                                    '#FFC107'
                                                    )"
                                                        title="Delete Folder">
                                                        <i class="bi bi-archive text-warning"></i> Archive
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
                                                'brgy_captain_access' => 'Brgy. Captain',
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

                            <!-- Update Folder -->
                            <div class="modal fade" id="update-folder-<?php echo htmlspecialchars($folder_data["folder_id"]); ?>" tabindex="-1" aria-labelledby="updateFolderModal" aria-hidden="true">

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content border-0 shadow-lg rounded-4">

                                        <div class="modal-header border-0 bg-secondary text-white rounded-top-4">

                                            <h5 class="modal-title fw-bold" id="updateFolderLabel">

                                                <i class="bi bi-folder-plus me-2"></i> Update Folder: <?php echo htmlspecialchars($folder_data["folder_name"]); ?>

                                            </h5>

                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>

                                        </div>

                                        <form action="../process/officials/file-management.php" method="POST">

                                            <input type="hidden" name="folder-id" value="<?php echo htmlspecialchars(base64_encode($folder_data["folder_id"])); ?>">
                                            <input type="hidden" name="position" value="<?php echo htmlspecialchars($position); ?>">

                                            <div class="modal-body">

                                                <!-- Folder Name -->
                                                <div class="mb-3">
                                                    <label for="folderName" class="form-label fw-semibold">Folder Name</label>
                                                    <input type="text" class="form-control rounded-pill" id="folderName" name="folder-name" placeholder="Enter folder name" value="<?php echo htmlspecialchars($folder_data["folder_name"]); ?>" required>
                                                </div>

                                                <!-- Access Toggles -->
                                                <div class="mb-3">
                                                    <?php if($position === "Brgy. Captain" || $position === "S.K. Chairman"): ?>
                                                        <label class="form-label fw-semibold">Access Permissions</label>
                                                    <?php endif; ?>

                                                    <?php if($position === "Brgy. Captain" || $position === "S.K. Chairman"): ?>
                                                    
                                                        <div class="form-check form-switch mb-2">
                                                            <input class="form-check-input"
                                                                type="checkbox"
                                                                id="brgySecretaryAccess-<?php echo htmlspecialchars($folder_data["folder_id"]); ?>"
                                                                value="Yes"
                                                                name="brgy-secretary-access"
                                                                <?php echo $folder_data["brgy_secretary_access"] === "Yes" ? "checked" : ""; ?>>
                                                            <label class="form-check-label" for="brgySecretaryAccess-<?php echo htmlspecialchars($folder_data["folder_id"]); ?>">
                                                                Brgy. Secretary Access
                                                            </label>
                                                        </div>
                                                    
                                                        <div class="form-check form-switch mb-2">
                                                            <input class="form-check-input"
                                                                type="checkbox"
                                                                id="brgyTreasurerAccess-<?php echo htmlspecialchars($folder_data["folder_id"]); ?>"
                                                                value="Yes"
                                                                name="brgy-treasurer-access"
                                                                <?php echo $folder_data["brgy_treasurer_access"] === "Yes" ? "checked" : ""; ?>>
                                                            <label class="form-check-label" for="brgyTreasurerAccess-<?php echo htmlspecialchars($folder_data["folder_id"]); ?>">
                                                                Brgy. Treasurer Access
                                                            </label>
                                                        </div>

                                                    <?php endif; ?>

                                                    <?php if($position === "Brgy. Captain" || $position === "S.K. Chairman" || $position === "Brgy. Secretary" || $position === "Brgy. Treasurer"): ?>
                                                        <div class="form-check form-switch mb-2">
                                                            <input class="form-check-input"
                                                                type="checkbox"
                                                                id="brgyKagawadAccess-<?php echo htmlspecialchars($folder_data["folder_id"]); ?>"
                                                                value="Yes"
                                                                name="brgy-kagawad-access"
                                                                <?php echo $folder_data["brgy_kagawad_access"] === "Yes" ? "checked" : ""; ?>>
                                                            <label class="form-check-label" for="brgyKagawadAccess-<?php echo htmlspecialchars($folder_data["folder_id"]); ?>">
                                                                Brgy. Kagawad Access
                                                            </label>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if($position === "Brgy. Captain"): ?>
                                                        <div class="form-check form-switch mb-2">
                                                            <input class="form-check-input"
                                                                type="checkbox"
                                                                id="skChairmanAccess-<?php echo htmlspecialchars($folder_data["folder_id"]); ?>"
                                                                value="Yes"
                                                                name="sk-chairman-access"
                                                                <?php echo $folder_data["sk_chairman_access"] === "Yes" ? "checked" : ""; ?>>
                                                            <label class="form-check-label" for="skChairmanAccess-<?php echo htmlspecialchars($folder_data["folder_id"]); ?>">
                                                                S.K. Chairman Access
                                                            </label>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if($position === "Brgy. Captain" || $position === "S.K. Chairman" || $position === "Brgy. Secretary" || $position === "Brgy. Treasurer"): ?>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input"
                                                                type="checkbox"
                                                                id="skKagawadAccess-<?php echo htmlspecialchars($folder_data["folder_id"]); ?>"
                                                                value="Yes"
                                                                name="sk-kagawad-access"
                                                                <?php echo $folder_data["sk_kagawad_access"] === "Yes" ? "checked" : ""; ?>>
                                                            <label class="form-check-label" for="skKagawadAccess-<?php echo htmlspecialchars($folder_data["folder_id"]); ?>">
                                                                S.K. Kagawad Access
                                                            </label>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="mb-3 mt-3">
                                                        <p class="fst-italic">
                                                            Created At:
                                                            <span class="fw-bold"><?php echo htmlspecialchars(format_timestamp($folder_data["created_at"])); ?></span>

                                                            <br>

                                                            Modified At:
                                                            <span class="fw-bold"><?php echo htmlspecialchars(format_timestamp($folder_data["modified_at"])); ?></span>
                                                        </p> 
                                                    </div>                        

                                                </div>


                                            </div>

                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success rounded-pill px-4" name="update-folder">
                                                    <i class="bi bi-save me-1"></i> Update Folder
                                                </button>
                                            </div>
                                        </form>

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

        <div class="card shadow-sm mb-4">
          <div class="card-header bg-dark text-white fw-bold fs-3 d-flex justify-content-between">
            
            Your Files with no Folders
            
          </div>

          <div class="card-body pb-0">

            <div class="table-responsive">
              <table class="table align-middle table-hover shadow-sm rounded datatable">
                <thead class="bg-dark text-white">
                  <tr>
                    <th class="text-center w-50"> File Name </th>
                    <th class="text-center"> File Size </th>
                    <th class="text-center"> Uploaded At </th>
                    <th class="text-center"> Actions </th>
                  </tr>
                </thead>
                <tbody class="bg-white">
                  <?php
                  $get_folder_files = $conn->prepare("
                        SELECT 
                          *
                        FROM officials_files_tbl
                        WHERE is_archive = :is_archive AND folder_id IS NULL AND uploaded_by = :uploaded_by
                        ORDER BY uploaded_at DESC
                    ");

                  $get_folder_files->execute([":is_archive" => "No", ":uploaded_by" => $official_id]);

                  while ($file_data = $get_folder_files->fetch()) { ?>
                    <tr class="text-center border-bottom fw-bold ">

                      <td class="text-primary text-truncate" style="max-width: 300px;">
                        <?php echo htmlspecialchars($file_data["file_name"]); ?>
                      </td>

                      <td>
                        <?php echo htmlspecialchars($file_data["file_size"] . " MB"); ?>
                      </td>

                       <td>
                        <?php echo htmlspecialchars(format_timestamp($file_data["uploaded_at"])); ?>
                      </td>

                      <td>
                        <div class="d-flex justify-content-center gap-1">

                          <a href="../uploads/user-files/<?php echo htmlspecialchars($file_data["stored_file_name"]); ?>"
                            download="<?php echo htmlspecialchars($file_data["file_name"]); ?>" title="Download: <?php echo htmlspecialchars($file_data["file_name"]); ?>"
                            class="btn btn-success btn-sm">
                            <i class="bi bi-download"></i>
                          </a>   
                          
                          <button class="btn btn-sm btn-primary"
                              title="Edit Permission"
                              data-bs-toggle="modal"
                              data-bs-target="#file-details-<?php echo htmlspecialchars($file_data["file_id"]); ?>">
                              <i class="bi bi-pencil-square"></i>
                            </button>

                            <form action="../process/officials/file-management.php" method="POST" id="archive-file-form">

                              <input type="hidden" name="folder-id" value="<?php echo htmlspecialchars(base64_encode($folder_id)); ?>">
                              <input type="hidden" name="file-id" value="<?php echo htmlspecialchars(base64_encode($file_data["file_id"])); ?>">
                              <input type="hidden" name="archive-file" value="1">

                              <button
                                type="submit"
                                class="btn btn-warning btn-sm"
                                onclick="confirmAction(
                              event, 
                              this.form, 
                              'archive-file-form',
                              'Archive File: <?php echo htmlspecialchars($file_data['file_name']); ?>?', 
                              'warning',
                              'Are you sure you want to archive this file?',
                              'Archive',
                              '#FFC107'
                              )"
                                title="Archive File">
                                <i class="bi bi-archive"></i>
                              </button>

                            </form>

                          <button class="btn btn-sm btn-dark"
                            title="Assign Folder"
                            data-bs-toggle="modal"
                            data-bs-target="#assign-folder-<?php echo htmlspecialchars($file_data["file_id"]); ?>">
                            <i class="bi bi-folder-symlink"></i>
                          </button>

                        </div>

                      </td>
                      
                    </tr>

                    <!-- Update File Permissions -->
                    <div class="modal fade" id="file-details-<?php echo htmlspecialchars($file_data["file_id"]); ?>" tabindex="-1" aria-labelledby="updateFilePermission" aria-hidden="true">

                      <div class="modal-dialog modal-dialog-centered">

                        <div class="modal-content border-0 shadow-lg rounded-4">

                          <div class="modal-header border-0 bg-secondary text-white rounded-top-4">

                           <h5 class="modal-title fw-bold text-wrap" style="white-space: normal; word-break: break-word;" id="editPermission">

                              <i class="bi bi-pencil-square me-2"></i> Update File Permission: <?php echo htmlspecialchars($file_data["file_name"]); ?>

                            </h5>

                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>

                          </div>

                          <form action="../process/officials/file-management.php" method="POST">

                            <input type="hidden" name="folder-id" value="<?php echo htmlspecialchars(base64_encode($folder_id)); ?>">
                            <input type="hidden" name="file-id" value="<?php echo htmlspecialchars(base64_encode($file_data["file_id"])); ?>">
                            <input type="hidden" name="position" value="<?php echo htmlspecialchars($position); ?>">

                            <div class="modal-body">

                              <!-- Download Permissions -->
                              <div class="mb-3">
                                <label class="form-label fw-semibold"> Download Permissions </label>

                                <?php if ($position === "Brgy. Captain" || $position === "S.K. Chairman"): ?>
                                  <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="brgySecretaryDownload-<?php echo htmlspecialchars($file_data["file_id"]); ?>"
                                      value="Yes" name="allow-brgy-secretary-download"
                                      <?php echo $file_data["brgy_secretary_download"] === "Yes" ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="brgySecretaryDownload-<?php echo htmlspecialchars($file_data["file_id"]); ?>">
                                      Brgy. Secretary
                                    </label>
                                  </div>

                                  <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="brgyTreasurerDownload-<?php echo htmlspecialchars($file_data["file_id"]); ?>"
                                      value="Yes" name="allow-brgy-treasurer-download"
                                      <?php echo $file_data["brgy_treasurer_download"] === "Yes" ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="brgyTreasurerDownload-<?php echo htmlspecialchars($file_data["file_id"]); ?>">
                                      Brgy. Treasurer
                                    </label>
                                  </div>
                                <?php endif; ?>

                                <?php if ($position === "Brgy. Captain" || $position === "S.K. Chairman" || $position === "Brgy. Secretary" || $position === "Brgy. Treasurer"): ?>
                                  <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="brgyKagawadDownload-<?php echo htmlspecialchars($file_data["file_id"]); ?>"
                                      value="Yes" name="allow-brgy-kagawad-download"
                                      <?php echo $file_data["brgy_kagawad_download"] === "Yes" ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="brgyKagawadDownload-<?php echo htmlspecialchars($file_data["file_id"]); ?>">
                                      Brgy. Kagawad
                                    </label>
                                  </div>
                                <?php endif; ?>

                                <?php if ($position === "Brgy. Captain"): ?>
                                  <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="skChairmanDownload-<?php echo htmlspecialchars($file_data["file_id"]); ?>"
                                      value="Yes" name="allow-sk-chairman-download"
                                      <?php echo $file_data["sk_chairman_download"] === "Yes" ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="skChairmanDownload-<?php echo htmlspecialchars($file_data["file_id"]); ?>">
                                      SK Chairman
                                    </label>
                                  </div>
                                <?php endif; ?>

                                <?php if ($position === "Brgy. Captain" || $position === "S.K. Chairman" || $position === "Brgy. Secretary" || $position === "Brgy. Treasurer"): ?>
                                  <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="skKagawadDownload-<?php echo htmlspecialchars($file_data["file_id"]); ?>"
                                      value="Yes" name="allow-sk-kagawad-download"
                                      <?php echo $file_data["sk_kagawad_download"] === "Yes" ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="skKagawadDownload-<?php echo htmlspecialchars($file_data["file_id"]); ?>">
                                      SK Kagawad
                                    </label>
                                  </div>
                                <?php endif; ?>
                                
                              </div>

                            </div>

                            <div class="modal-footer border-0">
                              <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-success rounded-pill px-4" name="update-file-permissions">
                                <i class="bi bi-pencil-square me-1"></i> Update File Permissions
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

                    <!-- Assign Folder -->
                    <div class="modal fade" id="assign-folder-<?php echo htmlspecialchars($file_data["file_id"]); ?>" tabindex="-1" aria-labelledby="assignFolder" aria-hidden="true">

                      <div class="modal-dialog modal-dialog-centered">

                        <div class="modal-content border-0 shadow-lg rounded-4">

                          <div class="modal-header border-0 bg-secondary text-white rounded-top-4">

                            <h5 class="modal-title fw-bold text-wrap" style="white-space: normal; word-break: break-word;" id="assignFolder">

                              <i class="bi bi-pencil-square me-2"></i> Assign Folder to File: <?php echo htmlspecialchars($file_data["file_name"]); ?>

                            </h5>

                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>

                          </div>

                          <form action="../process/officials/file-management.php" method="POST">

                            <input type="hidden" name="file-id" value="<?php echo htmlspecialchars(base64_encode($file_data["file_id"])); ?>">

                            <div class="modal-body">

                              <select class="form-select form-select-md mb-3" aria-label=".form-select-lg example" name="folder-id" required>

                                <option value="" selected disabled> ASSIGN FOLDER </option>
                                
                                <?php
                                    $fetch_officials_folders = $conn->prepare("SELECT 
                                                                                        of.*,
                                                                                        CONCAT(oa.first_name, ' ', oa.last_name) AS 'owner'
                                                                                      FROM officials_folder_tbl of
                                                                                      LEFT JOIN officials_accounts_tbl oa
                                                                                      ON of.created_by = oa.official_id
                                                                                      WHERE of.is_archive = 'No'
                                                                                      ORDER BY of.folder_name ASC");
                                    $fetch_officials_folders->execute();

                                    while($folder = $fetch_officials_folders->fetch()) {
                                        ?>
                                            <option value="<?php echo htmlspecialchars($folder["folder_id"]); ?>">
                                                <?php echo htmlspecialchars($folder["folder_name"] . " (Owner: " . $folder["owner"] . ")"); ?>
                                            </option>
                                        <?php
                                    }
                                ?>
                            </select>

                            </div>

                            <div class="modal-footer border-0">
                              <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-success rounded-pill px-4" name="assign-folder">
                                <i class="bi bi-folder-symlink me-1"></i> Assign Folder
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

                  <?php } ?>
                </tbody>
              </table>
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
                                                        WHERE ofl.created_by != :official_id AND ofl.is_archive = 'No'
                                                        GROUP BY ofl.folder_id
                                                        ORDER BY ofl.modified_at DESC");
                        $get_officials_folders->execute([":official_id" => $official_id, ":is_archive" => "No"]);

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
                                                if($folder_data[$admin_folder_permission] === "Yes") {
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

    </section>

</main>

<!-- Add Folder Modal -->
<div class="modal fade" id="add-folder" tabindex="-1" aria-labelledby="addFolderModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header border-0 bg-secondary text-white rounded-top-4">

                <h5 class="modal-title fw-bold" id="addFolderModalLabel">

                    <i class="bi bi-folder-plus me-2"></i> Add New Folder

                </h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <form action="../process/officials/file-management.php" method="POST">

                <input type="hidden" name="position" value="<?php echo htmlspecialchars($position); ?>">

                <div class="modal-body">

                    <!-- Folder Name -->
                    <div class="mb-3">
                        <label for="folderName" class="form-label fw-semibold">Folder Name</label>
                        <input type="text" class="form-control rounded-pill" id="folderName" name="folder-name" placeholder="Enter folder name" required>
                    </div>

                    <!-- Access Toggles -->
                    <div class="mb-3">
                        <?php if($position === "Brgy. Captain" || $position === "S.K. Chairman"): ?>
                            <label class="form-label fw-semibold">Access Permissions</label>
                        <?php endif; ?>

                        <?php if($position === "Brgy. Captain" || $position === "S.K. Chairman"): ?>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="brgy-secretary-access" value="Yes" name="brgy-secretary-access" checked>
                                <label class="form-check-label" for="brgy-secretary-access">
                                    Brgy. Secretary
                                </label>
                            </div>
                       
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="brgy-treasurer-access" value="Yes" name="brgy-treasurer-access" checked>
                                <label class="form-check-label" for="brgy-treasurer-access">
                                    Brgy. Treasurer
                                </label>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($position === "Brgy. Captain" || $position === "S.K. Chairman" || $position === "Brgy. Secretary" || $position === "Brgy. Treasurer"): ?>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="brgy-kagawad-access" value="Yes" name="brgy-kagawad-access" checked>
                                <label class="form-check-label" for="brgy-kagawad-access">
                                    Brgy. Kagawad
                                </label>
                            </div>
                        <?php endif; ?>

                        <?php if($position === "Brgy. Captain"): ?>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="sk-chairman-access" value="Yes" name="sk-chairman-access" checked>
                                <label class="form-check-label" for="sk-chairman-access">
                                    S.K. Chairman
                                </label>
                            </div>
                        <?php endif; ?>

                        <?php if($position === "Brgy. Captain" || $position === "S.K. Chairman" || $position === "Brgy. Secretary" || $position === "Brgy. Treasurer"): ?>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="sk-kagawad-access" value="Yes" name="sk-kagawad-access" checked>
                                <label class="form-check-label" for="sk-kagawad-access">
                                    S.K. Kagawad
                                </label>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4" name="save-folder">
                        <i class="bi bi-save me-1"></i> Save Folder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>