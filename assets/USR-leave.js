var leave = {
  // (A) SHOW LEAVE
  year : null, // current year
  list : () => {
    cb.page(0);
    cb.load({
      page : "staff/leave/list", target : "leave-list",
      data : { year : leave.year }
    });
  },

  // (B) SET YEAR
  set : () => {
    leave.year = document.getElementById("leave-year").value;
    leave.list();
    return false;
  },

  // (C) APPLY LEAVE PART 1
  apply : () => cb.load({
    page : "staff/leave/form/a", target : "cb-page-2",
    onload : () => cb.page(1)
  }),

  // (D) APPLY LEAVE PART 2
  applyDays : () => {
    // (D1) GET DATE RANGE
    let from = document.getElementById("apply_from").value,
        to = document.getElementById("apply_to").value;

    // (D2) CHECK
    if (new Date(to) < new Date(from)) {
      cb.modal("Invalid Date", "Start date cannot be earlier than end date!");
      return false;
    }

    // (D3) OK - SHOW DAYS DOCKET
    cb.load({
      page : "staff/leave/form/b", target : "cb-page-3",
      data : {
        from : document.getElementById("apply_from").value,
        to : document.getElementById("apply_to").value
      },
      onload : () => {
        leave.total();
        cb.page(2);
      }
    });
    return false;
  },

  // (E) CALCULATE TOTAL LEAVE DAYS
  total : () => {
    let all = 0;
    for (let d of document.querySelectorAll("#leave-apply select")) {
      if (d.value == 0) { continue; }
      else if (d.value=="1") { all++; }
      else { all+=0.5; }
    }
    document.getElementById("leave-total-days").innerHTML = all;
  },

  // (F) SAVE LEAVE APPLICATION
  save : () => {
    // (F1) CHECK DAYS
    if (document.getElementById("leave-total-days").innerHTML=="0") {
      cb.modal("Error", "No applicable leave days.");
      return false;
    }

    // (F2) GET DAYS & DATA
    var data = {
      type : document.getElementById("apply_type").value,
      from : document.getElementById("apply_from").value,
      to : document.getElementById("apply_to").value,
      days : {}
    };
    for (let d of document.querySelectorAll("#leave-apply select")) {
      if (d.value == 0) { continue; }
      data.days[d.dataset.date] = d.value;
    }
    data.days = JSON.stringify(data.days);

    // (F3) API CALL
    cb.api({
      mod : "leave", req : "apply",
      data : data,
      passmsg : "Leave saved",
      onpass : leave.list
    });
    return false;
  },

  // (G) SHOW LEAVE DAYS
  show : id => cb.load({
    page : "staff/leave/show", target : "cb-page-2",
    data : { id : id },
    onload : () => cb.page(1)
  }),

  // (H) CANCEL LEAVE
  cancel : id => cb.modal("Please Confirm", "Cancel leave?", () => cb.api({
    mod : "leave", req : "cancel",
    data : { id : id },
    passmsg : "Leave canceled",
    onpass : leave.list
  }))
};
window.addEventListener("load", leave.set);