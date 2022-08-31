<?php
// (A) ADMIN ONLY
if (!isset($_SESS["user"]) || $_SESS["user"]["user_level"]!="A") {
  $_CORE->respond(0, "Please sign in first", null, null, 403);
}

switch ($_REQ) {
  // (B) INVALID REQUEST
  default:
    $_CORE->respond(0, "Invalid request", null, null, 400);
    break;

  // (C) GET HOLIDAYS
  case "getAll":
    $_CORE->autoGETAPI("Holiday", "getAll");
    break;

  // (C) GET HOLIDAY
  case "get":
    $_CORE->autoGETAPI("Holiday", "get");
    break;

  // (E) SAVE HOLIDAY
  case "save":
    $_CORE->autoAPI("Holiday", "save");
    break;

  // (F) DELETE HOLIDAY
  case "del":
    $_CORE->autoAPI("Holiday", "del");
    break;
}