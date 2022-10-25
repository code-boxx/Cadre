var leave = {
  // (A) SHOW LEAVE LIST
  pg : 1, // current page
  year : "", // current year
  list : () => {
    cb.page(0);
    cb.load({
      page : "admin/leave/list", target : "leave-list",
      data : {
        page : leave.pg,
        year : leave.year
      }
    });
  },

  // (B) GO TO PAGE
  //  pg : page number
  goToPage : pg => { if (pg!=leave.pg) {
    leave.pg = pg;
    leave.list();
  }},

  // (C) SET YEAR
  set : () => {
    leave.pg = 1;
    leave.year = document.getElementById("leave-year").value;
    leave.list();
    return false;
  },

  // (D) APPROVE/DENY LEAVE
  //  id : leave id
  //  approve : true/false
  permit : (id, approve) => cb.api({
    mod : "leave", req : "permit",
    data : {
      id : id,
      approve : approve ? 1 : 0
    },
    passmsg : "Leave updated",
    onpass : leave.list
  }),

  // (E) CANCEL LEAVE
  cancel : id => cb.modal("Please Confirm", "Cancel leave?", () => cb.api({
    mod : "leave", req : "cancel",
    data : { id : id },
    passmsg : "Leave canceled",
    onpass : leave.list
  }))
};
window.addEventListener("load", leave.set);