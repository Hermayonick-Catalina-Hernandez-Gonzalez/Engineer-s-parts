<?php
function apacheIsRunning() {
    $url = 'http://localhost';
    $contextOptions = [
        'http' => [
            'method' => 'GET',
            'timeout' => 5,
        ]
    ];
    $context = stream_context_create($contextOptions);
    $response = @file_get_contents($url, false, $context);
    return $response!== false;
}

if(apacheIsRunning()){
    header('Location: ../vistas/servidorNotConnected.html');
}