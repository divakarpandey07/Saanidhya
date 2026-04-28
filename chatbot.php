<?php
session_start();
include("includes/db.php");
include("includes/config.php");

header('Content-Type: application/json');

$message = trim($_POST['message'] ?? '');

if(empty($message)){
    echo json_encode(['reply' => 'Please type a message first.']);
    exit;
}

if(!defined('ANTHROPIC_API_KEY') || ANTHROPIC_API_KEY === 'your-anthropic-api-key-here' || ANTHROPIC_API_KEY === ''){
    echo json_encode(['reply' => "Chatbot is not yet configured. Please set your ANTHROPIC_API_KEY in includes/config.php — get a free key from console.anthropic.com."]);
    exit;
}

if(!function_exists('curl_init')){
    echo json_encode(['reply' => "cURL extension is not enabled on this server. Please enable it in php.ini."]);
    exit;
}

if(!isset($_SESSION['chat_history'])){
    $_SESSION['chat_history'] = [];
}

$_SESSION['chat_history'][] = ['role' => 'user', 'content' => $message];

if(count($_SESSION['chat_history']) > 20){
    $_SESSION['chat_history'] = array_slice($_SESSION['chat_history'], -20);
}

$system_prompt = "You are the Saanidhya AI Assistant — a friendly, helpful chatbot for a student housing platform in India. Saanidhya helps students find verified PGs, hostels, and flats near colleges. Help users with: finding rooms, pricing info (₹3,000–₹15,000/month typically), booking process (search → view → request → owner approves), amenities (WiFi, AC, Geyser), cities covered (Jalandhar, Phagwara and growing), account creation, and general student housing questions. Be warm, concise, reply in 2-4 sentences. If asked in Hindi/Hinglish, reply in the same language.";

$payload = [
    'model'      => 'claude-sonnet-4-20250514',
    'max_tokens' => 512,
    'system'     => $system_prompt,
    'messages'   => $_SESSION['chat_history']
];

$ch = curl_init('https://api.anthropic.com/v1/messages');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'x-api-key: ' . ANTHROPIC_API_KEY,
        'anthropic-version: 2023-06-01'
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response  = curl_exec($ch);
$curlError = curl_error($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if($curlError){
    echo json_encode(['reply' => "Network error: $curlError — Make sure your server can reach api.anthropic.com."]);
    exit;
}

$result = json_decode($response, true);

if($httpCode === 401){
    echo json_encode(['reply' => "Invalid API key. Please check your ANTHROPIC_API_KEY in includes/config.php."]);
    exit;
}

if($httpCode === 200 && isset($result['content'][0]['text'])){
    $reply = $result['content'][0]['text'];
    $_SESSION['chat_history'][] = ['role' => 'assistant', 'content' => $reply];
    echo json_encode(['reply' => $reply]);
} else {
    $errMsg = $result['error']['message'] ?? "HTTP $httpCode — check server logs.";
    echo json_encode(['reply' => "AI service error: $errMsg"]);
}
