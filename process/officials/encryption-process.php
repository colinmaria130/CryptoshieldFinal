<?php
    require_once "../../private/aes_algo.php";

    function isRestrictedFileType($filename, $mimeType) {

        $blockedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'mp4', 'mov', 'avi', 'mkv', 'flv', 'webm'];
        $blockedMimes = ['image/', 'video/'];
        
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if(in_array($extension, $blockedExtensions)) return true;

        foreach ($blockedMimes as $mime) {
            if (strpos($mimeType, $mime) === 0) return true;
        }

        return false;
    }

    // === AES Encryption ===
    function encryptFile($data, $aes_key, $cipher_algo, $options, $iv_length, &$ivOut) {
        $key = hash('sha256', $aes_key, true);
        $iv = openssl_random_pseudo_bytes($iv_length);
        $ivOut = $iv;
        return $iv . openssl_encrypt($data, $cipher_algo, $key, $options, $iv);
    }

    // === AES Decryption ===
    function decryptFile($data, $aes_key, $cipher_algo, $options, $iv_length) {
        $key = hash('sha256', $aes_key, true);
        $iv = substr($data, 0, $iv_length);
        $ciphertext = substr($data, $iv_length);
        return openssl_decrypt($ciphertext, $cipher_algo, $key, $options, $iv);
    }
?>