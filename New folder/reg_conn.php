<?php
    $https = filter_input(INPUT_SERVER, 'HTTPS');//connects regularly http not secure
    if ($https) {
        $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
        $url = 'http://' . $host . $uri;
        header("Location: " . $url);
        exit();
    }
?>