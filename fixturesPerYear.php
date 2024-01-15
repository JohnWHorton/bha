<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://www.britishhorseracing.com/feeds/v3/fixtures?fields=courseId,courseName,fixtureDate,fixtureType,fixtureSession,abandonedReasonCode,highlightTitle&&order=asc&resultsAvailable=true&year=2018',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic b3BlcmF0b3IxOnhYRGlzazAxNVh4'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

?>