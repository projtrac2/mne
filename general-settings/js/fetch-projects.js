var manageItemTable;

$(document).ready(function () {
  manageItemTable = $("#manageItemTable").DataTable({
    ajax: url,
    order: [],
    columnDefs: [
      {
        targets: [0, 6],
        orderable: false
      }
    ]
  });
});

function more(itemId = null) {
  if (itemId) {
    $("#itemId").remove();
    $(".text-danger").remove();
    $(".form-input")
      .removeClass("has-error")
      .removeClass("has-success");
    $(".div-result").addClass("div-hide");

    $.ajax({
      url: "general-settings/selected-items/fetch-selected-projects-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "html",
      success: function (response) {
        $("#moreinfo").html(response);
      } // /success function
    }); // /ajax to fetch Project Main Menu  image
  } else {
    alert("error please refresh the page");
  }
}

// remove Project
function removeItem(itemId = null) {
  if (itemId) {
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function () {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-edit-action",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function (response) {
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              manageItemTable.ajax.reload(null, true);
              alert(response.messages);
              $(".modal").each(function () {
                $(this).modal("hide");
              });
            } else {
              alert(response.messages);
            }
          }
        });
        return false;
      });
  }
}

function approveItem(itemId = null) {
  if (itemId) {
    $.ajax({
      url: "general-settings/action/project-approval",
      type: "post",
      data: { itemId: itemId },
      dataType: "html",
      success: function (response) {
        $("#aproveBody").html(response);
      }
    });
  } else {
    alert("error please refresh the page");
  }
}

// approve item
$("#approveItemForm")
  .unbind("submit")
  .bind("submit", function (e) {
    e.preventDefault();
    var form = $(this);
    var formData = new FormData(this);

    var sumOutputBudget = 0;
    $("input[name='projcost[]']").each(function () {
      if ($(this).val()) {
        sumOutputBudget = parseFloat($(this).val()) + sumOutputBudget;
      }
    });

    var financierContribution = 0;
    $("input[name='amountfunding[]']").each(function () {
      if ($(this).val()) {
        financierContribution =
          parseFloat($(this).val()) + financierContribution;
      }
    });

    var difference = sumOutputBudget - financierContribution;

    if (difference == 0) {
      var bhandler = validate_location_state();
      if (bhandler) {
        $.ajax({
          url: "general-settings/action/project-edit-action",
          type: "post",
          data: formData,
          dataType: "json",
          cache: false,
          contentType: false,
          processData: false,
          success: function (response) {
            if (response) {
              $("#editProductBtn").button("reset");
              manageItemTable.ajax.reload(null, true);
              alert(response.messages);
              $(".modal").each(function () {
                $(this).modal("hide");
              });
            }
          }
        });
      }
    } else {
      alert("Ensure that the financier funding is equal to Output Budget");
    }
  });

function Undo(itemId = null) {
  if (itemId) {
    $("#Unapprove")
      .unbind("click")
      .bind("click", function () {
        var unapproveitem = 1;
        $.ajax({
          url: "general-settings/action/project-edit-action",
          type: "post",
          data: { itemId: itemId, unapproveitem: unapproveitem },
          dataType: "json",
          success: function (response) {
            $("#unapproveItemBtn").button("reset");
            if (response.success == true) {
              manageItemTable.ajax.reload(null, true);
              alert(response.messages);
              $(".modal").each(function () {
                $(this).modal("hide");
              });
            } else {
              alert(response.messages);
            }
          }
        });
        return false;
      });
  }
}

function add_row_files() {
  $("#add_new_file").remove();
  $rowno = $("#meetings_table tr").length;
  $rowno = $rowno + 1;
  $("#meetings_table tr:last").after(
    '<tr id="mtng' +
    $rowno +
    '">' +
    "<td>" +
    "</td>" +
    "<td>" +
    '<input type="file" name="pfiles[]" id="pfiles" multiple class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>' +
    "</td>" +
    "<td>" +
    '<input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>' +
    "</td>" +
    "<td>" +
    '<button type="button" class="btn btn-danger btn-sm"  onclick=delete_files("mtng' +
    $rowno +
    '")>' +
    '<span class="glyphicon glyphicon-minus"></span>' +
    "</button>" +
    "</td>" +
    "</tr>"
  );
  numbering_files();
}

function delete_files(rowno) {
  $("#" + rowno).remove();
  numbering_files();
  $number = $("#meetings_table tr").length;
  if ($number == 1) {
    $("#meetings_table tr:last").after(
      '<tr id="add_new_file"><td colspan="4">Attach file </td></tr>'
    );
  }
}

function delete_attachment(rowno) {
  $("#" + rowno).remove();
}

// auto numbering table rows on delete and add new for financier table
function numbering_files() {
  $("#meetings_table tr").each(function (idx) {
    $(this)
      .children()
      .first()
      .html(idx - 1 + 1);
  });
}

function financeirChange(rowno) {
  var financier = $("#finance" + rowno).val();
  var sourcecategory = $("#source_category" + rowno).val();
  var financierCeiling = "#financierCeiling" + rowno;
  var projfscyear = $("#projfscyear").val();

  if (financier) {
    $.ajax({
      type: "post",
      url: "general-settings/action/project-edit-action",
      data: {
        get_finance: "finance",
        financier: financier,
        projfscyear: projfscyear,
        sourcecategory: sourcecategory
      },
      dataType: "json",
      success: function (response) {
        var finance = $("#finance" + rowno).val();
        if (finance) {
          if (response.msg == "true") {
            var responseval = response.remaining;
            $(financierCeiling).html(commaSeparateNumber(parseInt(responseval)));
            $("#ceilingval" + rowno).val(responseval);
          } else if (response.msg == "false") {
            $("#ceilingval" + rowno).val("No Balance");
            $("#finance" + rowno).val("");
            $(financierCeiling).html("");
          }
        } else {
          $("#ceilingval" + rowno).val("No Balance");
          $("#finance" + rowno).val("");
          $(financierCeiling).html("");
        }
      }
    });
  } else {
    alert("Select Input");
    $(financierCeiling).html("");
    $("#amountfunding" + rowno).val("");
  }
}


function add_row_approve_financier() {
  $("#remove_approve_tr").remove(); //new change
  $rowno = $("#approve_financier_table_body tr").length;
  $rowno = $rowno + 1;
  $("#approve_financier_table_body tr:last").after(
    '<tr id="financerow' +
    $rowno +
    '">' +
    "<td>" +
    $rowno +
    "</td>" +
    "<td>" +
    '<select  data-id="' +
    $rowno +
    '" name="source_category[]" onchange="category_change(' + $rowno + ')" id="source_categoryrow' +
    $rowno +
    '" class="form-control validoutcome selected_category" required="required">' +
    "</select>" +
    "</td>" +
    "<td>" +
    '<select  data-id="' +
    $rowno +
    '" name="source[]" id="sourcerow' +
    $rowno +
    '" class="form-control selected_source" onchange="get_source_ceiling(' + $rowno + ')"  required="required">' +
    '<option value="">Select Category First</option></select>' +
    "</td>" +
    "<td>" +
    '<span id="source_category_span_row' + $rowno + '" class="source_category_span_row' + $rowno + '"></span>' +
    '<input type="hidden" name="category[]" class="" id="source_category_valrow' + $rowno + '"/>' +
    "</td>" +
    "<td>" +
    '<input type="hidden" name="sourcefunding[]"   id="source_valrow' + $rowno + '"/>' +
    '<span id="source_span_row' + $rowno + '"></span>' +
    "</td>" +
    "<td>" +
    '<input type="number" name="amountfunding[]"   id="amountfundingrow' +
    $rowno +
    '"   placeholder="Enter" onkeyup=amountfunding("row' + $rowno + '")    class="form-control" style="width: 85 %; float: right" required/>' +
    "</td>" +
    "<td>" +
    '<button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_approve_financier("financerow' +
    $rowno +
    '")>' +
    '<span class="glyphicon glyphicon-minus"></span>' +
    "</button>" +
    "</td>" +
    "</tr>"
  );
  numbering_approval_table();
  get_category($rowno)
}

// function to delete row 
function delete_row_approve_financier(rowno) {
  $("#" + rowno).remove();
  numbering_approval_table();
  $number = $("#approve_financier_table_body tr").length;
  if ($number == 1) {
    $("#approve_financier_table_body tr:last").after(
      '<tr id="remove_approve_tr"><td colspan="7">Add Financier </td></tr>'
    );
  }
}

// auto numbering table rows on delete and add new for financier table
function numbering_approval_table() {
  $("#approve_financier_table_body tr").each(function (idx) {
    $(this)
      .children()
      .first()
      .html(idx - 1 + 1);
  });
}

function get_category(rowno) {
  var projid = $("#projid").val();
  if (projid) {
    $.ajax({
      type: "post",
      url: "general-settings/action/project-edit-action",
      data: {
        get_category: "get_category",
        projid: projid
      },
      dataType: "html",
      success: function (response) {
        $("#source_categoryrow" + rowno).html(response);
      }
    });
  }
}

function category_change(rowno) {
  var category = $("#source_categoryrow" + rowno).val();
  var projid = $("#projid").val();
  var projfscyear = $("#projfscyear").val();
  if (category) {
    $.ajax({
      type: "post",
      url: "general-settings/action/project-edit-action",
      data: {
        get_source: "get_source",
        projfscyear: projfscyear,
        projid: projid,
        sourcecategory: category
      },
      dataType: "json",
      success: function (response) {
        if (category != "") {
          $("#sourcerow" + rowno).html(response.source);
          $("#source_category_valrow" + rowno).val(response.category_ceiling);
          $("#amountfundingrow" + rowno).val("");
          $("#amountfundingrow" + rowno).removeAttr("class");
          $("#amountfundingrow" + rowno).addClass("form-control amount_funding_valrow" + category);
          $("#source_category_span_row" + rowno).removeAttr("class");
          $("#source_category_span_row" + rowno).addClass("source_category_span_row" + category);
          var handler = category_fund_validate(rowno);
          if (handler != false) {
            $(".source_category_span_row" + category).html(commaSeparateNumber(parseFloat(handler)));
          } else {
            $(".source_category_span_row" + category).html(commaSeparateNumber(parseFloat(response.category_ceiling)));
          }
        } else {
          $("#amountfundingrow" + rowno).removeAttr("class");
          $("#amountfundingrow" + rowno).addClass("form-control");
          $("#source_category_span_row" + rowno).removeAttr("class");
          $("#sourcerow" + rowno).html('<option value="">Select Category</option>');
          $("#source_category_span_row" + rowno).html("");
          $("#source_category_valrow" + rowno).val("");
          $("#amountfundingrow" + rowno).val("");
          $("#source_span_row" + rowno).html("");
          $("#source_valrow" + rowno).val("");
        }
      }
    });
  } else {
    $("#amountfundingrow" + rowno).removeAttr("class");
    $("#amountfundingrow" + rowno).addClass("form-control");
    $("#sourcerow" + rowno).html('<option value="">Select Category</option>');
    $("#source_category_span_row" + rowno).html(commaSeparateNumber(""));
    $("#source_category_valrow" + rowno).val("");
    $("#amountfundingrow" + rowno).val("");
    $("#source_span_row" + rowno).html("");
    $("#source_valrow" + rowno).val("");
  }
}

function get_source_ceiling(rowno) {
  var source = $("#sourcerow" + rowno).val();
  var category = $("#source_categoryrow" + rowno).val();
  var category = $("#source_categoryrow" + rowno).val();
  var projid = $("#projid").val();
  var projfscyear = $("#projfscyear").val();
  if (category && source) {
    $.ajax({
      type: "post",
      url: "general-settings/action/project-edit-action",
      data: {
        get_source_ceiling: "get_source_ceiling",
        projfscyear: projfscyear,
        projid: projid,
        sourcecategory: category
      },
      dataType: "json",
      success: function (response) {
        var source_new = $("#sourcerow" + rowno).val();
        if (source_new != "") {
          $("#source_span_row" + rowno).html(commaSeparateNumber(parseFloat(response.remaining)));
          $("#source_valrow" + rowno).val(response.remaining);
          $("#amountfundingrow" + rowno).val("");
        } else {
          $("#source_span_row" + rowno).html("");
          $("#source_valrow" + rowno).val("");
          $("#amountfundingrow" + rowno).val("");
        }
      }
    });
  } else {
    $("#source_span_row" + rowno).html(commaSeparateNumber(parseFloat("")));
    $("#source_valrow" + rowno).val("");
    $("#amountfundingrow" + rowno).val("");
  }
}


//filter the source  cannot be selected twice 
$(document).on('change', '.selected_source', function (e) {
  var tralse = true;
  var attrb = $(this).attr("id");
  var data_id = $(this).attr("data-id");
  var selectedid = "#" + attrb;
  var selectedText = $(selectedid + " option:selected").html();
  var selectSource_arr = []; // for contestant name
  var selected = []; // for contestant name

  $('.selected_source').each(function (k, v) {
    var getVal = $(v).val();
    var guess = $(v).attr("data-id");
    var sourcecat = $("#source_categoryrow" + guess).val();
    var getit = sourcecat + getVal;

    if (getit && $.trim(selectSource_arr.indexOf(getit)) != -1) {
      tralse = false;
      alert('You cannot select source ' + selectedText + ' more than once ');
      $(v).val("");
      return false;
    } else {
      selectSource_arr.push(sourcecat + getVal);
    }
  });
  if (!tralse) {
    return false;
  }
});
