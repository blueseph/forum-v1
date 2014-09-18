<?php

define("HOST", "localhost");
define("USER", "omitted");
define("PASSWORD", "omitted");
define("DATABASE", "entropy");

$conn = new mysqli(HOST, USER, PASSWORD, DATABASE);

if ($conn->connect_error) {
  trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
}

?>