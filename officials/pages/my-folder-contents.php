<?php
  $folder_id = htmlspecialchars(base64_decode($_GET["folder-id"]));

  $get_folder_contents = $conn->prepare("SELECT * FROM officials_folder_tbl WHERE is_archive = :is_archive AND folder_id = :folder_id LIMIT 1");
  $get_folder_contents->execute([":is_archive" => "No", ":folder_id" => $folder_id]);

  if ($get_folder_contents->rowCount() === 1) {
    $folder_data = $get_folder_contents->fetch(PDO::FETCH_OBJ);
  } 
  
  else {
    $_SESSION["query-status"] = [
      "status" => "danger",
      "message" => "Folder not found or archived! Please try again."
    ];

    header("Location: home.php?page=my-files-and-folders");
    exit();
  }
?>

<main class="main" id="main">

  <div class="pagetitle">

    <?php if (isset($_SESSION["query-status"]) && isset($_SESSION["query-status"]) !== "") { ?>
      <div id="notification" class="alert alert-<?php echo htmlspecialchars($_SESSION["query-status"]["status"]); ?> text-center" role="alert">
        <?php echo htmlspecialchars($_SESSION["query-status"]["message"]); ?>
      </div>

      <?php unset($_SESSION["query-status"]); ?>
    <?php } ?>

    <h1>
      <?php echo htmlspecialchars($page_titles[$page_name]); ?>
    </h1>

    <nav>

      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="home.php?page=dashboard"> Home </a></li>

        <li class="breadcrumb-item active">
          <a href="home.php?page=my-files-and-folders">
            My Files and Folders
          </a>
        </li>

        <li class="breadcrumb-item active">
          <?php echo htmlspecialchars($folder_data->folder_name); ?>
        </li>
      </ol>

    </nav>

  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-dark text-white fw-bold fs-3 d-flex justify-content-between">
            My Folder: <?php echo htmlspecialchars($folder_data->folder_name); ?>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#upload-file">
              <i class="bi bi-upload"></i>
              Upload File
            </button>

          </div>

          <div class="card-body pb-0">

            <div class="table-responsive">
              <table class="table align-middle table-hover shadow-sm rounded datatable">
                <thead class="bg-dark text-white">
                  <tr>
                    <th class="fixed-column text-center"> File Name </th>
                    <th class="text-center"> File Size </th>
                    <th class="text-center"> Uploaded By </th>
                    <th class="text-center"> Uploaded At </th>
                    <th class="text-center"> Actions </th>
                  </tr>
                </thead>
                <tbody class="bg-white">
                  <?php
                  $get_folder_files = $conn->prepare("
                      SELECT 
                        ofl.*,
                        CONCAT(oa.first_name, ' ', oa.last_name) AS 'uploader' 
                      FROM officials_files_tbl ofl
                      LEFT JOIN officials_accounts_tbl oa
                      ON ofl.uploaded_by = oa.official_id
                      WHERE folder_id = :folder_id AND is_archive = :is_archive
                      ORDER BY uploaded_at DESC
                  ");

                  $get_folder_files->execute([":folder_id" => $folder_id, ":is_archive" => "No"]);

                  while ($file_data = $get_folder_files->fetch()) { ?>
                    <tr class="text-center border-bottom">

                      <td class="fw-bold text-primary fixed-column">
                        <?php echo htmlspecialchars($file_data["file_name"]); ?>
                      </td>

                      <td class="fw-bold">
                        <?php echo htmlspecialchars($file_data["file_size"] . " MB"); ?>
                      </td>

                      <td class="fw-bold">
                        <?php if($file_data["uploaded_by"] == $official_id) { ?>
                          You
                        <?php } else { ?>
                            <?php echo htmlspecialchars($file_data["uploader"]);?>
                        <?php } ?>
                      </td>

                      <td class="fw-bold">
                        <?php echo htmlspecialchars(format_timestamp($file_data["uploaded_at"])); ?>
                      </td>

                      <td>

                        <div class="d-flex justify-content-center gap-1">

                        <?php if($file_data[$officials_file_permission] === "Yes") { ?>

                          <a href="../uploads/user-files/<?php echo htmlspecialchars($file_data["stored_file_name"]); ?>"
                            download="<?php echo htmlspecialchars($file_data["file_name"]); ?>" title="Download: <?php echo htmlspecialchars($file_data["file_name"]); ?>"
                            class="btn btn-success btn-sm">
                            <i class="bi bi-download"></i>
                          </a>

                        <?php } else { ?>
                          <a href="../uploads/user-files/<?php echo htmlspecialchars($file_data["processed_file_name"]); ?>"
                            download="<?php echo htmlspecialchars($file_data["file_name"]); ?>" title="Download: <?php echo htmlspecialchars($file_data["file_name"]); ?>"
                            class="btn btn-secondary btn-sm">
                            <i class="bi bi-download"></i>
                          </a>
                        <?php } ?>

                          <?php if ($file_data["uploaded_by"] == $official_id): ?>
                            <button class="btn btn-sm btn-primary"
                              title="Edit Permission"
                              data-bs-toggle="modal"
                              data-bs-target="#file-details-<?php echo htmlspecialchars($file_data["file_id"]); ?>">
                              <i class="bi bi-pencil-square"></i>
                            </button>
                          <?php endif; ?>

                          <?php if ($file_data["uploaded_by"] == $official_id): ?>
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
                          <?php endif; ?>

                        </div>

                      </td>

                    </tr>

                    <!-- Update File Permissions -->
                    <div class="modal fade" id="file-details-<?php echo htmlspecialchars($file_data["file_id"]); ?>" tabindex="-1" aria-labelledby="updateFilePermission" aria-hidden="true">

                      <div class="modal-dialog modal-dialog-centered">

                        <div class="modal-content border-0 shadow-lg rounded-4">

                          <div class="modal-header border-0 bg-secondary text-white rounded-top-4">

                            <h5 class="modal-title fw-bold" id="updateFilePermission">

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

                  <?php } ?>
                </tbody>
              </table>
            </div>


          </div>

        </div>
      </div>

    </div>

  </section>

</main>

<!-- Upload File -->
<div class="modal fade" id="upload-file" tabindex="-1" aria-labelledby="addFolderModalLabel" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered">

    <div class="modal-content border-0 shadow-lg rounded-4">

      <div class="modal-header border-0 bg-secondary text-white rounded-top-4">

        <h5 class="modal-title fw-bold" id="addFolderModalLabel">

          <i class="bi bi-upload me-2"></i> Upload File

        </h5>

        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>

      </div>

      <form action="../process/officials/file-management.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="folder-id" value="<?php echo htmlspecialchars(base64_encode($folder_id)); ?>">
        <input type="hidden" name="position" value="<?php echo htmlspecialchars($position); ?>">
        
        <div class="modal-body">

          <!-- Folder Name -->
          <div class="mb-3">
            <label class="form-label"> Upload File: </label>
            <input type="file" name="uploaded-file" class="form-control" required
              accept=".txt,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.csv,.json,.xml,.zip,.rar,.7z,.html,.php,.js,.css">
          </div>

          <!-- Download Permissions -->
          <div class="mb-3">
            <?php if ($position === "Brgy. Captain" || $position === "S.K. Chairman" || $position === "Brgy. Secretary" || $position === "Brgy. Treasurer"): ?>
              <label class="form-label fw-semibold"> Download Permissions </label>
            <?php endif; ?>

            <?php if ($position === "Brgy. Captain" || $position === "S.K. Chairman"): ?>
              <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="brgySecretaryDownload"
                  value="Yes" name="allow-brgy-secretary-download" checked>
                <label class="form-check-label" for="brgySecretaryDownload">
                  Brgy. Secretary
                </label>
              </div>


              <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="brgyTreasurerDownload"
                  value="Yes" name="allow-brgy-treasurer-download" checked>
                <label class="form-check-label" for="brgyTreasurerDownload">
                  Brgy. Treasurer
                </label>
              </div>

            <?php endif; ?>

            <?php if ($position === "Brgy. Captain" || $position === "S.K. Chairman" || $position === "Brgy. Secretary" || $position === "Brgy. Treasurer"): ?>
              <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="brgyKagawadDownload"
                  value="Yes" name="allow-brgy-kagawad-download" checked>
                <label class="form-check-label" for="brgyKagawadDownload">
                  Brgy. Kagawad
                </label>
              </div>
            <?php endif; ?>

            <?php if ($position === "Brgy. Captain"): ?>
              <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="skChairmanDownload"
                  value="Yes" name="allow-sk-chairman-download" checked>
                <label class="form-check-label" for="skChairmanDownload">
                  SK Chairman
                </label>
              </div>
            <?php endif; ?>

            <?php if ($position === "Brgy. Captain" || $position === "S.K. Chairman" || $position === "Brgy. Secretary" || $position === "Brgy. Treasurer"): ?>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="skKagawadDownload"
                  value="Yes" name="allow-sk-kagawad-download" checked>
                <label class="form-check-label" for="skKagawadDownload">
                  SK Kagawad
                </label>
              </div>
            <?php endif; ?>

          </div>

        </div>

        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success rounded-pill px-4" name="upload-file">
            <i class="bi bi-upload me-1"></i> Upload File
          </button>
        </div>
      </form>
    </div>
  </div>
</div>