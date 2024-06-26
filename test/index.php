<!DOCTYPE html>
<html>
<head>
    <title>Test</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    <?php

    echo "hello";

    $key = "teszt_kulcs";
    $encrypted = encrypt_data($key, json_encode(['name' => 'Teszt']));

    echo '<a href="#" onclick="
    const encrypted = \'' . $encrypted . '\';
    $.ajax({
        url: \'/teszt\',
        type: \'POST\',
        contentType: \'application/json\',
        data: JSON.stringify({data: encrypted}),
        success: function(response) {
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            console.error(status);
            console.error(error);
        }
    });
    return false;">Send POST request to /teszt</a>';

    function encrypt_data($key, $data) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    ?>
</body>
</html>