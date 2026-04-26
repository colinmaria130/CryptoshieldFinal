<?php
  $folder_id = htmlspecialchars(base64_decode($_GET["folder-id"]));

  $get_folder_contents = $conn->prepare("SELECT * FROM admin_folder_tbl WHERE folder_id = :folder_id LIMIT 1");
  $get_folder_contents->execute([":folder_id" => $folder_id]);

  if ($get_folder_contents->rowCount() === 1) {

    $folder_data = $get_folder_contents->fetch(PDO::FETCH_OBJ);

    if($folder_data->$admin_folder_permission === "No") {
      $_SESSION["query-status"] = [
        "status" => "danger",
        "message" => "You don't have permission to open this folder!"
      ];

      header("Location: home.php?page=admin-files-and-folders");
      exit();
    }
  } 
  
  else {
    $_SESSION["query-status"] = [
      "status" => "danger",
      "message" => "Folder not found! Please try again."
    ];

    header("Location: home.php?page=admin-files-and-folders");
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

            Admin Folder: <?php echo htmlspecialchars($folder_data->folder_name); ?>

          </div>

          <div class="card-body pb-0">

            <div class="table-responsive">
              <table class="table align-middle table-hover shadow-sm rounded datatable">
                <thead class="bg-dark text-white">
                  <tr>
                    <th class="text-center fixed-column"> File Name</th>
                    <th class="text-center"> File Size </th>
                    <th class="text-center"> Uploaded At </th>
                    <th class="text-center"> Download </th>
                  </tr>
                </thead>
                <tbody class="bg-white">
                  <?php
                  $get_folder_files = $conn->prepare("
                    SELECT * FROM admin_files_tbl
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
                        <?php echo htmlspecialchars(format_timestamp($file_data["uploaded_at"])); ?>
                      </td>

                      <td>
                      
                        <?php
                          if($file_data[$admin_files_permission] === "Yes") {
                        ?>
                          <a href="../uploads/user-files/<?php echo htmlspecialchars($file_data["stored_file_name"]); ?>"
                            download="<?php echo htmlspecialchars($file_data["file_name"]); ?>" title="Download: <?php echo htmlspecialchars($file_data["file_name"]); ?>"
                            class="btn btn-success btn-sm">
                            <i class="bi bi-download"></i>
                          </a>
                        <?php
                          } else {
                        ?>
                          <a href="../uploads/user-files/<?php echo htmlspecialchars($file_data["processed_file_name"]); ?>"
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
      </div>

    </div>

  </section>

</main>
