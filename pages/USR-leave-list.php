<?php
// (A) GET LEAVE ENTRIES
$_CORE->Settings->defineN(["LEAVE_STATUS", "LEAVE_TYPES"], true);
$_CORE->load("Leave");
$leave = $_CORE->Leave->getAllTaken($_POST["year"], $_SESS["user"]["user_id"]);

// (B) HTML LIST
if (is_array($leave)) { foreach ($leave as $l) { ?>
<div class="d-flex align-items-center border p-2">
  <div class="flex-grow-1">
    <div class="fw-bold">
      <?=$l["leave_from"]?> to <?=$l["leave_to"]?> 
      (<?=LEAVE_TYPES[$l["leave_type"]]?>)
    </div>
    <div>
      <?=LEAVE_STATUS[$l["leave_status"]]?>
    </div>
  </div>
  <div>
    <button class="btn btn-danger btn-sm mi" 
      <?php if ($l["leave_status"]=="P") { ?>
      onclick="leave.cancel(<?=$l["leave_id"]?>)"
      <?php } else { ?>
      disabled
      <?php } ?>>delete
    </button>
    <button class="btn btn-primary btn-sm mi" onclick="leave.show(<?=$l["leave_id"]?>)">
      search
    </button>
  </div>
</div>
<?php }} else { echo "No records found."; } ?>