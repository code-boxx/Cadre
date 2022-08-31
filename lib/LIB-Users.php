<?php
class Users extends Core {
  // (A) PASSWORD CHECKER
  //  $password : password to check
  //  $pattern : regex pattern check (at least 8 characters, alphanumeric)
  function checker ($password, $pattern='/^(?=.*[0-9])(?=.*[A-Z]).{8,20}$/i') {
    if (preg_match($pattern, $password)) { return true; }
    else {
      $this->error = "Password must be at least 8 characters alphanumeric.";
      return false;
    }
  }

  // (B) ADD OR UPDATE USER
  //  $name : user name
  //  $title : user title
  //  $email : user email
  //  $password : user password
  //  $level : user level
  //  $id : user id (for updating only)
  function save ($name, $title, $email, $password, $level="U", $id=null) {
    // (B1) DATA SETUP + PASSWORD CHECK
    if (!$this->checker($password)) { return false; }
    $fields = ["user_name", "user_title", "user_email", "user_password", "user_level"];
    $data = [$name, $title, $email, password_hash($password, PASSWORD_DEFAULT), $level];

    // (B2) ADD/UPDATE USER
    if ($id==null) {
      $this->DB->insert("users", $fields, $data);
    } else {
      $data[] = $id;
      $this->DB->update("users", $fields, "`user_id`=?", $data);
    }
    return true;
  }

  // (C) UPDATE ACCOUNT (LIMITED SAVE)
  function update ($name, $email, $password) {
    // (C1) MUST BE SIGNED IN
    global $_SESS;
    if (!isset($_SESS["user"])) {
      $this->error = "Please sign in first";
      return false;
    }
    
    // (C2) UPDATE DATABASE
    $this->DB->update("users",
      ["user_name", "user_email", "user_password"],
      "`user_id`=?", [$name, $email, password_hash($password, PASSWORD_DEFAULT), $_SESS["user"]["user_id"]]
    );
    return true;
  }

  // (D) SUSPEND USER
  //  $id : user id
  function del ($id) {
    $this->DB->update("users", ["user_level"], "`user_id`=?", ["S", $id]);
    return true;
  }

  // (E) GET USER
  //  $id : user id or email
  function get ($id) {
    return $this->DB->fetch(
      "SELECT * FROM `users` WHERE `user_". (is_numeric($id)?"id":"email") ."`=?",
      [$id]
    );
  }

  // (F) GET ALL OR SEARCH USER
  //  $search : optional, user name or email
  //  $page : optional, current page number
  //  $active : optional, active users only
  function getAll ($search=null, $page=null, $active=null) {
    // (F1) PARITAL USER SQL + DATA
    $sql = "FROM `users`";
    $data = null;
    if ($search != null) {
      $sql .= " WHERE `user_name` LIKE ? OR `user_email` LIKE ?";
      $data = ["%$search%", "%$search%"];
    }
    if ($active !=null) {
      $sql .= $search == null ? " WHERE `user_level`!='S'" : " AND `user_level`!='S'";
    }

    // (F2) PAGINATION
    if ($page != null) {
      $pgn = $this->core->paginator(
        $this->DB->fetchCol("SELECT COUNT(*) $sql", $data), $page
      );
      $sql .= " LIMIT {$pgn["x"]}, {$pgn["y"]}";
    }

    // (F3) RESULTS
    $user = $this->DB->fetchAll("SELECT * $sql", $data, "user_id");
    return $page != null
     ? ["data" => $user, "page" => $pgn]
     : $user ;
  }

  // (G) VERIFY EMAIL & PASSWORD (LOGIN OR SECURITY CHECK)
  // RETURNS USER ARRAY IF VALID, FALSE IF INVALID
  //  $email : user email
  //  $password : user password
  function verify ($email, $password) {
    // (G1) GET USER
    $user = $this->get($email);
    $pass = is_array($user);

    // (G2) SUSPENDED CHECK
    if ($pass) {
      $pass = $user["user_level"] != "S";
    }

    // (G3) PASSWORD CHECK
    if ($pass) {
      $pass = password_verify($password, $user["user_password"]);
    }

    // (G4) RESULTS
    if (!$pass) {
      $this->error = "Invalid user or password.";
      return false;
    }
    return $user;
  }

  // (H) LOGIN
  //  $email : user email
  //  $password : user password
  function login ($email, $password) {
    // (H1) ALREADY SIGNED IN
    global $_SESS;
    if (isset($_SESS["user"])) { return true; }

    // (H2) VERIFY EMAIL PASSWORD
    $user = $this->verify($email, $password);
    if ($user===false) { return false; }

    // (H3) SESSION START
    $_SESS["user"] = $user;
    $this->core->Session->create();
    return true;
  }

  // (I) LOGOUT
  function logout () {
    // (I1) ALREADY SIGNED OFF
    global $_SESS;
    if (!isset($_SESS["user"])) { return true; }

    // (I2) END SESSION
    $this->core->Session->destroy();
    return true;
  }
}