<?php

require 'db-connect.php';

$_POST['user_id'] = 1;
$_POST['project_id'] = 1;

$sql = "SELECT * FROM projects WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $_POST['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode($data);