<?php
header("Content-Type: application/json");

require_once "auth.php";  // Auth yoxlamasını əlavə et
require_once "config.php"; // Database bağlantısını əlavə et

// URL parametrlərini oxu
$lat = isset($_GET['lat']) ? $_GET['lat'] : null;
$lng = isset($_GET['lng']) ? $_GET['lng'] : null;

if ($lat !== null && $lng !== null) {
    // Xüsusi koordinata uyğun məlumatları götür
    $sql = "
        SELECT c.id as coord_id, c.lant, c.lng, 
               i.id as info_id, i.title, i.description, 
               im.id as image_id, im.uri
        FROM coordinates c
        LEFT JOIN informations i ON c.id = i.cord_id
        LEFT JOIN images im ON i.id = im.info_id
        WHERE c.lant = ? AND c.lng = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dd", $lat, $lng);
} else {
    // Bütün məlumatları gətir
    $sql = "
        SELECT c.id as coord_id, c.lant, c.lng, 
               i.id as info_id, i.title, i.description, 
               im.id as image_id, im.uri
        FROM coordinates c
        LEFT JOIN informations i ON c.id = i.cord_id
        LEFT JOIN images im ON i.id = im.info_id
    ";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $coord_id = $row['coord_id'];

    if (!isset($data[$coord_id])) {
        $data[$coord_id] = [
            "id" => $coord_id,  // Koordinatın ID-si əlavə edildi
            "lat" => $row['lant'],
            "lng" => $row['lng'],
            "info" => []
        ];
    }

    if ($row['info_id']) {
        $info_id = $row['info_id'];

        // Mövcud info yoxdursa, əlavə et
        if (!isset($data[$coord_id]['info'][$info_id])) {
            $data[$coord_id]['info'][$info_id] = [
                "id" => $info_id,  // Info ID əlavə edildi
                "title" => $row['title'],
                "description" => $row['description'],
                "images" => []
            ];
        }

        // Şəkil varsa, onu da əlavə et
        if ($row['image_id']) {
            $data[$coord_id]['info'][$info_id]['images'][] = [
                "id" => $row['image_id'],  // Şəkilin ID-si əlavə edildi
                "uri" => $row['uri']
            ];
        }
    }
}

$conn->close();

// Massivi düz formatda JSON-a çevir
$response = [];
foreach ($data as $coord) {
    $coord["info"] = array_values($coord["info"]); // Associative array-i düz massivə çevir
    $response[] = $coord;
}

echo json_encode(["data" => $response], JSON_PRETTY_PRINT);
?>
