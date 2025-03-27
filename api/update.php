<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: PUT");

require_once "auth.php";  // Auth yoxlamasını əlavə et
require_once "config.php"; // Database bağlantısını əlavə et

// JSON request qəbul et
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(["error" => "Missing id parameter"]);
    exit;
}

$coord_id = $data['id'];
$lat = isset($data['lat']) ? $data['lat'] : null;
$lng = isset($data['lng']) ? $data['lng'] : null;

// Koordinatları yenilə
if ($lat !== null && $lng !== null) {
    $stmt = $conn->prepare("UPDATE coordinates SET lant = ?, lng = ? WHERE id = ?");
    $stmt->bind_param("ddi", $lat, $lng, $coord_id);
    $stmt->execute();
}

// Informations yenilə
if (isset($data['info']) && is_array($data['info'])) {
    foreach ($data['info'] as $info) {
        if (!isset($info['id'])) continue;  // info_id mütləq olmalıdır
        $info_id = $info['id'];
        $title = isset($info['title']) ? $info['title'] : null;
        $description = isset($info['description']) ? $info['description'] : null;

        $stmt = $conn->prepare("UPDATE informations SET title = ?, description = ? WHERE id = ? AND cord_id = ?");
        $stmt->bind_param("ssii", $title, $description, $info_id, $coord_id);
        $stmt->execute();

        // Şəkilləri yenilə
        if (isset($info['images']) && is_array($info['images'])) {
            // Köhnə şəkilləri sil
            $stmt = $conn->prepare("SELECT uri FROM images WHERE info_id = ?");
            $stmt->bind_param("i", $info_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                if (file_exists($row['uri'])) {
                    unlink($row['uri']);
                }
            }

            $stmt = $conn->prepare("DELETE FROM images WHERE info_id = ?");
            $stmt->bind_param("i", $info_id);
            $stmt->execute();

            // Yeni şəkilləri əlavə et
            foreach ($info['images'] as $base64_image) {
                $image_data = base64_decode($base64_image);
                $image_name = uniqid() . ".jpg";
                $image_path = "uploads/" . $image_name;

                if (file_put_contents($image_path, $image_data)) {
                    $stmt = $conn->prepare("INSERT INTO images (uri, info_id) VALUES (?, ?)");
                    $stmt->bind_param("si", $image_path, $info_id);
                    $stmt->execute();
                }
            }
        }
    }
}

$conn->close();
echo json_encode(["message" => "Data updated successfully"]);
?>
