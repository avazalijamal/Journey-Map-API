# Journey Map API Documentation

## Overview

Journey Map API, istifadəçilərin müəyyən coğrafi mövqeləri qeyd etməsinə və onlar haqqında məlumat saxlamasına imkan verən RESTful API-dir. Bu sənəd Postman kolleksiyası vasitəsilə API-nin necə istifadə olunacağını izah edir.

## 📌 API Endpoints

API aşağıdakı əsas CRUD əməliyyatlarını dəstəkləyir:

-   **Create** (POST `/create.php`)
    
-   **Read** (GET `/read.php?lat={lat}&lng={lng}`)
    
-   **Update** (PUT `/update.php`)
    
-   **Delete** (DELETE `/delete.php`)
    

## 🛠️ Quraşdırma və İstifadə

### 1️⃣ Postman Kolleksiyasını İdxal Edin

1.  **Postman** proqramını açın.
    
2.  **Import** düyməsinə klikləyin.
    
3.  JSON faylını yükləyin və kolleksiyanı əlavə edin.
    

### 2️⃣ Əsas Parametrlər

Postman kolleksiyası aşağıdakı dəyişənlərdən istifadə edir:

-   **`base_url`** – API əsas URL (standart: `https://journeymap.hewart.az/api`)
    
-   **`api_key`** – API-yə giriş açarı
    
-   **`image`** – Şəkil məlumatı üçün baza64 kodlaşdırılmış dəyər
    

## 🔹 API İstifadəsi

### 1️⃣ **Yeni Məlumat Yaratmaq** (Create)

-   **Endpoint:** `POST {{base_url}}/create.php`
    
-   **Body:**
    

```json
[
    {
        "lat": 41.123456,
        "lng": 45.987654,
        "info": [
            {
                "title": "Test Title",
                "description": "Test Description",
                "images": [
                    "{{image}}"
                ]
            }
        ]
    }
]

```

-   **Headers:** `api-key: {{api_key}}`
    
-   **Response:** `200 OK`
    

### 2️⃣ **Məlumat Oxumaq** (Read)

-   **Endpoint:** `GET {{base_url}}/read.php?lat=41.123456&lng=45.987654`
    
-   **Headers:** `api-key: {{api_key}}`
    
-   **Response:** JSON formatında saxlanılmış məlumat
    

### 3️⃣ **Məlumat Yeniləmək** (Update)

-   **Endpoint:** `PUT {{base_url}}/update.php`
    
-   **Body:**
    

```json
{
    "id": 6,
    "lat": 49.123456,
    "lng": 50.987654,
    "info": [
        {
            "id": 6,
            "title": "Updated Title",
            "description": "Updated Description",
            "images": [
                "{{image}}"
            ]
        }
    ]
}

```

-   **Headers:** `api-key: {{api_key}}`
    
-   **Response:** `200 OK`
    

### 4️⃣ **Məlumat Silmək** (Delete)

-   **Endpoint:** `DELETE {{base_url}}/delete.php`
    
-   **Body:**
    

```json
{
    "id": 1
}

```

-   **Headers:** `api-key: {{api_key}}`
    
-   **Response:** `200 OK`
    

## ✅ API Testləri

Postman kolleksiyasına testlər əlavə olunub. **Test nəticələri** Postman-ın "Test Results" sekmesinde görünə bilər.

## 📢 Əlavə Qeydlər

-   API istifadə etmək üçün `api-key` daxil edilməlidir.
    
-   `base_url` dəyişəni fərdi serverinizə uyğun tənzimlənə bilər.
    

🔗 Daha ətraflı məlumat üçün [Postman Kolleksiyası](./api/postman/Journey%20Map%20API.postman_collection.json) linkinə daxil olun.