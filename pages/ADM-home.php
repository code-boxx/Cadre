<?php
$_PMETA = ["load" => [["s", HOST_ASSETS."ADM-users.js", "defer"]]];
require PATH_PAGES . "TEMPLATE-top.php"; ?>
<!-- (A) HEADER -->
<h3 class="mb-3">MANAGE USERS</h3>

<!-- (B) ACTION BAR -->
<form class="d-flex align-items-stretch mb-3 p-2 head" onsubmit="return usr.search()">
  <!-- (B1) SEARCH -->
  <input type="text" id="usr-search" placeholder="Search" class="form-control form-control-sm flex-grow-1">

  <div class="btn-group mx-1">
    <button class="btn btn-primary mi">
      search
    </button>
    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"></button>
    <ul class="dropdown-menu dropdown-menu-dark">
      <li class="dropdown-item"><select class="form-select form-select w-auto" id="usr-active">
        <option value="1">Active Only</option>
        <option value="">All Users</option>
      </select></li>
    </ul>
  </div>
  <button class="btn btn-primary mi" onclick="usr.addEdit()">
    add
  </button>
</form>

<!-- (C) USERS LIST -->
<div id="usr-list" class="zebra my-4"></div>
<?php require PATH_PAGES . "TEMPLATE-bottom.php"; ?>