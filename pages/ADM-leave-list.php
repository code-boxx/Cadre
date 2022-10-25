<?php
// (A) GET LEAVE
$_CORE->Settings->defineN(["LEAVE_TYPES", "LEAVE_STATUS"], true);
$_CORE->load("Leave");
$leave = $_CORE->autoCall("Leave", "getAllTaken");

// (B) DRAW LEAVE LIST
if (is_array($leave)) { foreach ($leave as $id=>$l) { ?>
<div class="d-flex align-items-center border p-2">
  <div class="flex-grow-1">
    <div class="fw-bold"><?=$l["user_name"]?> (<?=$l["user_email"]?>)</div>
    <div><?=$l["leave_from"]?> to <?=$l["leave_to"]?> (<?=LEAVE_TYPES[$l["leave_type"]]?>)</div>
    <span class="badge bg-<?=$l["leave_status"]=="A"?"success":"danger"?>">
      <?=LEAVE_STATUS[$l["leave_status"]]?>
    </span>
  </div>
  <div class="dropdown">
    <button class="btn btn-primary btn-sm mi" type="button" data-bs-toggle="dropdown">
      more_vert
    </button>
    <ul class="dropdown-menu dropdown-menu-dark">
      <?php if ($l["leave_status"]=="P" || $l["leave_status"]=="D") { ?>
      <li class="dropdown-item" onclick="leave.permit(<?=$id?>, true)">
        <i class="mi mi-smol">done</i> Permit
      </li>
      <?php } ?>
      <?php if ($l["leave_status"]=="P" || $l["leave_status"]=="A") { ?>
      <li class="dropdown-item" onclick="leave.permit(<?=$id?>, false)">
        <i class="mi mi-smol">close</i> Reject
      </li>
      <?php } ?>
      <li class="dropdown-item text-warning" onclick="leave.cancel(<?=$id?>, false)">
        <i class="mi mi-smol">delete</i> Cancel
      </li>
    </ul>
  </div>
</div>
<?php }} else { echo "No leave records found."; }

// (C) PAGINATION
$_CORE->load("Page");
$_CORE->Page->draw("leave.goToPage");