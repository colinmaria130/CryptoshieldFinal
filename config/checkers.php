<?php

    function generateUserId($length = 12) {
        $random_numbers = "";

        for($i = 0; $i < $length; $i++) {
            $random_numbers .= mt_rand(0, 9);
        }
        
        return "user@" . $random_numbers;
    }

    function generate_verification_token($length = 16) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomToken = '';

        for ($i = 0; $i < $length; $i++) {
            $randomToken .= $characters[random_int(0, $charactersLength - 1)];
        }
    
        return $randomToken;
    }

    function generate_expiry_time($minutes) {
         $expiry_time = date("Y-m-d H:i:s", strtotime("+$minutes minutes"));

         return $expiry_time;
    }
    
    $allowed_img_format = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'tiff', 'ico', 'jfif'];
    $allowed_genders = ["Male", "Female", "Others"];
    $allowed_officials = [
        "Brgy. Captain",
        "Brgy. Secretary",
        "Brgy. Treasurer",
        "Brgy. Kagawad",
        "S.K. Chairman",
        "S.K. Kagawad"
    ];

    $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,16}$/';
    $cellphone_pattern = '/^(\+639|09)\d{9}$/'; 
    $date_pattern = '/^(19|20)\d{2}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/';

?>