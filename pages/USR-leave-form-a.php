<?php
// (A) INIT - GET ENTITLED LEAVE + TYPES
$_CORE->load("Leave");
$_CORE->Settings->defineN("LEAVE_TYPES", true);
$leave = $_CORE->Leave->getEntitled($_SESS["user"]["user_id"], date("Y"));
$today = date("Y-m-d");

// (B) HTML ?>
<!-- (B1) HEADER -->
<h3 class="mb-3">APPLY LEAVE</h3>

<!-- (B2) REMAINING LEAVE -->
<h6 class="text-danger">LEAVE REMAINING</h6>
<table class="table table-striped mb-5">
  <thead><tr class="table-dark">
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

<!-- (B3) LEAVE APPLICATION FORM -->
<h6 class="text-danger">LEAVE TYPE &AMP; PERIOD</h6>
<form onsubmit="return leave.apply()">
  <div class="bg-white border p-3 mb-3">
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text mi">checklist</span>
      </div>
      <select class="form-select" id="apply_type"><?php
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

    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text mi">event</span>
      </div>
      <input type="date" class="form-control" id="apply_to" min="<?=$today?>" value="<?=$today?>" required>
    </div>
  </div>

  <input type="button" class="btn btn-danger" value="Back" onclick="cb.page(0)">
  <input type="button" class="btn btn-primary" value="Next" onclick="leave.applyDays()">
</form>