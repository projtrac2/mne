// function o get add inspection checklist
function getAddTeamForm(projid) {
  if (projid) {
    $.ajax({
      type: "post",
      url: "general-settings/action/project-team-action",
      data: {
        getAddProjectTeam: "new",
        projid: projid
      },
      dataType: "html",
      success: function (response) {
        $("#teamForm").html(response);
        $("#title").html('<i class="fa fa-plus-square"></i> Add Project Team');
      }
    });
  } else {
    console.log("Id does not exists");
  }
}

// get edit inspection checklist
function more(projid) {
  if (projid) {
    $.ajax({
      type: "post",
      url: "general-settings/action/project-team-action",
      data: {
        more: "more",
        projid: projid
      },
      dataType: "html",
      success: function (response) {
        $("#moreinfo").html(response);
      }
    });
  } else {
    console.log("Id does not exists");
  }
}

// get edit inspection checklist
function getEditTeamForm(projid, progid) {
  if (projid) {
    $.ajax({
      type: "post",
      url: "general-settings/action/project-team-action",
      data: {
        getEditProjectTeam: "edit",
        projid: projid
      },
      dataType: "html",
      success: function (response) {
        $("#teamForm").html(response);
        $("#title").html('<i class="fa fa-pencil-square"></i> Edit Project Team');
        $('.selectpicker').selectpicker('refresh');
      }
    });
  } else {
    console.log("Id does not exists");
  }
}

// remove function
function removeItem(projid, option) {
  if (projid) {
    if (option == "2") {
      $("#removeItemBtn")
        .unbind("click")
        .bind("click", function () {
          $.ajax({
            url: "general-settings/action/project-team-action",
            type: "post",
            data: { projid: projid, deleteTeam: "deleteItem" },
            dataType: "json",
            success: function (response) {
              $("#removeItemBtn").button("reset");
              if (response.success == true) {
                alert(response.messages);
                $(".modal").each(function () {
                  $(this).modal("hide");
                });
                location.reload(true);
              } else {
                alert(response.messages);
                location.reload(true);
              }
            }
          });
          return false;
        });
    } else if (option == "3") {
      $("#removeItemBtn")
        .unbind("click")
        .bind("click", function () {
          $.ajax({
            url: "general-settings/action/project-team-action",
            type: "post",
            data: { itemId: itemId, deleteMonitoring: "deleteItem" },
            dataType: "json",
            success: function (response) {
              $("#removeItemBtn").button("reset");
              if (response.success == true) {
                alert(response.messages);
                $(".modal").each(function () {
                  $(this).modal("hide");
                });
                location.reload(true);
              } else {
                alert(response.messages);
                location.reload(true);
              }
            }
          });
          return false;
        });
    }
  }
}

function getrole(rowno) {
  var memberrole = "#rolerow" + rowno;
  $.ajax({
    type: "POST",
    url: "general-settings/action/project-team-action",
    data: {
      getmemberrole: "getmemberrole"
    },
    success: function (html) {
      $(memberrole).html(html);
    }
  });
}

function add_row_member() {
  $("#hideinfo").remove(); //new change 
  $rowno = $("#member_table_body tr").length;
  $rowno = $rowno + 1;
  var sourceid = "source" + $rowno;

  $("#member_table_body tr:last").after(
    `<tr id="row${$rowno}">
    <td></td>
    <td>
    <select data-id="${$rowno}" name="role[]" id="rolerow${$rowno}"  class="form-control validrole selectRole" required="required">
      <option value="">Select Member Role</option> 
    </select>
    </td>
    <td>
      <select data-id="${$rowno}" name="department[]" id="departmentrow${$rowno}" onchange="getmember(${$rowno})" data-live-search="true"  class="form-control selectpicker" required="required">
        <option value="">Select Project Department </option> 
      </select>
    </td> 
    <td>
      <select data-id="${$rowno}" name="member[]" id="memberrow${$rowno}" data-live-search="true"  class="form-control selectpicker" required="required">
        <option value="">Select Project Member </option> 
      </select>
    </td> 
    <td>
      <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_member("row${$rowno}")>
        <span class="glyphicon glyphicon-minus"></span>
      </button>
    </td>
  </tr>`);

  $('.selectpicker').selectpicker('refresh');

  numberingMember();
  get_department($rowno);
  getrole($rowno);
}

function delete_row_member(rowno) {
  $("#" + rowno).remove();
  // removeDiv(rowno);
  numberingMember();
}


function numberingMember() {
  $("#member_table_body tr").each(function (idx) {
    $(this)
      .children()
      .first()
      .html(idx - 1 + 1);
  });
}

function get_department(rowno) {
  var department = "#departmentrow" + rowno;
  $.ajax({
    type: "POST",
    url: "general-settings/action/project-team-action",
    data: {
      get_department: "get_department",
    },
    success: function (html) {
      $(department).html(html);
      $('.selectpicker').selectpicker('refresh');
    }
  });
}

function getmember(rowno) {
  var projmember = "#memberrow" + rowno;
  var projid = $("#projid").val();
  var department = $('#departmentrow' + rowno).val();
  $.ajax({
    type: "POST",
    url: "general-settings/action/project-team-action",
    data: {
      getprojmember: "getprojmember",
      projid: projid,
      department: department,
    },
    success: function (html) {
      console.log(projmember);
      $(projmember).html(html);
      $('.selectpicker').selectpicker('refresh');
    }
  });
}

$(document).ready(function () {
  $("#team-form-submit").click(function (e) {
    e.preventDefault();
    var role = [];
    $("select[name='role[]']").each(function () {
      role.push($(this).val());
    });

    var member = [];
    $("select[name='member[]']").each(function () {
      member.push($(this).val());
    });

    var projid = $("#projid").val();
    var user_name = $("#user_name").val();
    var action = $("#action").val();

    if (member.length > 0 && role.length > 0) {
      if (action == 2) {
        $.ajax({
          type: "post",
          url: "general-settings/action/project-team-action",
          data: {
            editProjTeam: "edit",
            member: member,
            projid: projid,
            role: role,
            user_name: user_name
          },
          dataType: "json",
          success: function (response) {
            alert(response.messages);
            location.reload(true);
          }
        });
      } else if (action == 1) {
        $.ajax({
          type: "post",
          url: "general-settings/action/project-team-action",
          data: {
            addProjTeam: "new",
            role: role,
            member: member,
            projid: projid,
            user_name: user_name
          },
          dataType: "json",
          success: function (response) {
            if (response.success == true) {
              alert(response.messages);
              $(".modal").each(function () {
                $(this).modal("hide");
              });
              location.reload(true);
            } else {
              alert(response.messages);
              location.reload(true);
            }
          }
        });
      }
    }


  });

  // functionality to collapse
  $(".collapse td").click(function (e) {
    e.preventDefault();
    $(this)
      .find("i")
      .toggleClass("fa-plus-square fa-minus-square");
  });

  // functionality to collapse
  $(".outputs td").click(function (e) {
    e.preventDefault();
    $(this)
      .find("i")
      .toggleClass("fa-plus-square fa-minus-square");
  });
});



