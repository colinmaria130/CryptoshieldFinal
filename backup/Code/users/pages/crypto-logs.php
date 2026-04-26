<main class="main" id="main">
    <?php
    include_once "includes/pagetitle.php";
    ?>

    <div class="row">

        <div class="col-12">
            <div class="card top-selling overflow-auto">

                <div class="card-body pb-0">
                    <h5 class="card-title"> 
                        User Crypto Logs
                        <span id="copyStatus"></span>
                    </h5>

                    <table class="table table-light table-hover datatable table-responsive">

                        <thead>
                            <tr>
                                <th scope="col" class="text-center"> Algorithm Type </th>
                                <th scope="col" class="text-center"> Used Method </th>
                                <th scope="col" class="fixed-column text-center"> Original Text </th>
                                <th scope="col" class="fixed-column text-center"> Output </th>
                                <th scope="col" class="text-center"> Processed At </th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $fetch_user_logs = $conn->prepare("SELECT * FROM user_logs_tbl WHERE user_id = :user_id ORDER BY processed_at DESC");
                            $fetch_user_logs->execute([":user_id" => $user_id]);

                            while ($log_data = $fetch_user_logs->fetch()) {
                            ?>
                                <tr class="text-center">
                                    <td class="fw-bold">
                                        <?php echo htmlspecialchars($log_data["algorithm_type"]); ?>
                                    </td>

                                    <td class="fw-bold">
                                        <?php echo htmlspecialchars($log_data["used_method"]); ?>
                                    </td>

                                    <td class="fixed-column" style="cursor: pointer" id="original-text" onclick="copyToClipboard('original-text')">
                                        <?php echo htmlspecialchars($log_data["original_text"]); ?>
                                    </td>

                                    <td class="fixed-column" style="cursor: pointer" id="output-text" onclick="copyToClipboard('output-text')">
                                        <?php echo htmlspecialchars($log_data["output"]); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars(format_timestamp($log_data["processed_at"])); ?>
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

</main>