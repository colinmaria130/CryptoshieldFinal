<main class="main" id="main">
    <?php
    include_once "includes/pagetitle.php";
    ?>

    <div class="row">
        <div class="col-12">
            <div class="card top-selling overflow-auto">

                <div class="card-body pb-0">
                    <h5 class="card-title"> Uploaded Files (Click to Download) </h5>

                    <table class="table table-light table-hover datatable table-responsive">

                        <thead>
                            <tr>
                                <th scope="col" class="text-center"> Used Method </th>
                                <th scope="col" class="text-center"> Original File </th>
                                <th scope="col" class="fixed-column text-center"> Processed File </th>
                                <th scope="col" class="text-center"> Processed At </th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $fetch_uploaded_files = $conn->prepare("SELECT * FROM user_uploaded_files WHERE user_id = :user_id ORDER BY processed_at DESC");
                                $fetch_uploaded_files->execute([":user_id" => $user_id]);

                                while($file_data = $fetch_uploaded_files->fetch()) {
                                    ?>
                                        <tr class="text-center">
                                            <td class="fw-bold">
                                                <?php echo htmlspecialchars($file_data["used_method"]); ?>
                                            </td>

                                            <td>
                                                <a href="../uploads/user-files/<?php echo htmlspecialchars($file_data["stored_original_name"]); ?>" download="Original_File_<?php echo htmlspecialchars($file_data["original_name"]); ?>">
                                                    <?php echo htmlspecialchars($file_data["original_name"]); ?>
                                                </a>
                                            </td>

                                            <td>
                                                <a href="../uploads/user-files/<?php echo htmlspecialchars($file_data["stored_processed_name"]); ?>" download="Processed_File_<?php echo htmlspecialchars($file_data["original_name"]); ?>">
                                                    <?php echo htmlspecialchars($file_data["original_name"]); ?>
                                                </a>
                                            </td>

                                            
                                            <td>
                                                <?php echo htmlspecialchars(format_timestamp($file_data["processed_at"])); ?>
                                            </td>
                                        </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                        
                    </table>

                </div>

            </div>
        </div><!-- End Top Selling -->
    </div>
</main>