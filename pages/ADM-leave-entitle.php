<?php
// (A) GET USER + ENTITLED LEAVE
$_CORE->Settings->defineN("LEAVE_TYPES", true);
$_CORE->load("Users");
$_CORE->load("Leave");
$user = $_CORE->autoCall("Users", "get");
$leave = $_CORE->autoCall("Leave", "getEntitled");

// (B) ENTITLED LEAVE FORM ?>
<h3 class="mb-3">ENTITLED LEAVE FOR <?=strtoupper($user["user_name"])?></h3>
<form id="leave-form" onsubmit="return leave.save()">
  <input type="hidden" id="user_id" value="<?=isset($user)?$user["user_id"]:""?>">

  <div class="bg-white border p-4 mb-3">
    <?php foreach ($leave as $t=>$l) { ?>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text"><?=LEAVE_TYPES[$t]?></span>
      </div>
      <input type="hidden" class="leave-type" value="<?=$t?>">
      <input type="number" class="form-control leave-days" required min="0" step="0.5" value="<?=$l["leave_days"]?$l["leave_days"]:0?>">
    </div>
    <?php } ?>
  </div>

  <input type="button" class="col btn btn-danger btn-lg" value="Back" onclick="cb.page(0)">
  <input type="submit" class="col btn btn-primary btn-lg" value="Save">
</form>