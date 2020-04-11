<?php

$servers = [
    'https://ya.ru:8081',
    'https://ya.ru:8080',
    '129.67.1.190:53',
    '129.67.1.190:54',
    '129.67.1.190:55',
    '129.67.1.190:56',
    '108.162.216.211:80',
    '108.162.216.211:443',
    'https://www.linkedin.com:443',
    'http://www.linkedin.com:80',
];

$pIds = [];
$threads = array_key_exists(1, $argv) ? $argv[1] : 10;
$serversCount = count($servers);

function pingServer(string $server) :void {
    $successMessage = "Server $server is running. \n";
    $errorMessage = "Server $server is not available \n";
    $httpClient = curl_init();

    curl_setopt($httpClient, CURLOPT_URL, $server);
    curl_setopt($httpClient, CURLOPT_RETURNTRANSFER, false);
    curl_setopt($httpClient, CURLOPT_TIMEOUT, 5);

    curl_exec($httpClient);

    if (!curl_errno($httpClient)) {
        $status = curl_getinfo($httpClient, CURLINFO_HTTP_CODE);

        if ($status >= 200 && $status < 500) {
            echo $successMessage;
        } else {
            echo $errorMessage;
        }

    } else {
        echo $errorMessage;
    }

    curl_close($httpClient);
}


echo "Threads count = $threads \n";
echo "Servers count = $serversCount \n";

for ($i = 0; $i < $serversCount; $i++) {
    if (count($pIds) >= $threads) {
        $pid = pcntl_waitpid(-1, $status);
        unset($pIds[$pid]);
    }

    $pid = pcntl_fork();

    if ($pid === -1) {
        die('Could not fork the process');
    }

    if ($pid) {
        $pIds[$pid] = $pid;
    } else {
        pingServer($servers[$i]);
        exit;
    }
}

foreach ($pIds as $pid) {
    pcntl_waitpid($pid, $status);
    unset($pIds[$pid]);
}
