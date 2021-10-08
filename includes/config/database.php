<?php

function conectarDB(): mysqli
{


  $db = mysqli_connect('us-cdbr-east-04.cleardb.com', 'bc84c775df6787', '4b0c2b3b', 'heroku_2892a6ec8081b2e');
  if (!$db) {
    echo "<h3>No se ha podido conectar PHP - MySQL, verifique sus datos.</h3><hr><br>";
    exit;
  }
  return $db;
}
