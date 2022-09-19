<?php
// (A) GET LEAVE DAYS
$_CORE->Settings->defineN(["LEAVE_DAYS", "LEAVE_STATUS", "LEAVE_TYPES"], true);
$leave = $_CORE->autoCall("Leave", "getTaken");

// (B) HTML ?>
<table class="table table-striped">
  <tr>
    <th>Period</th>
    <td><?=$leave["leave_from"]?> to <?=$leave["leave_to"]?> </td>
  </tr>
  <tr>
    <th>Type</th>
    <td><?=LEAVE_TYPES[$leave["leave_type"]]?></td>
  </tr>
  <tr>
    <th>Days</th>
    <td><?=$leave["leave_days"]?></td>
  </tr>
  <tr>
    <th>Status</th>
    <td><?=LEAVE_STATUS[$leave["leave_status"]]?></td>
  </tr>
</table>

<ul class="list-group my-4"><?php
  for ($unix=strtotime($leave["leave_from"]); $unix<=strtotime($leave["leave_to"]); $unix+=86400) {
    $day = date("Y-m-d", $unix);
    printf("<li class='list-group-item'>%s (%s)</li>",
      $day, !isset($leave["days"][$day]) ? "NA" : LEAVE_DAYS[$leave["days"][$day]]
    );
  }
?></ul>
<button class="btn btn-danger" onclick="cb.page(0)">
  Back
</button>