<?php

    if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["official-id"])) {
    // Decode and sanitize official-id
    $receiver_official_id = base64_decode($_GET["official-id"], true);
    $receiver_official_id = $receiver_official_id !== false ? trim($receiver_official_id) : null;

        if($receiver_official_id) {
            // Fetch receiver data
            $get_receiver_data = $conn->prepare("
                SELECT 
                    CONCAT(oa.first_name, ' ', oa.last_name) AS receiver,
                    oa.is_online,
                    oi.profile_picture
                FROM officials_accounts_tbl oa
                LEFT JOIN officials_info_tbl oi 
                    ON oa.official_id = oi.official_id
                WHERE oa.official_id = :receiver_id
                LIMIT 1
            ");
            $get_receiver_data->execute([":receiver_id" => $receiver_official_id]);

            if ($receiver_data = $get_receiver_data->fetch(PDO::FETCH_OBJ)) {
                $receiver = $receiver_data->receiver;
                $receiver_image = $image_path . (!empty($receiver_data->profile_picture) 
                                    ? $receiver_data->profile_picture 
                                    : "default-img.png");
                $receiver_status = $receiver_data->is_online;

                // Fetch conversation between sender and receiver
                $fetch_conversation = $conn->prepare("
                    SELECT 
                        fs.*,
                        ofl.file_name,
                        ofl.file_size,
                        ofl.stored_file_name,
                        ofl.processed_file_name
                    FROM file_sharing_tbl fs
                    LEFT JOIN officials_files_tbl ofl 
                        ON fs.file_id = ofl.file_id
                    WHERE 
                        (fs.sender = :sender AND fs.receiver = :receiver)
                        OR (fs.sender = :receiver2 AND fs.receiver = :sender2)
                    ORDER BY fs.shared_at ASC
                ");
                $fetch_conversation->execute([
                    ":sender"   => $official_id,
                    ":receiver" => $receiver_official_id,
                    ":sender2"   => $official_id,
                    ":receiver2" => $receiver_official_id
                ]);

                $messages = $fetch_conversation->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // Receiver not found in DB
                $receiver = "Unknown User";
                $receiver_image = $image_path . "default-img.png";
                $messages = [];
                $receiver_status = "No";
            }
        } 
        
        else {
            $receiver = "Invalid User ID";
            $receiver_image = $image_path . "default-img.png";
            $messages = [];
            $receiver_status = "No";
        }
    } 
    
    else {
        $receiver = "Please select a user to share files with";
        $receiver_image = $image_path . "default-img.png";
        $messages = [];
        $receiver_status = "No";
    }
?>


<main class="main" id="main">

    <?php
    include_once "includes/pagetitle.php";
    ?>

    <section class="section" id="section">

        <div class="container">

            <div class="row">

                <!-- Left Sidebar: Users List -->
                <div class="col-lg-4 col-md-5 mb-3">

                    <div class="card shadow-sm border-0">

                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0"> Users List </h5>
                        </div>

                        <div class="card-body p-0">

                            <ul class="list-group list-group-flush" style="max-height: 70vh; overflow-y: auto;">

                                <?php
                                    $get_users = $conn->prepare("SELECT
                                                                    oa.*,
                                                                    oi.profile_picture
                                                                FROM officials_accounts_tbl oa
                                                                LEFT JOIN officials_info_tbl oi
                                                                ON oa.official_id = oi.official_id
                                                                WHERE oa.official_id != :official_id
                                                                ORDER BY FIELD(oa.position, 'Brgy. Captain', 'S.K. Chairman', 'Brgy. Secretary', 'Brgy. Treasurer', 'Brgy. Kagawad', 'S.K. Kagawad')
                                                                ");
                                    $get_users->execute([":official_id" => $official_id]);

                                    while($users_data = $get_users->fetch()) {
                                        $user_profile_picture = $users_data["profile_picture"];
                                        if(empty($user_profile_picture) || !file_exists($image_path . $user_profile_picture)) {
                                            $user_profile_picture = "default-img.png";
                                        }  
                                ?>
                                    
                                    <li class="list-group-item d-flex align-items-center user-item <?php echo ($users_data["official_id"] == $receiver_official_id) ? "bg-light border border-secondary" : ""; ?> py-3">

                                        <img src="<?php echo htmlspecialchars($image_path . $user_profile_picture); ?>" alt="Profile Picture" class="rounded-circle me-3" width="45" height="45">

                                        <div class="flex-grow-1">

                                            <h6 class="mb-0 d-flex align-items-center gap-2">
                                                <span class="fw-bold text-<?php echo htmlspecialchars($users_data["is_online"] === "Yes" ? "success" : "danger"); ?>" style="font-size: 8px;"> 
                                                    <i class="bi bi-circle-fill"></i>
                                                </span>

                                                <?php echo htmlspecialchars($users_data["first_name"] . " " . $users_data["last_name"]); ?>
                                            </h6>

                                            <small class="text-muted" style="font-size: 12px;">
                                                <?php echo htmlspecialchars($users_data["position"]); ?>
                                            </small>

                                        </div>

                                        <a href="home.php?page=file-sharing&official-id=<?php echo htmlspecialchars(base64_encode($users_data["official_id"])); ?>" class="btn btn-sm btn-outline-primary" title="Open Chat">

                                            <i class="bi bi-send-check me-1"></i>

                                        </a>

                                    </li>

                                <?php
                                    }
                                ?>
 

                            </ul>
                        </div>

                    </div>
                </div>

                <!-- Right Side: File Sharing Conversation -->
                <div class="col-lg-8 col-md-7">

                    <div class="card shadow-sm border-0 h-75">

                        <!-- Header -->
                        <div class="card-header bg-white border-0 d-flex align-items-center border border-bottom">
                            <img src="<?php echo htmlspecialchars($receiver_image); ?>" alt="User Image" class="rounded-circle me-3" width="40" height="40">
                            <div>
                                <h6 class="mb-0 text-dark"> <?php echo htmlspecialchars($receiver); ?> </h6>
                                
                                <small class="text-muted" style="font-size: 12px;">
                                    <?php
                                        if($receiver_status === "Yes") {
                                    ?>
                                        <span class="text-success">
                                            Online
                                        </span>
                                    <?php
                                        } else {
                                    ?>
                                        <span class="text-danger">
                                            Offline
                                        </span>
                                    <?php
                                        }
                                    ?>
                                </small>

                            </div>
                        </div>

                        <div class="card-body p-3" id="chatBox"
                            style="height:calc(100vh - 250px); overflow-y:auto; overflow-x:hidden; display:flex; flex-direction:column;">

                            <?php if (!empty($messages)): ?>
                                <?php foreach ($messages as $msg): ?>
                                    <?php if ($msg['sender'] == $official_id): ?>

                                        <!-- Sender (Right Side) -->
                                        <a href="../uploads/user-files/<?php echo htmlspecialchars($msg["stored_file_name"]); ?>" download="<?php echo htmlspecialchars($msg["file_name"]); ?>" class="text-dark" style="font-size: 13px;" title="Click to Download">
                                            <div class="d-flex mb-3 justify-content-end">
                                                <div class="p-3 rounded-3 bg-primary text-white shadow-sm" style="max-width: 75%;">
                                                    <i class="bi bi-file-earmark me-1"></i>
                                                    <?= htmlspecialchars($msg['file_name']) ?><br>
                                                    <small class="text-light">
                                                        <?= $msg['file_size'] ?> mb · Sent <?= format_timestamp($msg['shared_at']); ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </a>

                                    <?php else: ?>

                                        <div class="d-flex gap-0">

                                            <img src="<?php echo htmlspecialchars($receiver_image); ?>" alt="User Image" class="rounded-circle me-1" width="40" height="40" title="<?php echo htmlspecialchars($receiver); ?>" style="cursor: pointer;">
                                            <!-- Receiver (Left Side) -->
                                            <a href="../uploads/user-files/<?php echo htmlspecialchars($msg["stored_file_name"]); ?>" download="<?php echo htmlspecialchars($msg["file_name"]); ?>" class="text-dark" style="font-size: 13px;" title="Click to Download">
                                                <div class="d-flex mb-3">
                                                    <div class="p-3 rounded-3 bg-light border shadow-sm" style="max-width: 75%;">
                                                        <i class="bi bi-file-earmark me-1"></i>
                                                        <?= htmlspecialchars($msg['file_name']) ?><br>
                                                        <small class="text-muted">
                                                            <?= $msg['file_size'] ?> mb · Received <?= format_timestamp($msg['shared_at']); ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </a>

                                        </div>

                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center text-muted">No files shared yet.</div>
                            <?php endif; ?>

                        </div>

                        <?php if(isset($_GET["official-id"])): ?>
                            <!-- File Upload Input -->
                            <div class="card-footer bg-white border-0">
                                <form action="../process/officials/file-sharing.php" method="POST" enctype="multipart/form-data" class="d-flex w-100">

                                    <input type="hidden" name="receiver-id" value="<?php echo htmlspecialchars(base64_encode($receiver_official_id)); ?>">

                                    <input type="file" name="uploaded-file" class="form-control" required
                                        accept=".txt,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.csv,.json,.xml,.zip,.rar,.7z,.html,.php,.js,.css">
                                    <button type="submit" class="btn btn-primary mx-1" name="share-file">
                                        <i class="bi bi-send"></i>
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>


                    </div>

                </div>

            </div>

        </div>

    </section>

</main>

<!-- Scroll to Bottom Script -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    var chatBox = document.getElementById("chatBox");
    chatBox.scrollTop = chatBox.scrollHeight; // auto-scroll to latest message
  });
</script>