<main class="main" id="main">

  <?php
    include_once "includes/pagetitle.php";
  ?>

  <section class="section">

    <div class="row">

      <div class="col-12">

        <div class="card shadow-sm mb-4">

          <div class="card-header bg-dark text-white fw-bold fs-3 d-flex justify-content-between">

            File Sharing Logs

          </div>

          <div class="card-body pb-0">

            <table class="table table-light table-hover datatable table-responsive">

              <thead>
                <tr>
                  <th scope="col" class="fixed-column text-center"> File Name </th>
                  <th scope="col" class="text-center"> File Size </th>
                  <th scope="col" class="fixed-column text-center"> Sender </th>
                  <th scope="col" class="fixed-column text-center"> Receiver </th>
                  <th scope="col" class="fixed-column text-center"> Shared At </th>
                  <th scope="col" class="text-center"> Download </th>
                </tr>
              </thead>

              <tbody>
                <?php
                    $fetch_file_sharing_logs = $conn->prepare("SELECT
                                                            fst.*,
                                                            ofl.file_name,
                                                            ofl.file_size,
                                                            ofl.stored_file_name,
                                                            CONCAT(sender.first_name, ' ', sender.last_name) AS 'sender',
                                                            CONCAT(receiver.first_name, ' ', receiver.last_name) AS 'receiver'
                                                        FROM file_sharing_tbl fst
                                                        LEFT JOIN officials_files_tbl ofl
                                                        ON fst.file_id = ofl.file_id
                                                        LEFT JOIN officials_accounts_tbl sender
                                                        ON fst.sender = sender.official_id
                                                        LEFT JOIN officials_accounts_tbl receiver
                                                        ON fst.receiver = receiver.official_id
                                                        WHERE fst.sender = :official_id
                                                        ORDER BY fst.shared_at DESC
                                                        ");

                    $fetch_file_sharing_logs->execute([":official_id" => $official_id]);
                                                              

                    while ($log_data = $fetch_file_sharing_logs->fetch()) {
                ?>
                    <tr class="text-center">
                    
                        <td class="fw-bold text-primary fixed-column">
                            <?php echo htmlspecialchars($log_data["file_name"]); ?>
                        </td>

                        <td class="fw-bold">
                            <?php echo htmlspecialchars($log_data["file_size"] . " MB"); ?>
                        </td>

                        <td class="fw-bold">
                            <?php echo htmlspecialchars($log_data["sender"]); ?>
                        </td>

                        <td class="fw-bold">
                            <?php echo htmlspecialchars($log_data["receiver"]); ?>
                        </td>

                        <td class="fw-bold">
                            <?php echo htmlspecialchars(format_timestamp($log_data["shared_at"])); ?>
                        </td>

                        <td>                        
                            <a href="../uploads/user-files/<?php echo htmlspecialchars($log_data["stored_file_name"]); ?>"
                               download="<?php echo htmlspecialchars($log_data["file_name"]); ?>" title="Download: <?php echo htmlspecialchars($log_data["file_name"]); ?>"
                               class="btn btn-sm btn-success text-white">
                                <i class="bi bi-download"></i>
                            </a>    
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
