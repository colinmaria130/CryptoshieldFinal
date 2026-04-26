<?php
    require_once "../config/conn.php";

    try {
        $insert_account = $conn->prepare("INSERT INTO admin_accounts_tbl(first_name, middle_name, last_name, email_address, admin_password)
                                        VALUES(:first_name, :middle_name, :last_name, :email_address, :admin_password)
                                        ");
        $insert_account->execute([
            ":first_name" => "Colin Maria",
            ":middle_name" => "Castanares",
            ":last_name" => "Pampango",
            ":email_address" => "coca.pampango.ui@phinmaed.com",
            ":admin_password" => password_hash("Colin13!", PASSWORD_BCRYPT)
        ]);

        $admin_id = $conn->lastInsertId();

        $insert_info = $conn->prepare("INSERT INTO admin_info_tbl(admin_id, date_of_birth, gender, phone_number, full_address)
                                    VALUES(:admin_id, :date_of_birth, :gender, :phone_number, :full_address)
                                    ");

        $insert_info->execute([
            ":admin_id" => $admin_id,
            ":date_of_birth" => "2004-05-13",
            ":gender" => "Female",
            ":phone_number" => "09163500594",
            ":full_address" => "North San Jose, Molo, Iloilo City"
        ]);

        header("Location: ../index.php");
        exit();
    }

    catch(PDOException $e) {
        echo $e->getMessage();
    }
?>