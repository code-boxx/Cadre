<?php
$_PMETA = [
  "title" => "Admin Manage Users",
  "load" => [["s", HOST_ASSETS."ADM-users.js", "defer"]]
];
require PATH_PAGES . "TEMPLATE-top.php"; ?>
<!-- (A) HEADER -->
<h3 class="mb-3">MANAGE USERS</h3>

<!-- (B) SEARCH BAR bg-white border -->
<form class="d-flex align-items-stretch mb-3 p-2 head" onsubmit="return usr.search()">
  <input type="text" id="usr-search" placeholder="Search" class="form-control form-control-sm flex-grow-1">
  <select class="form-control form-control-sm w-auto" id="usr-active">
    <option value="1">Active Only</option>
    <option value="">All Users</option>
  </select>
  <button class="btn btn-primary mi me-1">
    search
  </button>
  <button class="btn btn-primary mi" onclick="usr.addEdit()">
    add
  </button>
</form>

<!-- (C) USERS LIST -->
<div id="usr-list" class="zebra my-4"></div>
<?php require PATH_PAGES . "TEMPLATE-bottom.php"; ?>