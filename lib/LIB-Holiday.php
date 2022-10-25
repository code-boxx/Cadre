<?php
class Holiday extends Core {
  // (A) GET HOLIDAYS
  function getAll ($from, $to) {
    return $this->DB->fetchAll(
      "SELECT * FROM `holidays` WHERE `holiday_date` BETWEEN ? AND ? ORDER BY `holiday_date` ASC",
      [$from, $to], "holiday_date"
    );
  }

  // (B) GET HOLIDAY
  function get ($id) {
    return $this->DB->fetch(
      "SELECT * FROM `holidays` WHERE `holiday_id`=?", [$id]
    );
  }

  // (C) SAVE HOLIDAY
  function save ($name, $date, $half, $id=null) {
    $fields = ["holiday_name", "holiday_date", "holiday_half"];
    $data = [$name, $date, $half];
    if ($id == null) {
      $this->DB->insert("holidays", $fields, $data);
    } else {
      $data[] = $id;
      $this->DB->update("holidays", $fields, "`holiday_id`=?", $data);
    }
    return true;
  }

  // (D) DELETE HOLIDAY
  function del ($id) {
    $this->DB->delete("holidays", "`holiday_id`=?", [$id]);
    return true;
  }
}