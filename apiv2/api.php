<?php
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Pragma: no-cache');
header("Access-Control-Allow-Origin: *");//this allows cors
header("Content-type: application/json; charset=utf-8");

include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        handleGet($pdo);
        break;
    case 'POST':
        handlePost($pdo, $input);
        break;
    case 'PUT':
        handlePut($pdo, $input);
        break;
    case 'DELETE':
        handleDelete($pdo, $input);
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function handleGet($pdo) {
    $sql = "SELECT * FROM users";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode($result);
}

function handlePost($pdo, $input) {
    $sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => $input['name'], 'email' => $input['email']]);
    echo json_encode(['message' => 'User created successfully']);
}

function handlePut($pdo, $input) {
    $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => $input['name'], 'email' => $input['email'], 'id' => $input['id']]);
    echo json_encode(['message' => 'User updated successfully']);
}

function handleDelete($pdo, $input) {
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $input['id']]);
    echo json_encode(['message' => 'User deleted successfully']);
}

/*
 * returnJsonHttpResponse
 * @param $success: Boolean
 * @param $data: Object or Array
 */
function returnJsonHttpResponse($httpCode, $data)
{
    // remove any string that could create an invalid JSON 
    // such as PHP Notice, Warning, logs...
    ob_start();
    ob_clean();

    // this will clean up any previously added headers, to start clean
    header_remove(); 

    // Set the content type to JSON and charset 
    // (charset can be set to something else)
    // add any other header you may need, gzip, auth...
    header("Content-type: application/json; charset=utf-8");

    // Set your HTTP response code, refer to HTTP documentation
    http_response_code($httpCode);
    
    
    // encode your PHP Object or Array into a JSON string.
    // stdClass or array
    echo json_encode($data);

    // making sure nothing is added
    exit();
}
?>
