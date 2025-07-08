<?php
return [
    'db_path' => __DIR__ . '/../db/tradezone.sqlite',
    'gold_api_key' => getenv('GOLD_API_KEY') ?: '',
    'telegram_token' => getenv('TELEGRAM_TOKEN') ?: '',
    'telegram_chat_id' => getenv('TELEGRAM_CHAT_ID') ?: '',
];
?>
