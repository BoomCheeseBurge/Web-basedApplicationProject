<?php

$pw = "brandon";

$hash = sha1($pw);

 echo "<script>console.log('Hashed password: " . $hash . "' );</script>";

?>