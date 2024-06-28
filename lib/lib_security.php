<?php

/**
 * AES-256-CBC titkosítás egy adott kulccsal string adatokra (tömbökhoz json_encode segítségével használható)
 * @param string $key   A titkosításhoz használt kulcs
 * @param string $data  A titkosítandó adat
 * @return string       A titkosított karakterlánc
 */

function encrypt_data($key, $data) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

/**
 * CRSF token készítése egy adott kulccsal
 * @param string $key   A token készítéséhez használt kulcs
 * @return string       A token
*/
function generate_csrf_token() {
    if(session_status() == PHP_SESSION_NONE)
        session_start();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}