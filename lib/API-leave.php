<?php
// (A) ACCESS CHECK
function check ($adm=true) {
  global $_SESS;
  global $_CORE;
  if (!isset($_SESS["user"]) || ($adm && $_SESS["user"]["user_level"]!="A")) {
    $_CORE->respond(0, "Please sign in first", null, null, 403);
  }
}

switch ($_REQ) {
  // (B) INVALID REQUEST
  default:
    $_CORE->respond(0, "Invalid request", null, null, 400);
    break;

  // (C) GET LEAVE TYPES
  case "getTypes":
    check();
    $_CORE->autoGETAPI("Leave", "getTypes");
    break;

  // (D) GET ENTITLED LEAVE
  case "getEntitled":
    check();
    $_CORE->autoGETAPI("Leave", "getEntitled");
    break;

  // (E) SAVE ENTITLED LEAVE
  case "saveEntitled":
    check();
    $_CORE->autoAPI("Leave", "saveEntitled");
    break;

  // (F) APPROVE/DENY LEAVE
  case "permit":
    check();
    $_CORE->autoAPI("Leave", "permit");
    break;

  // (G) APPLY LEAVE
  case "apply":
    check(false);
    $_CORE->autoAPI("Leave", "apply");
    break;

  // (H) CANCEL LEAVE
  case "cancel":
    check(false);
    $_CORE->autoAPI("Leave", "cancel");
    break;
}