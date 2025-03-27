<?php
// Burada icazə verilmiş API açarlarını müəyyən edirik
$valid_api_keys = [
    "1234567890abcdef",   // Nümunə API açarı
];

// HTTP Header-dən API açarını oxuyuruq
$headers = getallheaders();
if (!isset($headers['API-Key']) || !in_array($headers['API-Key'], $valid_api_keys)) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized access"]);
    exit;
}
?>
