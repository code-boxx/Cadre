<?php
// (A) INIT - GET ENTITLED LEAVE + TYPES
$_CORE->load("Leave");
$_CORE->Settings->defineN("LEAVE_TYPES", true);
$leave = $_CORE->Leave->getEntitled($_SESS["user"]["user_id"], date("Y"));
$today = date("Y-m-d");

// (B) HTML ?>
<!-- (B1) REMAINING LEAVE -->
<h3 class="mb-3">LEAVE REMAINING</h3>
<table class="table table-dark table-striped mb-5">
  <thead><tr>
    <th>Type</th>
    <th>Entitled</th>
    <th>Taken</th>
    <th>Remain</th>
  </tr></thead>
  <tbody>
    <?php foreach ($leave as $type=>$l) { ?>
    <tr>
      <th><?=LEAVE_TYPES[$type]?></th>
      <td><?=$l["leave_days"]?></td>
      <td><?=$l["leave_taken"]?></td>
      <td><?=$l["leave_left"]?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>

<!-- (B2) LEAVE APPLICATION FORM -->
<h3 class="mb-3">APPLY LEAVE</h3>
<form class="bg-white border p-3" onsubmit="return leave.apply()">
  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text mi">checklist</span>
    </div>
    <select class="form-control" id="apply_type"><?php
      foreach (LEAVE_TYPES as $c=>$n) {
        echo "<option value='$c'>$n</option>";
      }
    ?></select>
  </div>

  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text mi">today</span>
    </div>
    <input type="date" class="form-control" id="apply_from" min="<?=$today?>" value="<?=$today?>" required>
  </div>

  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text mi">event</span>
    </div>
    <input type="date" class="form-control" id="apply_to" min="<?=$today?>" value="<?=$today?>" required>
  </div>

  <input type="button" class="btn btn-danger" value="Back" onclick="cb.page(0)">
  <input type="button" class="btn btn-primary" value="Next" onclick="leave.applyDays()">
</form>