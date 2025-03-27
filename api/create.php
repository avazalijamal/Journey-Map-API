<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

require_once "auth.php";  // Auth yoxlamasını əlavə et
require_once "config.php"; // Database bağlantısını əlavə et

$data = json_decode(file_get_contents("php://input"), true);
if (!is_array($data)) {
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

$response = [];

foreach ($data as $item) {
    $lat = $item['lat'];
    $lng = $item['lng'];

    // Koordinatları əlavə et
    $stmt = $conn->prepare("INSERT INTO coordinates (lant, lng) VALUES (?, ?)");
    $stmt->bind_param("dd", $lat, $lng);
    if ($stmt->execute()) {
        $coord_id = $stmt->insert_id;

        foreach ($item['info'] as $info) {
            $title = $info['title'];
            $description = $info['description'];

            // Məlumatları əlavə et
            $stmt = $conn->prepare("INSERT INTO informations (title, description, cord_id) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $title, $description, $coord_id);
            if ($stmt->execute()) {
                $info_id = $stmt->insert_id;

                foreach ($info['images'] as $base64_image) {
                    $image_data = base64_decode($base64_image);
                    $image_name = uniqid() . ".jpg";
                    $image_path = "uploads/" . $image_name;

                    // Şəkli serverdə saxla
                    if (file_put_contents($image_path, $image_data)) {
                        $stmt = $conn->prepare("INSERT INTO images (uri, info_id) VALUES (?, ?)");
                        $stmt->bind_param("si", $image_path, $info_id);
                        $stmt->execute();
                    }
                }
            }
        }
        $response[] = ["lat" => $lat, "lng" => $lng, "status" => "success"];
    }
}

$conn->close();
echo json_encode(["message" => "Data inserted successfully", "data" => $response]);
?>
