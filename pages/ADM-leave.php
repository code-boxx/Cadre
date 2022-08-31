<?php
$_PMETA = [
  "title" => "Admin Manage Leave",
  "load" => [["s", HOST_ASSETS."ADM-leave.js", "defer"]]
];
require PATH_PAGES . "TEMPLATE-top.php"; ?>
<!-- (A) HEADER -->
<h3 class="mb-3">MANAGE LEAVE</h3>

<!-- (B) YEAR BAR -->
<form class="d-flex align-items-stretch mb-3 p-2 head" onsubmit="return leave.set()">
  <input type="number" id="leave-year" placeholder="Year" class="form-control form-control-sm" value="<?=date("Y")?>">
  <button class="btn btn-primary mi me-1">
    search
  </button>
</form>

<!-- (C) LEAVE LIST -->
<div id="leave-list" class="zebra my-4"></div>
<?php require PATH_PAGES . "TEMPLATE-bottom.php"; ?>