<main class="main" id="main">

  <?php
    include_once "includes/pagetitle.php";
  ?>

  <section class="section">

    <div class="row">

      <div class="col-12">

        <div class="card shadow-sm mb-4">

          <div class="card-header bg-dark text-white fw-bold fs-3 d-flex justify-content-between">

            User Uploaded Files Log

          </div>

          <div class="card-body pb-0">

            <table class="table table-light table-hover datatable table-responsive">

              <thead>
                <tr>
                  <th scope="col" class="fixed-column text-center"> File Name </th>
                  <th scope="col" class="text-center"> File Size </th>
                  <th scope="col" class="fixed-column text-center"> Uploaded By </th>
                  <th scope="col" class="fixed-column text-center"> Uploaded At </th>
                  <th scope="col" class="text-center"> Actions </th>
                </tr>
              </thead>

              <tbody>
                <?php
                    $fetch_uploads_logs = $conn->prepare("SELECT
                                                            ofs.*,
                                                            ofl.folder_id,
                                                            CONCAT(oa.first_name, ' ', oa.last_name) AS 'uploaded_by'
                                                        FROM officials_files_tbl ofs
                                                        LEFT JOIN officials_accounts_tbl oa
                                                        ON ofs.uploaded_by = oa.official_id
                                                        LEFT JOIN officials_folder_tbl ofl
                                                        ON ofs.folder_id = ofl.folder_id
                                                        WHERE ofl.is_archive = :is_archive OR ofs.folder_id IS NULL
                                                        ORDER BY ofs.uploaded_at DESC
                                                        ");

                    $fetch_uploads_logs->execute([":is_archive" => "No"]);
                                                              

                    while ($log_data = $fetch_uploads_logs->fetch()) {
                ?>
                    <tr class="text-center">
                    
                        <td class="fw-bold text-primary fixed-column">
                            <?php echo htmlspecialchars($log_data["file_name"]); ?>
                        </td>

                        <td class="fw-bold">
                            <?php echo htmlspecialchars($log_data["file_size"] . " MB"); ?>
                        </td>

                        <td class="fw-bold">
                            <?php echo htmlspecialchars($log_data["uploaded_by"]); ?>
                        </td>

                        <td class="fw-bold">
                            <?php echo htmlspecialchars(format_timestamp($log_data["uploaded_at"])); ?>
                        </td>

                        <td>
                            <div class="d-flex justify-content-center align-items-center gap-1 ">

                                <a href="../uploads/user-files/<?php echo htmlspecialchars($log_data["stored_file_name"]); ?>"
                                   download="<?php echo htmlspecialchars($log_data["file_name"]); ?>" title="Download: <?php echo htmlspecialchars($log_data["file_name"]); ?>"
                                   class="btn btn-sm btn-success text-white">
                                    <i class="bi bi-download"></i>
                                </a>

                                <?php if($log_data["folder_id"] !== null): ?>

                                  <a href="home.php?page=officials-folder-contents&folder-id=<?php echo htmlspecialchars(base64_encode($log_data["folder_id"])); ?>"
                                    class="btn btn-warning btn-sm" title="Go to Folder">
                                      <i class="bi bi-box-arrow-up-right"></i>
                                  </a>

                                <?php endif; ?>

                                
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
              </tbody>

            </table>

          </div>

        </div>
      </div>

    </div>

  </section>

</main>
