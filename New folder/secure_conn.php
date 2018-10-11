<?php
	$https = filter_input(INPUT_SERVER, 'HTTPS');//creates a secure connection htts for log in and register
    if (!$https) {
        $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
        $url = 'https://' . $host . $uri;
        header("Location: " . $url);
        exit();
    }
?>