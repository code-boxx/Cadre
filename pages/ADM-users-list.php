<?php
// (A) GET USERS
$_CORE->Settings->defineN("USR_LEVELS", true);
$users = $_CORE->autoCall("Users", "getAll");

// (B) DRAW USERS LIST
if (is_array($users)) { foreach ($users as $id=>$u) { ?>
<div class="d-flex align-items-center border p-2">
  <div class="flex-grow-1">
    <div class="fw-bold">
      <?=$u["user_name"]?>
      (<a href="mailto:<?=$u["user_email"]?>"><?=$u["user_email"]?></a>)
    </div>
    <div><?=$u["user_title"]?></div>
    <span class="badge bg-danger"><?=USR_LEVELS[$u["user_level"]]?></span>
  </div>
  <div class="dropdown">
    <button class="btn btn-primary btn-sm mi" type="button" data-bs-toggle="dropdown">
      more_vert
    </button>
    <ul class="dropdown-menu dropdown-menu-dark">
      <li class="dropdown-item" onclick="usr.addEdit(<?=$id?>)">
        <i class="mi mi-smol">edit</i> Edit
      </li>
      <li class="dropdown-item" onclick="leave.entitle(<?=$id?>)">
        <i class="mi mi-smol">event_available</i> Entitled Leave
      </li>
      <li class="dropdown-item text-warning" onclick="usr.del(<?=$id?>)">
        <i class="mi mi-smol">delete</i> Delete
      </li>
    </ul>
  </div>
</div>
<?php }} else { echo "No users found."; }

// (C) PAGINATION
$_CORE->load("Page");
$_CORE->Page->draw("usr.goToPage");