<?php
include("dbconnect.php");
session_start ();
$ham = intval($_SESSION['donate']);
$tam = 50;
$pam = $ham / $tam;
$_SESSION['noofvotes'] =$pam;
$yamcount=$_SESSION['no_of_votes'];

$curl = curl_init();
$reference = isset($_GET['reference']) ? $_GET['reference'] : '';
if(!$reference){
  die('No reference supplied');
}

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => [
    "accept: application/json",
    "authorization: Bearer sk_test_a9f8a0cae1a2315feb07f6a046d362283b16423f",
    "cache-control: no-cache"
  ],
));

$response = curl_exec($curl);
$err = curl_error($curl);

if($err){
    // there was an error contacting the Paystack API
  die('Curl returned error: ' . $err);
}

$tranx = json_decode($response);

if(!$tranx->status){
  // there was an error from the API
  die('API returned error: ' . $tranx->message);
}

if('success' == $tranx->data->status){
  // transaction was successful...
  // please check other things like whether you already gave value for this ref
  // if the email matches the customer who owns the product etc
  // Give value
  header ('location: paid.php');
  $ricecount = intval($yamcount) + intval($_SESSION['noofvotes']);
    
  $_SESSION['ricecount'] = $ricecount;
  
  
  
  $fishcount = intval($_SESSION['ricecount']);

  
  $yes = "UPDATE contestants SET no_of_votes='$fishcount' WHERE id=1";
  $query2 = mysqli_query($connect,$yes);
}
else{
    header ('location: fail.php');
}




