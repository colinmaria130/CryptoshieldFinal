<?php
    require_once "../config/conn.php";

    try {
        $update_login_status = $conn->prepare("UPDATE officials_accounts_tbl SET is_online = :is_online WHERE official_id = :official_id");
        $update_login_status->execute([
            ":is_online" => "No",
            ":official_id" => $_SESSION["official-id"]
        ]);

        unset($_SESSION["offical-id"]);

        header("Location: ../index.php");
        exit();
    }

    catch(PDOException $e) {
        unset($_SESSION["offical-id"]);

        header("Location: ../index.php");
        exit();
    }
    
?>