<?php
    require_once "../process/users/user-encryption-process.php";

    header("Content-Type: application/json");

    $encryption_type = $_POST['encryption_type'] ?? 'aes';
    $user_id = htmlspecialchars(base64_decode($_POST["user-id"]));
    $action = $_POST['action'] ?? null;

    $encryption_label = "";
    $encryption_text = "";
    $status = "success";

    $text = $_POST['text'] ?? '';

    // AES ENCRYPTION
    if ($encryption_type === 'aes') {
        include_once "../private/aes_algo.php"; // assumes $aes_key, $cipher_algo, $iv_length, $options are defined

        if ($action === 'aes_encrypt') {
            $iv = openssl_random_pseudo_bytes($iv_length);
            $ciphertext_raw = openssl_encrypt($text, $cipher_algo, $aes_key, $options, $iv);
            $ciphertext = base64_encode($iv . $ciphertext_raw);

            $encryption_label = "Encrypted AES (Base64)";
            $encryption_text = htmlspecialchars($ciphertext);

            storeCyrptoLog(
                $user_id,
                "AES",
                "Encrypt",
                $text,
                $encryption_text
            );
        } 
        
        else if ($action === 'aes_decrypt') {
            $ciphertext_decoded = base64_decode($text);
            $iv = substr($ciphertext_decoded, 0, $iv_length);
            $ciphertext_raw = substr($ciphertext_decoded, $iv_length);
            $decrypted = openssl_decrypt($ciphertext_raw, $cipher_algo, $aes_key, $options, $iv);

            $encryption_label = "Decrypted Text";
            $encryption_text = htmlspecialchars($decrypted);

            storeCyrptoLog(
                $user_id,
                "AES",
                "Decrypt",
                $text,
                $decrypted
            );
        }
    }

    // RSA ENCRYPTION
    if ($encryption_type === 'rsa') {
        $rsa_private_key = file_get_contents('../private/private.pem');
        $rsa_public_key = file_get_contents('../private/public.pem');

        if ($action === 'rsa_encrypt') {
            openssl_public_encrypt($text, $encrypted, $rsa_public_key);
            $ciphertext = base64_encode($encrypted);

            $encryption_label = "Encrypted RSA (Base64)";
            $encryption_text = htmlspecialchars($ciphertext);

            storeCyrptoLog(
                $user_id,
                "RSA",
                "Encrypt",
                $text,
                $encryption_text
            );

        } 
        
        elseif ($action === 'rsa_decrypt') {
            $ciphertext = base64_decode($text);
            openssl_private_decrypt($ciphertext, $decrypted, $rsa_private_key);

            $encryption_label = "Decrypted Text";
            $encryption_text = htmlspecialchars($decrypted);

            storeCyrptoLog(
                $user_id,
                "RSA",
                "Decrypt",
                $text,
                $decrypted
            );
        }
    }

    echo json_encode([
        'status' => $status,
        'label' => $encryption_label,
        'text' => $encryption_text
    ]);

    exit();
?>
