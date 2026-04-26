<?php
    require_once "../../config/conn.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete-account"])) {
        $user_id = htmlspecialchars(trim($_SESSION["user-id"]));

        if(empty($user_id)) {
            $_SESSION["query-status"] = [
                "status" => "danger",
                "message" => "User not found."
            ];

            unset($_SESSION["user-id"]);

            header("Location: ../../login.php");
            exit();
        }

        else {
            try {
                $delete_account = $conn->prepare("DELETE FROM user_accounts_tbl WHERE user_id = :user_id");
                $delete_account->execute([":user_id" => $user_id]);

                unset($_SESSION["user-id"]);

                $_SESSION["query-status"] = [
                    "status" => "success",
                    "message" => "Account deleted successfully!"
                ];

                header("Location: ../../login.php");
                exit();
            }

            catch(PDOException $e) {
                $_SESSION["query-status"] = [
                    "status" => "danger",
                    "message" => "Unknown error occured! Please try again."
                ];

                header("Location: ../../login.php");
                exit();
            }
        }
    }
?>