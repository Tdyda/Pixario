#!/usr/bin/env php
<?php

$json = file_get_contents('base64.json');
$data = json_decode($json, true);

if (!isset($data['data']['image'])) {
    die("❌ Brak pola data.image\n");
}

$base64 = $data['data']['image'];
$imageData = base64_decode($base64);

if ($imageData === false) {
    die("❌ Nie udało się zdekodować Base64\n");
}

file_put_contents('fixed_photo.jpg', $imageData);
echo "✅ Zapisano: fixed_photo.jpg\n";

