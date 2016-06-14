<?php

  // get the token for your user
  $tokens = json_decode(file_get_contents('tokens.json'), FALSE);
  $url = $tokens[0];
  $token = $tokens[1];

  $c = 'cache/';
  if (!is_dir($c)) {
     mkdir($c, 0700);
  }

  print('try to connect with '. $url . ' and token ' . $token . '.' . "\n". 'Get data about all instruments in this project.'. "\n");



// get data from NDA
$ndar = json_decode(file_get_contents('https://ndar.nih.gov/api/datadictionary/v2/datastructure/image03?format=json'), TRUE);
file_put_contents($c . 'ndar_image03.json', json_encode($ndar, JSON_PRETTY_PRINT). "\n");
// get list of data elements
$ndar_keys = array();
foreach ($ndar['dataElements'] as $de) {
   $k = array_keys($de);
   foreach ($k as $key) {
     $ndar_keys[] = $key;
   }
}
print("Print keys from NDA: \n");
echo(json_encode(array_unique($ndar_keys), JSON_PRETTY_PRINT));

// print information about this project
$data = array(
    'token' => $token,
    'content' => 'project',
    'format' => 'json',
    'returnFormat' => 'json'
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));
$output = curl_exec($ch);
$j = json_decode($output, TRUE);
echo(json_encode($j, JSON_PRETTY_PRINT). "\n");
file_put_contents($c.'project_info.json', json_encode($j, JSON_PRETTY_PRINT));
curl_close($ch);



// get information about what instruments exist
$data = array(
    'token' => $token,
    'content' => 'instrument',
    'format' => 'json',
    'returnFormat' => 'json'
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));
$output = curl_exec($ch);
//print $output;
$j = json_decode($output, TRUE);
echo(json_encode($j, JSON_PRETTY_PRINT)."\n");
file_put_contents($c.'instruments.json', json_encode($j, JSON_PRETTY_PRINT));
curl_close($ch);

// for the list of instruments we can ask for the data dictionary
foreach( $j as $key => $value) {
$data = array(
    'token' => $token,
    'content' => 'metadata',
    'format' => 'json',
    'returnFormat' => 'json',
    'forms' => array($value['instrument_name'])
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));
$output = curl_exec($ch);
$inst = json_decode($output, TRUE);
file_put_contents($c.'instrument_'.$value['instrument_name'].'.json', json_encode($inst, JSON_PRETTY_PRINT));
echo("copy down: ".$value['instrument_name']."\n");
curl_close($ch);
}


?>