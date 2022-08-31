<?php
// (A) ACCESS CHECK
if (!isset($_SESS["user"])) {
  if (isset($_POST["ajax"])) { exit("E"); }
  else { $_CORE->redirect(); }
}

// (B) LOAD REQUESTED PAGE
$_PATH = explode("/", rtrim($_PATH, "/"));
array_shift($_PATH);
$_CORE->Route->load(
  count($_PATH)==0 ? "USR-leave.php" : "USR-" . implode("-", $_PATH) . ".php"
);