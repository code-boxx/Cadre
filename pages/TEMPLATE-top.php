<!DOCTYPE html>
<html>
  <head>
    <!-- (A) HEAD -->
    <!-- (A1) TITLE, DESC, CHARSET, VIEWPORT -->
    <title><?=isset($_PMETA["title"])?$_PMETA["title"]:""?></title>
    <meta charset="utf-8">
    <meta name="description" content="<?=isset($_PMETA["desc"])?$_PMETA["desc"]:""?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5">
    <meta name="robots" content="noindex">

    <?php if (isset($_SESS["user"])) { ?>
    <!-- (A2) WEB APP & ICONS -->
    <link rel="icon" href="<?=HOST_ASSETS?>favicon.png" type="image/png">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="white">
    <link rel="apple-touch-icon" href="<?=HOST_ASSETS?>icon-512.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Core Boxx">
    <meta name="msapplication-TileImage" content="<?=HOST_ASSETS?>icon-512.png">
    <meta name="msapplication-TileColor" content="#ffffff">

    <!-- (A3) WEB APP MANIFEST -->
    <!-- https://web.dev/add-manifest/ -->
    <link rel="manifest" href="<?=HOST_BASE?>CB-manifest.json">

    <!-- (A4) SERVICE WORKER -->
    <script>if ("serviceWorker" in navigator) {
      navigator.serviceWorker.register("<?=HOST_BASE?>CB-worker.js", {scope: "./"});
    }</script>
    <?php } ?>

    <!-- (A5) LIBRARIES & SCRIPTS -->
    <!-- https://getbootstrap.com/ -->
    <!-- https://fonts.google.com/icons -->
    <link rel="stylesheet" href="<?=HOST_ASSETS?>bootstrap.min.css">
    <script defer src="<?=HOST_ASSETS?>bootstrap.bundle.min.js"></script>
    <style>
    @font-face{font-family:"Material Icons";font-style:normal;font-weight:400;src:url(<?=HOST_ASSETS?>maticon.woff2) format("woff2");}
    .mi{font-family:"Material Icons";font-weight:400;font-style:normal;font-size:24px;letter-spacing:normal;text-transform:none;display:inline-block;white-space:nowrap;word-wrap:normal;direction:ltr;-webkit-font-feature-settings:"liga";-webkit-font-smoothing:antialiased}
    .mi-big{font-size:32px}.mi-smol{font-size:18px}
    #cb-loading{transition:opacity .3s}.cb-hide{opacity:0;visibility:hidden;height:0}.cb-pg-hide{display:none}
    #cb-loading{width:100vw;height:100vh;position:fixed;top:0;left:0;z-index:999;background:rgba(0,0,0,.7)}#cb-loading .spinner-border{width:80px;height:80px}
    .head{background:#ddd}.zebra .d-flex{background:#fff;margin-bottom:10px}.zebra .d-flex:nth-child(odd){background-color:#f1f1f1}.pagination{border:1px solid #d0e8ff;background:#f0f8ff}
    </style>
    <script>var cbhost={base:"<?=HOST_BASE?>",api:"<?=HOST_API_BASE?>",assets:"<?=HOST_ASSETS?>"};</script>
    <script defer src="<?=HOST_ASSETS?>PAGE-cb.js"></script>

    <!-- (A6) ADDITIONAL SCRIPTS -->
    <?php if (isset($_PMETA["load"])) { foreach ($_PMETA["load"] as $load) {
      if ($load[0]=="s") {
        printf("<script src='%s'%s></script>", $load[1], isset($load[2]) ? " ".$load[2] : "");
      } else {
        printf("<link rel='stylesheet' href='%s'>", $load[1]);
      }
    }}
    if (isset($_PMETA)) { unset($_PMETA); } ?>
  </head>
  <body class="bg-light">
    <!-- (B) COMMON SHARED INTERFACE -->
    <!-- (B1) NOW LOADING -->
    <div id="cb-loading" class="d-flex justify-content-center align-items-center cb-hide">
      <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <!-- (B2) TOAST MESSAGE -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index:11">
      <div id="cb-toast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <span id="cb-toast-icon" class="mi"></span>
          <strong id="cb-toast-head" class="me-auto p-1"></strong>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div id="cb-toast-body" class="toast-body"></div>
      </div>
    </div>

    <!-- (B3) MODAL DIALOG BOX -->
    <div id="cb-modal" class="modal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content">
      <div class="modal-header">
        <h5 id="cb-modal-head" class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="cb-modal-body" class="modal-body"></div>
      <div id="cb-modal-foot" class="modal-footer">
      </div>
    </div></div></div>

    <!-- (C) MAIN NAV BAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top"><div class="container-fluid">
      <!-- (C1) MENU TOGGLE BUTTON -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- (C2) COLLAPSABLE WRAPPER -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- (C2-1) BRANDING LOGO -->
        <a class="navbar-brand" href="<?=HOST_BASE?>">
          <img src="<?=HOST_ASSETS?>favicon.png" loading="lazy" width="32" height="32"/>
        </a>

        <?php if (isset($_SESS["user"])) { ?>
        <!-- (C2-2) LEFT MENU ITEMS -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0"><?php
          require $_SESS["user"]["user_level"]=="A" 
            ? PATH_PAGES . "TEMPLATE-admin.php" 
            : PATH_PAGES . "TEMPLATE-user.php" ;
        ?></ul>
        <?php } ?>
      </div>

      <?php if (isset($_SESS["user"])) { ?>
      <!-- (C3) RIGHT USER -->
      <div class="d-flex align-items-center">
        <a class="dropdown-toggle text-decoration-none text-white mx-2"
           data-bs-toggle="dropdown">
            <span class="mi">person</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
          <li><a class="dropdown-item" href="<?=HOST_BASE?>account">My Account</a></li>
          <li><div class="dropdown-item" onclick="cb.bye()">Logout</div></li>
        </ul>
      </div>
      <?php } ?>
    </div></nav>

    <!-- (D) MAIN PAGE -->
    <div class="container pt-4">
      <div id="cb-page-1">