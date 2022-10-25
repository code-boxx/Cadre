var hol = {
  // (A) SHOW ALL HOLIDAYS
  year : null, from : null, to : null,
  list : () => {
    cb.page(0);
    cb.load({
      page : "admin/holidays/list", target : "hol-list",
      data : {
        from : hol.from,
        to : hol.to
      }
    });
  },

  // (B) SET YEAR
  set : () => {
    hol.year = document.getElementById("hol-year").value;
    hol.from = `${hol.year}-01-01`;
    hol.to = `${hol.year}-12-31`;
    hol.list();
    return false;
  },

  // (C) SHOW ADD/EDIT DOCKET
  // id : holiday ID, for edit only
  addEdit : id => cb.load({
    page : "admin/holidays/form",
    target : "cb-page-2",
    data : { id : id ? id : "" },
    onload : () => cb.page(1)
  }),

  // (D) SAVE HOLIDAY
  save : () => {
    // (D1) GET DATA
    var data = {
      name : document.getElementById("holiday_name").value,
      date : document.getElementById("holiday_date").value,
      half : document.getElementById("holiday_half").value
    };
    var id = document.getElementById("holiday_id").value;
    if (id!="") { data.id = id; }

    // (D2) API CALL
    cb.api({
      mod : "holiday", req : "save",
      data : data,
      passmsg : "Holiday saved",
      onpass : hol.list
    });
    return false;
  },

  // (E) DELETE HOLIDAY
  //  id : holiday id
  del : id => cb.modal("Please confirm", "Delete this holiday?", () => cb.api({
    mod : "holiday", req : "del",
    data : { id : id },
    passmsg : "Holiday deleted",
    onpass : hol.list
  }))
};
window.addEventListener("load", hol.set);