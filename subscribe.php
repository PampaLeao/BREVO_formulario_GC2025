<?php
// Brevo API configuration
$apiKey = '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'; // Replace with your v3 API key
$listId = 3; // Replace with your Brevo list ID (integer)

// Get form data (assuming POST from HTML form)
$email = isset($_POST['email']) ? $_POST['email'] : '';
$firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
$lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';

if (empty($email)) {
    http_response_code(400);
    echo json_encode(['error' => 'Email is required']);
    exit;
}

// Prepare contact data
$data = [
    'email' => $email,
    'attributes' => [
        'FNAME' => $firstName,
        'LNAME' => $lastName,
    ],
    'listIds' => [$listId],
    'updateEnabled' => true, // Update if contact exists
];

// Make API request to Brevo
$ch = curl_init('https://api.brevo.com/v3/contacts');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'api-key: ' . $apiKey,
    'Content-Type: application/json',
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode(['message' => 'Contact added/updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to add contact', 'details' => json_decode($response)]);
}
?>