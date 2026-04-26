<?php
  $get_officials_count = $conn->prepare("SELECT 
                                                  p.position,
                                                  COUNT(o.position) AS total
                                              FROM (
                                                  SELECT 'Brgy. Captain' AS position
                                                  UNION ALL SELECT 'Brgy. Secretary'
                                                  UNION ALL SELECT 'Brgy. Treasurer'
                                                  UNION ALL SELECT 'Brgy. Kagawad'
                                                  UNION ALL SELECT 'S.K. Chairman'
                                                  UNION ALL SELECT 'S.K. Kagawad'
                                              ) AS p
                                              LEFT JOIN officials_accounts_tbl o 
                                                ON o.position = p.position
                                              GROUP BY p.position;
                                        ");
  $get_officials_count->execute();

  $brgy_capt = $brgy_sec = $brgy_tre = $brgy_kag = $sk_chair = $sk_kag = 0;

  while($official_count = $get_officials_count->fetch()) {
    switch ($official_count['position']) {
        case 'Brgy. Captain':
            $brgy_capt = (int)$official_count['total'];
            break;
        case 'Brgy. Secretary':
            $brgy_sec = (int)$official_count['total'];
            break;
        case 'Brgy. Treasurer':
            $brgy_tre = (int)$official_count['total'];
            break;
        case 'Brgy. Kagawad':
            $brgy_kag = (int)$official_count['total'];
            break;
        case 'S.K. Chairman':
            $sk_chair = (int)$official_count['total'];
            break;
        case 'S.K. Kagawad':
            $sk_kag = (int)$official_count['total'];
            break;
    }
  }
?>

<main class="main" id="main">
  <?php
  include_once "includes/pagetitle.php";
  ?>

  <section class="section">

    <div class="row">

      <div class="col-12">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-dark text-white fw-bold fs-3 d-flex justify-content-between">
            Officials Accounts

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-official-modal">
              <i class="bi bi-person-plus"></i>
              Add Official
            </button>
          </div>

          <div class="card-body pb-0">

            <table class="table table-light table-hover datatable table-responsive">

              <thead>
                <tr>
                  <th scope="col" class="text-center"> Profile Picture </th>
                  <th scope="col" class="text-center"> Name </th>
                  <th scope="col" class="fixed-column text-center"> Email Address </th>
                  <th scope="col" class="fixed-column text-center"> Position </th>
                  <th scope="col" class="fixed-column text-center"> Created At </th>
                  <th scope="col" class="text-center"> Details </th>
                </tr>
              </thead>

              <tbody>
                <?php
                $fetch_officials_data = $conn->prepare("SELECT 
                                                                              oa.*,
                                                                              oi.*
                                                                          FROM 
                                                                          officials_accounts_tbl oa
                                                                          LEFT JOIN officials_info_tbl oi
                                                                          ON oa.official_id = oi.official_id  
                                                                          ORDER BY 
                                                                          FIELD(oa.position, 'Brgy. Captain', 'Brgy. Secretary', 'Brgy. Treasurer', 'Brgy. Kagawad', 'S.K. Chariman', 'S.K. Kagawad')");
                $fetch_officials_data->execute();

                while ($official_data = $fetch_officials_data->fetch()) {
                  $official_profile_picture = $official_data["profile_picture"];

                  if (empty($official_profile_picture) || !file_exists($image_path . $official_profile_picture)) {
                    $official_profile_picture = "default-img.png";
                  }
                ?>
                  <tr class="text-center">
                    <td>
                      <img src="<?php echo $image_path . $official_profile_picture; ?>" alt="Profile Image" class="rounded-circle" width="45" height="45">
                    </td>

                    <td class="fw-bold">
                      <?php echo htmlspecialchars($official_data["first_name"] . " " . $official_data["last_name"]); ?>
                    </td>

                    <td class="fw-bold">
                      <?php echo htmlspecialchars($official_data["email_address"]); ?>
                    </td>

                    <td class="fw-bold">
                      <?php echo htmlspecialchars($official_data["position"]); ?>
                    </td>

                    <td>
                      <?php echo htmlspecialchars(format_timestamp($official_data["created_at"])); ?>
                    </td>

                    <td>
                      <div class="d-flex justify-content-center align-items-center gap-1 ">
                        <button class="btn btn-primary btn-sm" title="Details" data-bs-toggle="modal" data-bs-target="#official-details-<?php echo htmlspecialchars($official_data["official_id"]); ?>">
                          <i class="bi bi-list"></i>
                        </button>

                        <form action="../process/admin/account-management.php" method="POST" id="delete-account-form">
                          <input type="hidden" name="official-id" value="<?php echo htmlspecialchars(base64_encode($official_data["official_id"])); ?>">
                          <input type="hidden" name="delete-account" value="1">

                          <button
                            type="submit"
                            class="btn btn-danger btn-sm"
                            onclick="confirmAction(
                            event, 
                            this.form, 
                            'delete-account-form',
                            'Delete Account?', 
                            'warning',
                            'Are you sure you want to delete this account??',
                            'Delete',
                            '#dc3545'
                            )"
                            title="Delete Account">
                            <i class="bi bi-trash"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>

                  <!-- Modal -->
                  <div class="modal fade" id="official-details-<?php echo htmlspecialchars($official_data["official_id"]); ?>" tabindex="-1" aria-labelledby="viewAccountLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                      <div class="modal-content shadow-lg border-0">

                        <!-- Header -->
                        <div class="modal-header border-0 pb-0">
                          <div class="w-100">
                            <h4 class="fw-bold mb-1"> Account Information </h4>
                            <small class="text-muted"> Official ID: <?php echo htmlspecialchars($official_data["official_id"]); ?> </small>
                          </div>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Body -->
                        <div class="modal-body pt-0">
                          <div class="row g-4 align-items-center">

                            <!-- Profile Picture -->
                            <div class="col-md-4 text-center">
                              <img src="<?php echo htmlspecialchars($image_path . $official_profile_picture); ?>"
                                alt="Profile Picture"
                                class="rounded-circle shadow-sm border"
                                width="200" height="200">

                              <h5 class="mt-3 mb-0 fw-bold">
                                <?php echo htmlspecialchars($official_data["first_name"] . " " . $official_data["last_name"]); ?>
                              </h5>

                              <span class="text-muted small"><?php echo htmlspecialchars($official_data["email_address"]); ?></span>
                            </div>

                            <!-- User Info -->
                            <div class="col-md-8">
                              <div class="row g-3">

                                <div class="col-sm-12">
                                  <label class="text-muted small"> Position </label>
                                  <p class="fw-semibold mb-0"><?php echo htmlspecialchars($official_data["position"]); ?></p>
                                </div>

                                <div class="col-sm-6">
                                  <label class="text-muted small"> First Name </label>
                                  <p class="fw-semibold mb-0"><?php echo htmlspecialchars($official_data["first_name"]); ?></p>
                                </div>

                                <div class="col-sm-6">
                                  <label class="text-muted small"> Middle Name </label>
                                  <p class="fw-semibold mb-0"><?php echo htmlspecialchars($official_data["middle_name"]); ?></p>
                                </div>

                                <div class="col-sm-6">
                                  <label class="text-muted small"> Last Name </label>
                                  <p class="fw-semibold mb-0"><?php echo htmlspecialchars($official_data["last_name"]); ?></p>
                                </div>

                                <div class="col-sm-6">
                                  <label class="text-muted small"> Gender </label>
                                  <p class="fw-semibold mb-0"><?php echo htmlspecialchars($official_data["gender"]); ?></p>
                                </div>

                                <div class="col-sm-6">
                                  <label class="text-muted small"> Address</label>
                                  <p class="fw-semibold mb-0"><?php echo htmlspecialchars($official_data["full_address"]); ?></p>
                                </div>

                                <div class="col-sm-6">
                                  <label class="text-muted small"> Phone Number </label>
                                  <p class="fw-semibold mb-0"><?php echo htmlspecialchars($official_data["phone_number"]); ?></p>
                                </div>

                              </div>

                            </div>

                          </div>

                        </div>

                        <!-- Footer -->
                        <div class="modal-footer border-0">
                          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"> Close </button>
                        </div>

                      </div>

                    </div>

                  </div>

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

<!-- Modal -->
<div class="modal fade" id="add-official-modal" tabindex="-1" aria-labelledby="addOfficialModal" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">

      <!-- Header -->
      <div class="modal-header border-0 bg-gradient bg-secondary text-white rounded-top-4">
        <h4 class="modal-title fw-bold" id="addOfficialLabel"> Add New Official </h4>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Form -->
      <form action="../process/admin/account-management.php" method="POST" autocomplete="off">

        <div class="modal-body p-4">

          <!-- Personal Info -->
          <h6 class="fw-bold text-uppercase mb-3 border-bottom pb-2">Personal Information</h6>

          <div class="row g-3">

            <div class="col-md-12">
              <label for="position" class="form-label small text-muted"> Position </label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-person-check"></i></span>
                <select class="form-select" id="position" name="position" required>
                  <option value="" selected disabled> Select Position </option>\

                  <?php if($brgy_capt < 1): ?>
                    <option value="Brgy. Captain"> Brgy. Captain </option>
                  <?php endif; ?>

                  <?php if($brgy_sec < 1): ?>
                    <option value="Brgy. Secretary"> Brgy. Secretary </option>
                  <?php endif; ?>

                  <?php if($brgy_tre < 1): ?>
                    <option value="Brgy. Treasurer"> Brgy. Treasurer </option>
                  <?php endif; ?>

                  <?php if($brgy_kag < 7): ?>
                    <option value="Brgy. Kagawad"> Brgy. Kagawad (<?php echo htmlspecialchars((7 - $brgy_kag) . " Available"); ?>) </option>
                  <?php endif; ?>

                  <?php if($sk_chair < 1): ?>
                    <option value="S.K. Chairman"> S.K. Chairman </option>
                  <?php endif; ?>

                  <?php if($sk_kag < 7): ?>
                    <option value="S.K. Kagawad"> S.K. Kagawad (<?php echo htmlspecialchars((7 - $sk_kag) . " Available"); ?>) </option>
                  <?php endif; ?>

                </select>
              </div>
            </div>

            <div class="col-md-4">
              <label for="first_name" class="form-label small text-muted"> First Name </label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-person-fill"></i></span>
                <input type="text" class="form-control" id="first_name" name="first-name" placeholder="First Name" required>
              </div>
            </div>

            <div class="col-md-4">
              <label for="middle_name" class="form-label small text-muted"> Middle Name (Optional) </label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" id="middle_name" name="middle-name" placeholder="Middle Name">
              </div>
            </div>

            <div class="col-md-4">
              <label for="last_name" class="form-label small text-muted"> Last Name </label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-person-fill"></i></span>
                <input type="text" class="form-control" id="last_name" name="last-name" placeholder="Last Name" required>
              </div>
            </div>

            <div class="col-md-6">
              <label for="dob" class="form-label small text-muted"> Date of Birth </label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-calendar-date"></i></span>
                <input type="date" class="form-control" id="dob" name="date-of-birth" placeholder="Date of Birth" required>
              </div>
            </div>

            <div class="col-md-6">
              <label for="gender" class="form-label small text-muted"> Gender </label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-gender-ambiguous"></i></span>
                <select class="form-select" id="gender" name="gender" required>
                  <option value="" selected disabled> Select Gender </option>
                  <option value="Male"> Male </option>
                  <option value="Female"> Female </option>
                  <option value="Others"> Others </option>
                </select>
              </div>
            </div>

          </div>

          <!-- Contact Info -->
          <h6 class="fw-bold text-uppercase mt-4 mb-3 border-bottom pb-2">Contact Information</h6>
          <div class="row g-3">
            <div class="col-md-12">
              <label for="email" class="form-label small text-muted"> Email Address </label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" class="form-control" id="email" name="email-address" placeholder="Email Address" required>
              </div>
            </div>

          </div>

          <!-- Address Info -->
          <h6 class="fw-bold text-uppercase mt-4 mb-3 border-bottom pb-2"> Address & Phone </h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="phone" class="form-label small text-muted"> Phone Number </label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-telephone-fill"></i></span>
                <input type="text" class="form-control" id="phone" name="phone-number" placeholder="Phone Number" required>
              </div>
            </div>

            <div class="col-md-6">
              <label for="address" class="form-label small text-muted"> Address </label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-geo-alt-fill"></i></span>
                <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
              </div>
            </div>
          </div>

          <!-- Note -->
          <div class="alert alert-info mt-4 small">
            <strong>Note:</strong> Default password will be the user's <strong>last name</strong> in <strong>ALL UPPERCASE</strong>.
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer border-0 pt-0">
          <button type="submit" class="btn btn-success px-4" name="add-official-account">
            <i class="bi bi-plus-circle me-1"></i> Add Official
          </button>
          <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
            Cancel
          </button>
        </div>

      </form>

    </div>
  </div>
</div>