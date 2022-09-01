<?php
// (A) GET LEAVE
$_CORE->Settings->defineN(["LEAVE_TYPES", "LEAVE_STATUS"], true);
$_CORE->load("Leave");
$leave = $_CORE->autoCall("Leave", "getAllTaken");

// (B) DRAW LEAVE LIST
if (is_array($leave)) { foreach ($leave as $id=>$l) { ?>
<div class="d-flex align-items-center border p-2">
  <div class="flex-grow-1">
    <div>
      <strong>Date:</strong> <?=$l["leave_from"]?> to <?=$l["leave_to"]?>
    </div>
    <div>
      <strong>Staff:</strong> <?=$l["user_name"]?> (<?=$l["user_email"]?>)
    </div>
    <div>
      <strong>Type:</strong> <?=LEAVE_TYPES[$l["leave_type"]]?>
    </div>
    <div>
      <strong>Status:</strong> <?=LEAVE_STATUS[$l["leave_status"]]?>
    </div>
  </div>
  <div>
    <button class="btn btn-danger btn-sm mi" onclick="leave.cancel(<?=$id?>, false)">
      delete
    </button>
    <?php if ($l["leave_status"]=="P" || $l["leave_status"]=="A") { ?>
    <button class="btn btn-danger btn-sm mi" onclick="leave.permit(<?=$id?>, false)">
      close
    </button>
    <?php } ?>
    <?php if ($l["leave_status"]=="P" || $l["leave_status"]=="D") { ?>
    <button class="btn btn-primary btn-sm mi" onclick="leave.permit(<?=$id?>, true)">
      done
    </button>
    <?php } ?>
  </div>
</div>
<?php }} else { echo "No leave records found."; }

// (C) PAGINATION
$_CORE->load("Page");
$_CORE->Page->draw("leave.goToPage");