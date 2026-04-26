<main class="main" id="main">
    <?php include_once "includes/pagetitle.php" ?>

    <section class="section">

        <!-- Admin Folders Section -->
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-dark text-white fw-bold fs-3 d-flex justify-content-between">
                Admin Files and Folders
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

        <div class="card shadow-sm mb-4">
          <div class="card-header bg-dark text-white fw-bold fs-3 d-flex justify-content-between">
            
            Files with no Folders
            
          </div>

          <div class="card-body pb-0">

            <div class="table-responsive">
              <table class="table align-middle table-hover shadow-sm rounded datatable">
                <thead class="bg-dark text-white">
                  <tr>
                    <th class="text-center"> File Name </th>
                    <th class="text-center"> File Size </th>
                    <th class="text-center"> Uploaded At </th>
                    <th class="text-center"> Actions </th>
                  </tr>
                </thead>
                <tbody class="bg-white">
                  <?php
                  $get_folder_files = $conn->prepare("
                        SELECT * FROM admin_files_tbl
                        WHERE is_archive = :is_archive AND folder_id IS NULL
                        ORDER BY uploaded_at DESC
                    ");
                  $get_folder_files->execute([":is_archive" => "No"]);

                  while ($file_data = $get_folder_files->fetch()) { ?>
                    <tr class="text-center border-bottom">

                      <td class="fw-bold text-primary">
                        <?php echo htmlspecialchars($file_data["file_name"]); ?>
                      </td>

                      <td class="fw-bold">
                        <?php echo htmlspecialchars($file_data["file_size"] . " MB"); ?>
                      </td>

                      <td>
                        <?php echo htmlspecialchars(format_timestamp($file_data["uploaded_at"])); ?>
                      </td>

                      <td>
                        <div class="d-flex justify-content-center gap-1">

                        <?php
                            if($file_data[$admin_files_permission] === "Yes") {
                        ?>
                            <a href="../uploads/user-files/<?php echo htmlspecialchars($file_data["stored_file_name"]); ?>"
                            download="<?php echo htmlspecialchars($file_data["file_name"]); ?>" title="Download: <?php echo htmlspecialchars($file_data["file_name"]); ?>"
                            class="btn btn-success btn-sm">
                            <i class="bi bi-download"></i>
                          </a>
                        <?php
                            }
                            else {

                        ?>
                            <a href="../uploads/user-files/<?php echo htmlspecialchars($file_data["stored_file_name"]); ?>"
                            download="<?php echo htmlspecialchars($file_data["file_name"]); ?>" title="Download: <?php echo htmlspecialchars($file_data["file_name"]); ?>"
                            class="btn btn-secondary btn-sm">
                            <i class="bi bi-download"></i>
                            </a>
                        <?php
                            }
                        ?>

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
