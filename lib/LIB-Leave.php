<?php
class Leave extends Core {
  // (A) GET LEAVE TYPES
  function getTypes () {
    return $this->DB->fetchKV(
      "SELECT * FROM `leave_types`", null,
      "leave_type", "leave_name"
    );
  }

  // (B) GET ENTITLED LEAVE
  function getEntitled ($id, $year=null) {
    // (B1) "ALL LEAVE TYPES"
    if (!defined("LEAVE_TYPES")) {
      $this->core->load("Settings");
      $this->core->Settings->defineN("LEAVE_TYPES", true);
    }
    $leave = [];
    foreach (LEAVE_TYPES as $c=>$n) {
      $leave[$c] = ["leave_days" => 0];
    }

    // (B2) GET ENTITLED LEAVE
    $this->DB->query(
      "SELECT `leave_type`, `leave_days` FROM `leave_entitled` WHERE `user_id`=?", [$id]
    );
    while ($r = $this->DB->stmt->fetch()) {
      $leave[$r["leave_type"]]["leave_days"] = $r["leave_days"];
    }

    // (B3) LEAVE TAKEN & LEFT
    if ($year!=null) {
      foreach ($leave as $t=>$l) {
        $leave[$t]["leave_taken"] = 0;
        $leave[$t]["leave_left"] = $l["leave_days"];
      }
      $this->DB->query(
        "SELECT * FROM `leave_taken` 
         WHERE `user_id`=? AND `leave_from` BETWEEN ? AND ?
         AND `leave_type`!='D'",
        [$id, "$year-01-01", "$year-12-31"]
      );
      while ($r = $this->DB->stmt->fetch()) {
        $leave[$r["leave_type"]]["leave_taken"] += $r["leave_days"];
        $leave[$r["leave_type"]]["leave_left"] -= $r["leave_days"];
      }
    }

    // (B4) RETURN RESULTS
    return $leave;
  }

  // (C) GET ALL APPLIED LEAVE IN PERIOD
  function getAllTaken ($year, $id=null, $page=null) {
    // (C1) ONLY ADMIN CAN FETCH ALL
    global $_SESS;
    if ($id==null && $_SESS["user"]["user_level"]!="A") {
      $this->error = "No permission to access all records.";
      return false;
    }

    // (C2) ADMIN FETCH ALL
    if ($id==null) {
      // (C2-1) DATE RANGE DATA
      $data = ["$year-01-01", "$year-12-31"];

      // (C2-2) PAGINATION
      if ($page != null) {
        $pgn = $this->core->paginator(
          $this->DB->fetchCol(
            "SELECT COUNT(*) FROM `leave_taken` WHERE `leave_from` BETWEEN ? AND ?", $data
          ), $page
        );
      }

      // (C2-3) SQL
      $sql = "SELECT t.*, u.`user_name`, u.`user_email`
              FROM `leave_taken` t
              LEFT JOIN `users` u USING (`user_id`)
              WHERE `leave_from` BETWEEN ? AND ?
              ORDER BY `leave_from` DESC";
      if ($page != null) { $sql .= " LIMIT {$pgn["x"]}, {$pgn["y"]}"; }

      // (C2-3) RESULTS
      $leave = $this->DB->fetchAll($sql, $data, "leave_id");
      return $page != null
        ? ["data" => $leave, "page" => $pgn]
        : $leave ;
    }

    // (C3) FETCH OWN LEAVE RECORDS
    else {
      return $this->DB->fetchAll(
        "SELECT * FROM `leave_taken`
         WHERE `user_id`=? AND `leave_from` BETWEEN ? AND ?
         ORDER BY `leave_from` DESC",
        [$id, "$year-01-01", "$year-12-31"]
      );
    }
  }

  // (D) GET APPLIED LEAVE - "OVERLOADED FUNCTION"
  // $id only - acts as leave_id, will get entire leave taken + all days.
  // $id $from $to - $id acts as user id, get days $from $to.
  function getTaken ($id, $from=null, $to=null) {
    // (D1) GET LEAVE WITHIN DATE RANGE
    if ($from!=null && $to!=null) {
      return $this->DB->fetchKV(
        "SELECT * FROM `leave_taken_days` d
         LEFT JOIN `leave_taken` t USING (`leave_id`)
         WHERE t.`user_id`=? AND t.`leave_type`!='D'
         AND `leave_day` BETWEEN ? AND ?",
         [$id, $from, $to], "leave_day", "leave_half"
      );
    }

    // (D2) GET ENTIRE APPLIED LEAVE ENTRY
    else {
      $leave = $this->DB->fetch(
        "SELECT * FROM `leave_taken` WHERE `leave_id`=?", [$id]
      );
      $leave["days"] = $this->DB->fetchKV(
        "SELECT * FROM `leave_taken_days` WHERE `leave_id`=?",
        [$id], "leave_day", "leave_half"
      );
      return $leave;
    }
  }

  // (E) SAVE ENTITLED LEAVE
  function saveEntitled ($id, $entitled) {
    // (E1) DATA YOGA
    $data = [];
    foreach(json_decode($entitled, true) as $type=>$days) {
      $data[] = $id; $data[] = $type; $data[] = $days;
    }

    // (E2) SAVE ENTITLED LEAVE DATA
    $this->DB->replace("leave_entitled",
      ["user_id", "leave_type", "leave_days"], $data
    );
    return true;
  }

  // (F) APPLY LEAVE
  function apply ($type, $from, $to, $days) {
    // (F1) MUST BE SIGNED IN
    global $_SESS;
    if (!isset($_SESS["user"])) {
      $this->error = "Please sign in first";
      return false;
    }

    // (F2) DAYS
    $days = json_decode($days, true);
    $total = 0;
    foreach ($days as $d=>$h) {
      $total += $h == 1 ? 1 : 0.5;
    }

    // (F3) CHECK DAYS LEFT
    $entitled = $this->getEntitled($_SESS["user"]["user_id"], substr($from, 0, 4));
    if ($entitled[$type]["leave_left"] < $total) {
      $this->error = "You only have ".$entitled[$type]["leave_left"]." days of leave left!";
      return false;
    }
    unset($entitled);

    // (F4) DOUBLE CHECK - HOLIDAYS
    $this->core->load("Holiday");
    $holiday = $this->core->Holiday->getAll($from, $to);
    if (is_array($holiday)) { foreach ($holiday as $d=>$h) {
      if ($h["holiday_half"]===0 && isset($days[$d])) {
        $this->error = "$d is a holiday!";
        return false;
      }
      if ($h["holiday_half"]===1 && isset($days[$d]) && $days[$d]==1) {
        $this->error = "$d is a half-day holiday!";
        return false;
      }
    }}
    unset($holiday);

    // (F5) DOUBLE CHECK - WEEKENDS & CLASHES
    $taken = $this->getTaken($_SESS["user"]["user_id"], $from, $to);
    foreach ($days as $d=>$h) {
      // (F5-1) WEEKEND
      $day = date("N", strtotime($d));
      if ($day==6 || $day==7) {
        $this->error = "$d is a weekend!";
        return false;
      }

      // (F5-2) CLASH WITH ANOTHER APPLIED LEAVE
      if (isset($taken[$d])) {
        $this->error = "Already applied leave on $d!";
        return false;
      }
    }
    unset($taken);

    // (F6) "MAIN LEAVE ENTRY"
    $this->DB->start();
    $this->DB->insert("leave_taken",
      ["user_id", "leave_type", "leave_from", "leave_to", "leave_days"],
      [$_SESS["user"]["user_id"], $type, $from, $to, $total]
    );

    // (F7) LEAVE DAYS
    $leaveID = $this->DB->lastID;
    $data = [];
    foreach ($days as $d=>$h) {
      $data[] = $leaveID; $data[] = $d; $data[] = $h;
    }
    $this->DB->insert("leave_taken_days",
      ["leave_id", "leave_day", "leave_half"], $data
    );

    // (F8) DONE
    $this->DB->end();
    return true;
  }

  // (G) CANCEL LEAVE
  function cancel ($id) {
    // (G1) CHECK PERMISSION
    global $_SESS;
    $leave = $this->DB->fetch(
      "SELECT * FROM `leave_taken` WHERE `leave_id`=?",
      [$id]
    );
    $valid = is_array($leave);
    if ($valid) { $valid = $leave["leave_status"]=="P"; }
    if ($valid) {
      $valid = $_SESS["user"]["user_level"]=="A" || $leave["user_id"]==$_SESS["user"]["user_id"];
    }
    if (!$valid) {
      $this->error = "Invalid request";
      return false;
    }

    // (G2) PROCEED
    $this->DB->start();
    $this->DB->delete("leave_taken", "`leave_id`=?", [$id]);
    $this->DB->delete("leave_taken_days", "`leave_id`=?", [$id]);
    $this->DB->end();
    return true;
  }

  // (H) APPROVE/DENY
  function permit ($id, $approve) {
    $this->DB->update("leave_taken",
      ["leave_status"], "`leave_id`=?", [($approve?"A":"D"), $id]
    );
    return true;
  }
}