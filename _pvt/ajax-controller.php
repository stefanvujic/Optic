<?php

require 'db-connect.php';

$_POST['user_id'] = 1;
$_POST['project_id'] = 1;

if (isset($_POST['user_id'])) { // authenticate user after login
    $sql = "SELECT * FROM projects WHERE user_id = ? AND project_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $_POST['user_id'], $_POST['project_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    echo json_encode($data);
    exit();
}

// save data
if (isset($_POST['saveData'])) {
    $sql = "UPDATE projects SET project_name = ?, project_data = ? WHERE user_id = ? AND project_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssss", $_POST['project_name'], $_POST['project_data'], $_POST['user_id'], $_POST['project_id']);
    $stmt->execute();
    exit();
}

// $sql = "SELECT * FROM projects WHERE user_id = ?";
// $stmt = $con->prepare($sql);
// $stmt->bind_param("s", $_POST['user_id']);
// $stmt->execute();
// $result = $stmt->get_result();
// $data = $result->fetch_assoc();

echo json_encode($data);