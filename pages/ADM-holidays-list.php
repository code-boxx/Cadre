<?php
// (A) GET HOLIDAYS
$days = $_CORE->autoCall("Holiday", "getAll");

// (B) DRAW HOLIDAYS LIST
if (is_array($days)) { foreach ($days as $date=>$d) { ?>
<div class="d-flex align-items-center border p-2">
  <div class="flex-grow-1">
    <div class="fw-bold"><?=$d["holiday_name"]?></div>
    <div><?=$date?></div>
    <span class="badge bg-danger"><?=$d["holiday_half"]?"Half day":"Full day"?></span>
  </div>
  <div class="dropdown">
    <button class="btn btn-primary btn-sm mi" type="button" data-bs-toggle="dropdown">
      more_vert
    </button>
    <ul class="dropdown-menu dropdown-menu-dark">
      <li class="dropdown-item" onclick="hol.addEdit(<?=$d["holiday_id"]?>)">
        <i class="mi mi-smol">edit</i> Edit
      </li>
      <li class="dropdown-item text-warning" onclick="hol.del(<?=$d["holiday_id"]?>)">
        <i class="mi mi-smol">delete</i> Delete
      </li>
    </ul>
  </div>
</div>
<?php }} else { echo "No holidays defined."; } ?>