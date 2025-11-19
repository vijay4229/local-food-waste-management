<?php
// 1. Turn off Fatal Errors so we always get JSON
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
header('Content-Type: application/json');

$response = ['count' => 0, 'messages' => []];

try {
    // 2. Connect
    $connection = mysqli_connect("localhost", "root", "");
    if (!$connection) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }

    $db = mysqli_select_db($connection, 'demo');
    if (!$db) {
        throw new Exception("Database 'demo' not found.");
    }

    // 3. Get Count (Safe Query)
    $sql_count = "SELECT COUNT(*) as count FROM notifications WHERE status='unread'";
    $result_count = mysqli_query($connection, $sql_count);
    
    if ($result_count) {
        $row = mysqli_fetch_assoc($result_count);
        $response['count'] = $row['count'];
    }

    // 4. Get Messages (FIXED: Sorting by ID instead of date/created_at)
    // This avoids the "Unknown Column" error
    $sql_msgs = "SELECT * FROM notifications ORDER BY id DESC LIMIT 5";
    $result_msgs = mysqli_query($connection, $sql_msgs);

    if (!$result_msgs) {
        // If this fails, send the specific SQL error to the frontend
        throw new Exception("Query Failed: " . mysqli_error($connection));
    }

    while($row = mysqli_fetch_assoc($result_msgs)){
        // Make sure we have a date to show, even if the column is named differently
        $date_display = isset($row['date']) ? $row['date'] : (isset($row['created_at']) ? $row['created_at'] : 'Just now');
        
        $response['messages'][] = [
            'message' => $row['message'],
            'status' => $row['status'],
            'date' => $date_display
        ];
    }

} catch (Exception $e) {
    // Send error as a special message so you can see it in the dropdown
    $response['messages'][] = [
        'message' => "System Error: " . $e->getMessage(),
        'status' => 'read',
        'date' => ''
    ];
}

echo json_encode($response);
?>