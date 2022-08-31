<?php
// (A) GET HOLIDAYS
$days = $_CORE->autoCall("Holiday", "getAll");

// (B) DRAW HOLIDAYS LIST
if (is_array($days)) { foreach ($days as $date=>$d) { ?>
<div class="d-flex align-items-center border p-2">
  <div class="flex-grow-1">
    <strong><?=$d["holiday_name"]?></strong><br>
    <small><?=$date?> (<?=$d["holiday_half"]?"Half day":"Full day"?>)</small>
  </div>
  <div>
    <button class="btn btn-danger btn-sm mi" onclick="hol.del(<?=$d["holiday_id"]?>)">
      delete
    </button>
    <button class="btn btn-primary btn-sm mi" onclick="hol.addEdit(<?=$d["holiday_id"]?>)">
      edit
    </button>
  </div>
</div>
<?php }} else { echo "No holidays defined."; } ?>