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
    <span class="badge bg-<?=$l["leave_status"]=="A"?"success":"danger"?>">
      <?=LEAVE_STATUS[$l["leave_status"]]?>
    </span>
  </div>
  <div class="dropdown">
    <button class="btn btn-primary btn-sm mi" type="button" data-bs-toggle="dropdown">
      more_vert
    </button>
    <ul class="dropdown-menu dropdown-menu-dark">
      <li class="dropdown-item" onclick="leave.show(<?=$l["leave_id"]?>)">
        <i class="mi mi-smol">search</i> View
      </li>
      <li class="dropdown-item<?=$l["leave_status"]=="P"?"":" disabled"?>" 
        <?php if ($l["leave_status"]=="P") { ?>
        onclick="leave.cancel(<?=$l["leave_id"]?>)"
        <?php } ?>>
        <i class="mi mi-smol">delete</i> Cancel
      </li>
    </ul>
  </div>
</div>
<?php }} else { echo "No records found."; } ?>