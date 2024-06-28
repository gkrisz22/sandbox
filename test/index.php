<!DOCTYPE html>
<html>
<head>
    <title>Test</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    <?php

    require '../lib/lib_security.php';

    echo "hello";
    $encrypted = encrypt_data('teszt_kulcs', json_encode(['name' => 'Teszt', 'age' => 20, 'email' => 'teszt@teszt.com']));

    echo '<a href="#" onclick="
    const encrypted = \'' . $encrypted . '\';
    $.ajax({
        url: \'/oktatas/teszt\',
        type: \'POST\',
        contentType: \'application/json\',
        data: JSON.stringify({data: encrypted}),
        success: function(response) {
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
    return false;">Send POST request to /teszt</a>';

    ?>


    <form action="/oktatas/teszt" method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars(generate_csrf_token()) ?>">
        <input type="text" name="szoveg" placeholder="Ez szöveg">
        <input type="number" name="szam" placeholder="Ez egy szám">
        <button type="submit">Send</button>
    </form>
</body>
</html>