<?php
// lib.php

const PRIVATE_KEY_FILE = 'private_key.pem';
const PUBLIC_KEY_FILE = 'public_key.pem';
const CURVE = 'prime256v1';

/**
 * Kulcsok generálása: privát és publikus kulcs
 */
function generate_encryption_keys()
{
    $config = [
        "private_key_type" => OPENSSL_KEYTYPE_EC,
        "curve_name" => CURVE
    ];

    $res = openssl_pkey_new($config);
    openssl_pkey_export($res, $privateKey);

    $keyDetails = openssl_pkey_get_details($res);
    $publicKey = $keyDetails['key'];

    file_put_contents(PRIVATE_KEY_FILE, $privateKey);
    file_put_contents(PUBLIC_KEY_FILE, $publicKey);
}

/**
 * A titkosításhoz szükséges kulcsok lekérdezése
 * @return array - A kulcsokat tartalmazó asszociatív tömb: ['private_key' => '...', 'public_key' => '...']
 */
function get_enc_keys()
{
    if (day_passed()) {
        generate_encryption_keys();
    }

    $private_key = file_get_contents(PRIVATE_KEY_FILE);
    $public_key = file_get_contents(PUBLIC_KEY_FILE);

    return ['private_key' => $private_key, 'public_key' => $public_key];
}

/**
 * Ellenőrzi, hogy a kulcsokat generálni kell-e
 * @return bool - Igaz, ha a kulcsokat újra kell generálni
 */
function day_passed()
{
    if (!file_exists(PRIVATE_KEY_FILE) || !file_exists(PUBLIC_KEY_FILE)) {
        return true;
    }

    $file_modified_at = date('Y-m-d', filemtime(PRIVATE_KEY_FILE));
    $today = date('Y-m-d');

    return $today !== $file_modified_at;
}

/**
 * Adat titkosítása
 * @param string $data - A titkosítandó adat
 * @param string $publicKey - A publikus kulcs
 * @return string|false - A titkosított adat vagy hamis, ha a titkosítás sikertelen
 */
function encrypt_data($data, $publicKey)
{
    $publicKey = openssl_pkey_get_public($publicKey);

    $ecdh = openssl_pkey_new([
        'private_key_type' => OPENSSL_KEYTYPE_EC,
        'curve_name' => CURVE
    ]);
    openssl_pkey_export($ecdh, $ecdh_privateKey);

    $ecdh_details = openssl_pkey_get_details($ecdh);

    $ecdh_publicKey = $ecdh_details['key'];

    $sharedKey = openssl_pkey_derive($publicKey, $ecdh, 32);

    $aesKey = substr($sharedKey, 0, 32);
    $iv = openssl_random_pseudo_bytes(16);

    $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $aesKey, 0, $iv);

    return base64_encode($iv . $encryptedData . $ecdh_publicKey);
}

/**
 * Adat visszafejtése
 * @param string $encrypted_data - A titkosított adat
 * @param string $privateKey - A privát kulcs
 * @return string|false - A visszafejtett adat vagy hamis, ha a visszafejtés sikertelen
 */
function decrypt_data($encrypted_data, $privateKey)
{
    $encrypted_data = base64_decode($encrypted_data);

    $iv = substr($encrypted_data, 0, 16);
    $encryptedData = substr($encrypted_data, 16, -strlen(openssl_pkey_get_details(openssl_pkey_get_private($privateKey))['key']));
    $ecdh_publicKey = substr($encrypted_data, -strlen(openssl_pkey_get_details(openssl_pkey_get_private($privateKey))['key']));

    $privateKey = openssl_pkey_get_private($privateKey);
    $sharedKey = openssl_pkey_derive($ecdh_publicKey, $privateKey, 32);

    $aesKey = substr($sharedKey, 0, 32);

    return openssl_decrypt($encryptedData, 'aes-256-cbc', $aesKey, 0, $iv);
}
