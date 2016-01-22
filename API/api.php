<?php
header('Access-Control-Allow-Origin: *');
//
// API Demo
//
// This script provides a RESTful API interface for a web application
//
// Input:
//
// $_GET['format'] = [ json | html | xml ]
// $_GET['m'] = []
//
// Output: A formatted HTTP response
//
// Author: Mark Roland
//
// History:
// 11/13/2012 - Created
//
// Adapted by:
// Steven Ophalvens

// --- Step 0 : connect to db
//require_once "dbcon.php";

// een verbinding leggen met de databank
$servername = "localhost";
$username = "jengar1q_mobiel";// dangerous
$password = "thib4life";// dangerous
$dbname = "jengar1q_mobiel";// standaard test databank

// Define API response codes and their related HTTP response
$api_response_code = array(0 => array('HTTP Response' => 400, 'Message' => 'Unknown Error'), 1 => array('HTTP Response' => 200, 'Message' => 'Success'), 2 => array('HTTP Response' => 403, 'Message' => 'HTTPS Required'), 3 => array('HTTP Response' => 401, 'Message' => 'Authentication Required'), 4 => array('HTTP Response' => 401, 'Message' => 'Authentication Failed'), 5 => array('HTTP Response' => 404, 'Message' => 'Invalid Request'), 6 => array('HTTP Response' => 400, 'Message' => 'Invalid Response Format'), 7 => array('HTTP Response' => 400, 'Message' => 'DB problems'));

// Set default HTTP response of 'ok' or NOK in this case
$response['code'] = 0;
$response['status'] = 404;
$response['data'] = NULL;


$conn = new mysqli($servername, $username, $password, $dbname) or die(mysqli_connect_error());
// de or die() kan vervangen worden door de juiste aanroep van deliver_response();
// dit wordt later gedaan toch nog gedaan op de juiste plaatsen, dus we raken niet verder dan hier.
// Dit treedt normaal enkel op wanneer dit niet nog niet juist is ingesteld.

//require_once "functies.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    $response['code'] = 7;
    $response['status'] = 404;
}


// --- Step 1: Initialize variables and functions

///**
// * Deliver HTTP Response
// * @param string $format The desired HTTP response content type: [json, html, xml]
// * @param string $api_response The desired HTTP response data
// * @return void
// **/
    function deliver_response($format, $api_response)
    {

        // Define HTTP responses
        $http_response_code = array(200 => 'OK', 400 => 'Bad Request', 401 => 'Unauthorized', 403 => 'Forbidden', 404 => 'Not Found');

        // Set HTTP Response
        header('HTTP/1.1 ' . $api_response['status'] . ' ' . $http_response_code[$api_response['status']]);
        header('Content-Type: application/json; charset=utf-8');

        $json_response = json_encode($api_response);
        echo $json_response;
        exit;

    }





    switch ($_GET['m'])
    {
            case 'login':
            login($conn,$api_response_code);

            break;
            case 'getGegevens':
            getGegevens($conn,$api_response_code);
            break;
            case 'setMeterstand':
            setMeterstand($conn,$api_response_code);
            break;
            case 'NieuweGebruiker':
            maakUser($conn,$api_response_code);
            break;

            default:
                # code...
            break;
    }



function maakUser($conn,$api_response_code)
{
    $response['code'] = 1;
    $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
    $hashwachtwoord = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
    $lQuery = "insert into Users(Naam, Achternaam, Email, Wachtwoord) values ('" . $_POST['voornaam'] . "','" . $_POST['achternaam'] . "','" . $_POST['email'] . "','" . $hashwachtwoord . "')";
    $conn->query($lQuery);
    deliver_response($_POST['format'], $response);

}


function setMeterstand($conn,$api_response_code)
{
    $response['code'] = 0;
    $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
    $lQuery = "insert into Gegevens(Meterstand, datum) values ('" . $_POST['gegeven'] . "','" . $_POST['datum'] . "')";
    $conn->query($lQuery);
    deliver_response($_POST['format'], $response);
}


function getGegevens($conn,$api_response_code)
{

    $response['code'] = 0;
    $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
    $lQuery = "select * FROM Gegevens";
    $result = $conn->query($lQuery);
    $rows = array();
    if (!$result) {
        $response['data'] = "db error";
    } else {

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $response['data'] = $rows;

    }
    deliver_response($_POST['format'], $response);
}


function login($conn,$api_response_code)
{
    $lQuery = "select Wachtwoord FROM Users where Email = '" . $_POST['naam'] . "'";
    $ww = $_POST['password'];

    $result = $conn->query($lQuery);
    $rows = array();
    if (!$result) {
        $response['code'] = 4;
        $response['data'] = "db error";
    } else {
        if (password_verify($ww, $result)) {

            if (count($rows) > 0) {

                $response['code'] = 1;
                $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
                $response['data'] = $result;
            } else {
                $response['code'] = "4";
                $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
                $response['data'] = $api_response_code[$response['code']]['Message'];
            }

        }
    }
    deliver_response($_POST['format'], $response);

}

mysqli_close($conn);


?>
