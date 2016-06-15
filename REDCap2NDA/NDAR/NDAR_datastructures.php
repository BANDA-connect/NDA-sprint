<?php

  $entry = "";
  if (isset($_GET['entry'])) {
    $entry = $_GET['entry'];
  }
  $d = "cache/";
  if (!is_dir($d)) {
     mkdir($d, 0700);
  }

  if ($entry == "") {
     #    $xml = simplexml_load_string(file_get_contents('https://ndar.nih.gov/api/datadictionary/v2/datastructure'));
     #    $json = json_encode($xml);
     #    echo($json);
     $val = file_get_contents('https://ndar.nih.gov/api/datadictionary/v2/datastructure');
     echo($val);
     # lets cache the searches locally
     file_put_contents($d . 'datastructure.json', $val);

  } else {
    $val = file_get_contents('https://ndar.nih.gov/api/datadictionary/v2/datastructure/'.$entry);
    echo ($val);
    file_put_contents($d.$entry.".json", $val);
  }
?>