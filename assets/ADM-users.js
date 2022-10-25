var usr = {
  // (A) SHOW ALL USERS
  pg : 1, // current page
  find : "", // current search
  active : "", // active users only?
  list : () => {
    cb.page(0);
    cb.load({
      page : "admin/users/list", target : "usr-list",
      data : {
        page : usr.pg,
        search : usr.find,
        active: usr.active
      }
    });
  },

  // (B) GO TO PAGE
  //  pg : page number
  goToPage : pg => { if (pg!=usr.pg) {
    usr.pg = pg;
    usr.list();
  }},

  // (C) SEARCH USER
  search : () => {
    usr.find = document.getElementById("usr-search").value;
    usr.active = document.getElementById("usr-active").value;
    usr.list();
    return false;
  },

  // (D) SHOW ADD/EDIT DOCKET
  // id : user ID, for edit only
  addEdit : id => cb.load({
    page : "admin/users/form",
    target : "cb-page-2",
    data : { id : id ? id : "" },
    onload : () => cb.page(1)
  }),

  // (E) SAVE USER
  save : () => {
    // (E1) GET DATA
    var data = {
      name : document.getElementById("user_name").value,
      email : document.getElementById("user_email").value,
      title : document.getElementById("user_title").value,
      level : document.getElementById("user_level").value,
      password : document.getElementById("user_password").value
    };
    var id = document.getElementById("user_id").value;
    if (id!="") { data.id = id; }

    // (E2) PASSWORD CHECK
    if (!cb.checker(data.password)) {
      cb.modal("Please check", "Password must be at least 8 characters alphanumeric.");
      return false;
    }

    // (E3) API CALL
    cb.api({
      mod : "users", req : "save",
      data : data,
      passmsg : "User saved",
      onpass : usr.list
    });
    return false;
  },

  // (F) SUSPEND USER
  //  id : user id
  del : id => cb.modal("Please confirm", "Suspend this user account?", () => cb.api({
    mod : "users", req : "del",
    data : { id : id },
    passmsg : "User suspended",
    onpass : usr.list
  }))
};
window.addEventListener("load", usr.search);

var leave = {
  // (G) SHOW LEAVE ENTITLED DOCKET
  // id : user ID
  entitle : id => cb.load({
    page : "admin/leave/entitle",
    target : "cb-page-2",
    data : { id : id },
    onload : () => cb.page(1)
  }),

  // (H) SAVE LEAVE ENTITLED
  save : () => {
    // (H1) GET DATA
    var data = {
      id : document.getElementById("user_id").value,
      entitled : {}
    },
    types = document.querySelectorAll("#leave-form .leave-type"),
    days = document.querySelectorAll("#leave-form .leave-days");
    for (let i=0; i<types.length; i++) {
      data.entitled[types[i].value] = days[i].value;
    }
    data.entitled = JSON.stringify(data.entitled);

    // (H2) API CALL
    cb.api({
      mod : "leave", req : "saveEntitled",
      data : data,
      passmsg : "Leave updated"
    });
    return false;
  }
};