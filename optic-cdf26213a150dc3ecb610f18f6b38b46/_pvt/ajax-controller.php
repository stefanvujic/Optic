<?php

require 'db-connect.php';
// require_once "vendor/autoload.";ff

$request = $_POST['request'];

// $request = 'register';
// $_POST['username'] = 'ste2444sdsd34t3f5an';
// $_POST['password'] = 'pass4t4sdssdada3tword';
// $_POST['email'] = 'stefa534tssdds232adda34tn@example.com';
// $_POST['name'] = 'Stefan sdsdsds';


switch ($request) {

    case 'login':
        $data['token'] = hash('sha256', $data['id']);
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $_POST['username']);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        if($data) {
            if(password_verify($_POST['password'], $data['password'])) {
                $data['token'] = hash('sha256', $data['id'] . time());
                $sql = "UPDATE users SET token = ? WHERE username = ?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("ss", $data['token'], $data['username']);
                $stmt->execute();
            }else {
                $data = [];
                $data['password-error'] = 1;
            }
        }else {
            $data = [];
            $data['user-exists'] = 0;
        }

        echo json_encode($data);
        exit();
    break;

    case 'register':
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $_POST['username'], $_POST['email']);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if($data['ID']) {
            $data = [];
            $data['user-exists'] = 1;
            echo json_encode($data);
            exit();
        }

        $data['token'] = hash('sha256', $data['id'] . time());
        $sql = "INSERT INTO users (username, email, password, name, token) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt->bind_param("sssss", $_POST['username'], $_POST['email'], $hashed_password, $_POST['name'], $data['token']);

        $is_registered = $stmt->execute();

        if($is_registered) {
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $_POST['username']);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
        }else {
            $data = [];
            $data['error'] = 1;
        }

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

$_POST['user_id'] = 1;
if (isset($_POST['getProjects'])) {
    $sql = "SELECT * FROM projects WHERE user_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $_POST['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($data);
    exit();
}


function checkToken($con, $token) {
    $sql = "SELECT * FROM users WHERE SHA2(id, 256) = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    return $data;
}


$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $_POST['username']);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if($data) {
    $data = [];
    $data['user-exists'] = 1;
    echo json_encode($data);
    exit();
}

// create eath wallet and store it in the database
// create a function that will check if the user has a wallet

// use Sop\CryptoTypes\Asymmetric\EC\ECPublicKey;
// use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
// use Sop\CryptoEncoding\PEM;
// use kornrunner\Keccak;

// $config = [
//     'private_key_type' => OPENSSL_KEYTYPE_EC,
//     'curve_name' => 'secp256k1'
// ];

// $res = openssl_pkey_new($config);

// if (!$res) {
//     echo 'ERROR: Fail to generate private key. -> ' . openssl_error_string();
//     exit;
// }

// openssl_pkey_export($res, $priv_key);

// $key_detail = openssl_pkey_get_details($res);
// $pub_key = $key_detail["key"];

// $priv_pem = PEM::fromString($priv_key);

// $ec_priv_key = ECPrivateKey::fromPEM($priv_pem);

// $ec_priv_seq = $ec_priv_key->toASN1();

// $priv_key_hex = bin2hex($ec_priv_seq->at(1)->asOctetString()->string());
// $priv_key_len = strlen($priv_key_hex) / 2;
// $pub_key_hex = bin2hex($ec_priv_seq->at(3)->asTagged()->asExplicit()->asBitString()->string());
// $pub_key_len = strlen($pub_key_hex) / 2;

// $pub_key_hex_2 = substr($pub_key_hex, 2);
// $pub_key_len_2 = strlen($pub_key_hex_2) / 2;

// $hash = Keccak::hash(hex2bin($pub_key_hex_2), 256);

// $wallet_address = '0x' . substr($hash, -40);
// $wallet_private_key = '0x' . $priv_key_hex;

// echo "\r\n   SAVE BUT DO NOT SHARE THIS (Private Key): " . $wallet_private_key;
// echo "\r\n   Address: " . $wallet_address . " \n";dd