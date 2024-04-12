<?php

require 'db-connect.php';

function checkToken($con, $token) {
    $sql = "SELECT * FROM users WHERE SHA2(id, 256) = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    return $data;
}

$request = $_POST['request'];

switch ($request) {

    case 'login':
        $data['token'] = hash('sha256', $data['id']);
        $sql = "SELECT * FROM users WHERE username = ? AND password = ? AND token = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sss", $_POST['username'], $_POST['password'], $data['token']);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        echo json_encode($data);
        exit();
    break;

    case 'register':

        $data['token'] = hash('sha256', $data['id']);
        $sql = "INSERT INTO users (username, email, name, password, token) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssss", $_POST['username'], $_POST['email'], $_POST['name'], $_POST['password'], $data['token']);
        $is_registered = $stmt->execute();
        
        $sql = "SELECT * FROM users WHERE username = ? AND password = ? AND token = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sss", $_POST['username'], $_POST['password'], $data['token']);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        echo json_encode($data);
        exit();
    break;

    case 'getProjects':
        $is_correct_token = checkToken($con, $_POST['token']);
        if($is_correct_token) {
            $sql = "SELECT * FROM projects WHERE user_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $_POST['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
        }else {
            $data = ['error' => 'Invalid Token'];
        }

        echo json_encode($data);
        exit();
    break;

    case 'getProject':
        $is_correct_token = checkToken($con, $_POST['token']);
        if($is_correct_token) {
            $sql = "SELECT * FROM projects WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $_POST['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
        }else {
            $data = ['error' => 'Invalid Token'];
        }

        echo json_encode($data);
        exit();
    break;

    case 'updateProject':
        $is_correct_token = checkToken($con, $_POST['token']);
        if($is_correct_token) {
            $sql = "UPDATE projects SET name = ?, description = ?, status = ? WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssss", $_POST['name'], $_POST['description'], $_POST['status'], $_POST['id']);
            $stmt->execute();
            $data = ['success' => 'Project Updated'];
        }else {
            $data = ['error' => 'Invalid Token'];
        }

        echo json_encode($data);
        exit();
    break;

    case 'deleteProject':
        $is_correct_token = checkToken($con, $_POST['token']);
        if($is_correct_token) {
            $sql = "DELETE FROM projects WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $_POST['id']);
            $stmt->execute();
            $data = ['success' => 'Project Deleted'];
        }else {
            $data = ['error' => 'Invalid Token'];
        }

        echo json_encode($data);
        exit();
    break;

}

// $_POST['user_id'] = 1;
// if (isset($_POST['getProjects'])) {
//     $sql = "SELECT * FROM projects WHERE user_id = ?";
//     $stmt = $con->prepare($sql);
//     $stmt->bind_param("s", $_POST['user_id']);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = $result->fetch_all(MYSQLI_ASSOC);

//     echo json_encode($data);
//     exit();
// }
