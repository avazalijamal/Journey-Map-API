<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: DELETE");

require_once "auth.php";  // Auth yoxlamasını əlavə et
require_once "config.php"; // Database bağlantısını əlavə et

// JSON request qəbul et
$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['id'])) {
    echo json_encode(["error" => "Missing id parameter"]);
    exit;
}

$coord_id = $input['id'];

// Əvvəlcə şəkillərin fiziki fayllarını sil
$sql = "SELECT im.uri FROM images im 
        JOIN informations i ON im.info_id = i.id 
        WHERE i.cord_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $coord_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $file_path = $row['uri'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

// Əsas koordinat məlumatını sil
$stmt = $conn->prepare("DELETE FROM coordinates WHERE id = ?");
$stmt->bind_param("i", $coord_id);
if ($stmt->execute()) {
    echo json_encode(["message" => "Data deleted successfully"]);
} else {
    echo json_encode(["error" => "Failed to delete data"]);
}

$conn->close();
?>
