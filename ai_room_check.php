<?php
session_start();
include("includes/db.php");
include("includes/config.php");

header('Content-Type: application/json');

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='admin'){
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$room_id = intval($_POST['room_id'] ?? 0);
$auto_action = isset($_POST['auto_action']) && $_POST['auto_action'] === '1';

$room = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT rooms.*, room_images.image_path FROM rooms
     LEFT JOIN room_images ON rooms.id = room_images.room_id
     WHERE rooms.id = $room_id"
));

if(!$room){
    echo json_encode(['error' => 'Room not found']);
    exit;
}

$hasImage  = $room['image_path'] ? 'Yes' : 'No';
$price     = (int)$room['price'];
$title     = $room['title'] ?? '';
$desc      = $room['description'] ?? 'Not provided';
$address   = $room['address'] ?? 'Not provided';
$ptype     = $room['property_type'] ?? 'Not specified';
$guests    = $room['guests'] ?? 'Not specified';
$wifi      = $room['wifi'] ?? 'no';
$ac        = $room['ac'] ?? 'no';
$geyser    = $room['geyser'] ?? 'no';

$prompt = "You are a real estate fraud detection expert specializing in student housing in India. Analyze this room listing and determine if it is REAL or FAKE/SUSPICIOUS.

Listing Details:
Title: $title
Description: $desc
Price: ₹$price/month
Address: $address
Property Type: $ptype
Max Guests: $guests
WiFi: $wifi | AC: $ac | Geyser: $geyser
Has Photo: $hasImage

Red flags (fake indicators): price below ₹1500/month for furnished room, extremely vague or missing description, no address, generic or suspicious title, missing photo.
Green flags (real indicators): price ₹3000–₹20000/month, specific address, detailed description, clear property type, photo uploaded.

Respond ONLY with valid JSON in this exact format with no extra text:
{\"verdict\":\"real\",\"confidence\":85,\"reason\":\"Brief 1-2 sentence explanation.\",\"flags\":[\"flag1\",\"flag2\"]}

verdict must be exactly the string \"real\" or \"fake\".";

$payload = [
    'model' => 'claude-sonnet-4-20250514',
    'max_tokens' => 300,
    'messages' => [['role' => 'user', 'content' => $prompt]]
];

$ch = curl_init('https://api.anthropic.com/v1/messages');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'x-api-key: ' . ANTHROPIC_API_KEY,
        'anthropic-version: 2023-06-01'
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30
]);

$response = curl_exec($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if($httpCode !== 200 || !isset($result['content'][0]['text'])){
    echo json_encode(['error' => 'AI service unavailable. Check your API key in includes/config.php']);
    exit;
}

$text     = $result['content'][0]['text'];
$text     = preg_replace('/```json|```/', '', $text);
$analysis = json_decode(trim($text), true);

if(!$analysis || !isset($analysis['verdict'])){
    echo json_encode(['error' => 'Could not parse AI response', 'raw' => $text]);
    exit;
}

if($auto_action){
    if($analysis['verdict'] === 'real'){
        mysqli_query($conn, "UPDATE rooms SET is_verified='yes' WHERE id=$room_id");
        $analysis['action_taken'] = 'approved';
    } else {
        mysqli_query($conn, "UPDATE rooms SET is_verified='no' WHERE id=$room_id");
        $analysis['action_taken'] = 'rejected';
    }
}

echo json_encode($analysis);
