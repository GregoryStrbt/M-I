<?php

$servername = "localhost";
$username = "jengar1q_mobiel";// dangerous
$password = "thib4life";// dangerous
$dbname = "jengar1q_mobiel";


$conn = mysqli_connect($servername, $username, $password, $dbname);


 
//  //json response array
// $response = array("error" => FALSE);
// $response["error"] = FALSE;
//         $response["ID"] = "1";
//         $response["user"]["Naam"] = "test";
//         $response["user"]["Email"] = "test";
//         echo json_encode($response);
 
if (isset($_POST['email']) && isset($_POST['password'])) {
 
    // receiving the post params
    $email = $_POST['email'];
    $password = $_POST['password'];
 
    // get the user by email and password
    $user = getUserByEmailAndPassword($email, $password);
 
    if ($user != false) {
        // use is found
        $response["ID"] = $user["ID"];
        $response["user"]["Naam"] = $user["Naam"];
        $response["user"]["Achternaam"] = $user["Achternaam"];
        $response["user"]["Email"] = $user["Email"];
        $response["user"]["Wachtwoord"] = $user["Wachtwoord"];
        echo json_encode($response);
    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = "Login credentials are wrong. Please try again!";
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email or password is missing!";
    echo json_encode($response);
}

function getUserByEmailAndPassword($email, $password) {
 
        $stmt = $this->conn->prepare("SELECT * FROM Users WHERE Email = ?");
 
        $stmt->bind_param("s", $email);
 
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }
?>