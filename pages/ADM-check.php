<?php
// (A) ACCESS CHECK
if (!isset($_SESS["user"]) || $_SESS["user"]["user_level"]!="A") {
  if (isset($_POST["ajax"])) { exit("E"); }
  else { $_CORE->redirect(); }
}

// (B) LOAD REQUESTED PAGE
$_PATH = explode("/", rtrim($_PATH, "/"));
array_shift($_PATH);
$_CORE->Route->load(
  count($_PATH)==0 ? "ADM-home.php" : "ADM-" . implode("-", $_PATH) . ".php"
);