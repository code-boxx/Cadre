<?php
// (A) GET HOLIDAYS
$_CORE->load("Holiday");
$_CORE->load("Leave");
$holidays = $_CORE->Holiday->getAll($_POST["from"], $_POST["to"]);
$taken = $_CORE->Leave->getTaken($_SESS["user"]["user_id"], $_POST["from"], $_POST["to"]);

// (B) DRAW DAYS ?>
<h3 class="mb-3">CONFIRM DAYS</h3>
<div id="leave-apply" class="zebra my-4">
  <?php for ($unix=strtotime($_POST["from"]); $unix<=strtotime($_POST["to"]); $unix+=86400) {
  $date = date("Y-m-d", $unix);
  $dayNum = date("N", $unix);
  $dayName = date("l", $unix);
  $dayNo = $dayNum==6 || $dayNum==7 || (isset($holidays[$date]) && $holidays[$date]["holiday_half"]===0);
  $dayHalf = isset($holidays[$date]) && $holidays[$date]["holiday_half"]===1;
  $clash = isset($taken[$date]); ?>
  <div class="d-flex align-items-center border p-2">
    <div class="flex-grow-1">
      <div><span><?=$date?></span> (<?=$dayName?>)</div>
      <?php if (isset($holidays[$date])) { ?>
      <div class="text-danger"><?=$holidays[$date]["holiday_name"]?></div>
      <?php } ?>
      <?php if ($clash) { ?>
      <div class="text-danger">You have already applied leave on this date.</div>
      <?php } ?>
    </div>
    <select data-date="<?=$date?>" class="form-select w-auto" onchange="leave.total()"<?=$dayNo||$clash?" disabled":""?>>
      <?php if ($clash || $dayNo) { ?>
      <option value="0">NA</option>
      <?php } else { ?>
      <?php if (!$dayHalf) { ?>
      <option value="1">Full Day</option>
      <?php } ?>
      <option value="A">AM</option>
      <option value="P">PM</option>
      <option value="0">No Leave</option>
      <?php } ?>
    </select>
  </div>
  <?php } ?>

  <div class="d-flex align-items-center border p-2 fw-bold">
    <div class="flex-grow-1">Total Days</div>
    <div id="leave-total-days">0</div>
  </div>
</div>

<input type="button" class="btn btn-danger" value="Back" onclick="cb.page(1)">
<input type="button" class="btn btn-primary" value="Confirm Apply" onclick="leave.save()">