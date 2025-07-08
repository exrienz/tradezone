<?php
$config = include __DIR__ . '/../app/config/config.php';
$logFile = __DIR__ . '/monitor.log';
$db = new PDO('sqlite:' . $config['db_path']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function logMsg($msg, $logFile) {
    file_put_contents($logFile, '['.date('c')."] $msg\n", FILE_APPEND);
}

function fetchPrice($symbol, $apiKey) {
    $url = "https://api.gold-api.com/price/$symbol";
    $options = [
        'http' => [
            'header' => "x-access-token: $apiKey\r\n"
        ]
    ];
    $context = stream_context_create($options);
    $json = @file_get_contents($url, false, $context);
    if($json === false) return null;
    $data = json_decode($json, true);
    return $data['price'] ?? null;
}

function sendTelegram($token, $chatId, $text) {
    if(!$token || !$chatId) return;
    $url = "https://api.telegram.org/bot{$token}/sendMessage";
    $params = http_build_query(['chat_id' => $chatId, 'text' => $text]);
    @file_get_contents($url.'?'.$params);
}

while(true) {
    $zones = $db->query('SELECT * FROM zones')->fetchAll(PDO::FETCH_ASSOC);
    foreach($zones as $z) {
        $price = fetchPrice($z['symbol'], $config['gold_api_key']);
        if(!$price) continue;
        $enteredAt = $z['entered_at'];
        if(is_null($enteredAt) && $price >= $z['lower_bound'] && $price <= $z['upper_bound']) {
            sendTelegram($config['telegram_token'], $config['telegram_chat_id'], "{$z['symbol']} entered zone {$z['lower_bound']}-{$z['upper_bound']}");
            logMsg("{$z['symbol']} entered zone", $logFile);
            $stmt = $db->prepare('UPDATE zones SET entered_at=? WHERE id=?');
            $stmt->execute([time(), $z['id']]);
        } elseif(!is_null($enteredAt)) {
            $diff = time() - $enteredAt;
            if($diff >= 600) {
                if($z['direction'] == 'Uptrend') {
                    if($price < $z['lower_bound']) {
                        sendTelegram($config['telegram_token'], $config['telegram_chat_id'], "{$z['symbol']} retest");
                        logMsg("{$z['symbol']} retest", $logFile);
                        $db->prepare('UPDATE zones SET entered_at=NULL WHERE id=?')->execute([$z['id']]);
                    } elseif($price > $z['upper_bound']) {
                        sendTelegram($config['telegram_token'], $config['telegram_chat_id'], "{$z['symbol']} invalidated");
                        logMsg("{$z['symbol']} invalidated", $logFile);
                        $db->prepare('DELETE FROM zones WHERE id=?')->execute([$z['id']]);
                    }
                } else { // Downtrend
                    if($price > $z['upper_bound']) {
                        sendTelegram($config['telegram_token'], $config['telegram_chat_id'], "{$z['symbol']} retest");
                        logMsg("{$z['symbol']} retest", $logFile);
                        $db->prepare('UPDATE zones SET entered_at=NULL WHERE id=?')->execute([$z['id']]);
                    } elseif($price < $z['lower_bound']) {
                        sendTelegram($config['telegram_token'], $config['telegram_chat_id'], "{$z['symbol']} invalidated");
                        logMsg("{$z['symbol']} invalidated", $logFile);
                        $db->prepare('DELETE FROM zones WHERE id=?')->execute([$z['id']]);
                    }
                }
            }
        }
    }
    sleep(10);
}
