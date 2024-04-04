<?php

require 'db-connect.php';

$_POST['user_id'] = 1;
$_POST['project_id'] = 1;

$sql = "SELECT * FROM projects WHERE user_id =" . $_POST['user_id'];
$data = $con->query($sql);
$data = $data->fetch_assoc();


echo json_encode($data);