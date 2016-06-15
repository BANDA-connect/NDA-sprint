<?php

  $entry = "";
  if (isset($_GET['entry'])) {
    $entry = $_GET['entry'];
  }

  if ($entry == "") {
#    $xml = simplexml_load_string(file_get_contents('https://ndar.nih.gov/api/datadictionary/v2/datastructure'));
#    $json = json_encode($xml);
#    echo($json);
     echo (file_get_contents('https://ndar.nih.gov/api/datadictionary/v2/datastructure'));
  } else {
    echo (file_get_contents('https://ndar.nih.gov/api/datadictionary/v2/datastructure/'.$entry));
  }
?>