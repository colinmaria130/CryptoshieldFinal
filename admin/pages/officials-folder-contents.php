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
      "message" => "Folder not found! Please try again."
    ];

    header("Location: home.php?page=files-and-folders");
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
          <a href="home.php?page=files-and-folders">
            Files and Folders
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

            Officials Folder: <?php echo htmlspecialchars($folder_data->folder_name); ?>

          </div>

          <div class="card-body pb-0">

            <div class="table-responsive">
              <table class="table align-middle table-hover shadow-sm rounded datatable">
                <thead class="bg-dark text-white">
                  <tr>
                    <th class="text-center fixed-column"> File Name </th>
                    <th class="text-center"> File Size </th>
                    <th class="text-center"> Uploaded By </th>
                    <th class="text-center"> Uploaded At </th>
                    <th class="text-center"> Action </th>
                  </tr>
                </thead>
                <tbody class="bg-white">
                  <?php
                  $get_folder_files = $conn->prepare("
                SELECT 
                    ofl.*, CONCAT(oa.first_name, ' ', oa.last_name) AS 'uploaded_by'
                FROM officials_files_tbl ofl
                LEFT JOIN officials_accounts_tbl oa
                ON ofl.uploaded_by = oa.official_id
                WHERE ofl.folder_id = :folder_id
                ORDER BY ofl.uploaded_at DESC
            ");
                  $get_folder_files->execute([":folder_id" => $folder_id]);

                  while ($file_data = $get_folder_files->fetch()) { ?>
                    <tr class="text-center border-bottom">

                      <td class="fw-bold text-primary fixed-column">
                        <?php echo htmlspecialchars($file_data["file_name"]); ?>
                      </td>

                      <td class="fw-bold">
                        <?php echo htmlspecialchars($file_data["file_size"] . " MB"); ?>
                      </td>

                      <td class="fw-bold">
                        <?php echo htmlspecialchars($file_data["uploaded_by"]); ?>
                      </td>

                      <td>
                        <?php echo htmlspecialchars(format_timestamp($file_data["uploaded_at"])); ?>
                      </td>

                      <td>
                
                        <a href="../uploads/user-files/<?php echo htmlspecialchars($file_data["stored_file_name"]); ?>"
                            download="<?php echo htmlspecialchars($file_data["file_name"]); ?>" title="Download: <?php echo htmlspecialchars($file_data["file_name"]); ?>"
                            class="btn btn-success btn-sm">
                            <i class="bi bi-download"></i>
                        </a>
                        </div>
                      </td>
                      
                    </tr>
                
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
