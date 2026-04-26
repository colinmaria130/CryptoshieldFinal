<?php
    require_once "../config/conn.php";

    function storeCyrptoLog($user_id, $type, $method, $original_text, $output) {
        global $conn;

        try {
            $insert_user_log = $conn->prepare("INSERT INTO user_logs_tbl(user_id, algorithm_type, used_method, original_text, output)
                                            VALUES(:user_id, :type, :method, :original_text, :output)");
            
            $insert_user_log->execute([
                ":user_id" => $user_id,
                ":type" => $type,
                ":method" => $method,
                ":original_text" => $original_text,
                ":output" => $output
            ]);
        }

        catch(PDOException $e) {
            throw $e;
        }
    }
?>