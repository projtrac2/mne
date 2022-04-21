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
      success: function(response) {
        $("#projmoreinfo").html(response);
      } // /success function
    }); // /ajax to fetch Project Main Menu  image
  } else {
    alert("error please refresh the page");
  }
}

function moreInfo(itemId = null) {
  if (itemId) {
    $("#itemId").remove();
    // remove text-error
    $(".text-danger").remove();
    // remove from-group error
    $(".form-input")
      .removeClass("has-error")
      .removeClass("has-success");
    // modal div
    $(".div-result").addClass("div-hide");

    $.ajax({
      url: "general-settings/selected-items/fetch-selected-program-item",
      type: "post",
      data: { itemId: itemId },
      dataType: "html",
      success: function(response) { 
        $("#moreinfo").html(response);
      } // /success function
    }); // /ajax to fetch Project Main Menu  image
  } else {
    alert("error please refresh the page");
  }
} // /edit Project Main Menu  function


// remove Contractor Nationality
function removeItem(itemId = null) {
  if (itemId) {
    // remove Contractor Nationality button clicked
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/program-action",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function(response) {
            // loading remove button
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              // reload the manage Contractor Nationality table
              manageItemTable.ajax.reload(null, true);

              alert(response.messages);
              $(".modal").each(function() {
                $(this).modal("hide");
              });
            } else {
              alert(response.messages);
            } // /error
          } // /success function
        }); // /ajax fucntion to remove the Contractor Nationality
        return false;
      }); // /remove Contractor Nationality btn clicked
  } // /if Contractor Nationalityid
} // /remove Contractor Nationality function



// remove Project
function removeProj(itemId = null) {
  if (itemId) {
    $("#removeItemBtn")
      .unbind("click")
      .bind("click", function() {
        var deleteItem = 1;
        $.ajax({
          url: "general-settings/action/project-edit-action",
          type: "post",
          data: { itemId: itemId, deleteItem: deleteItem },
          dataType: "json",
          success: function(response) {
            $("#removeItemBtn").button("reset");
            if (response.success == true) {
              manageItemTable.ajax.reload(null, true);
              alert(response.messages);
              $(".modal").each(function() {
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
    //console.log(itemId);
    $.ajax({
      url: "ajax/projects/approve",
      type: "post",
      data: {
        approveProj: "approveProj",
        itemId: itemId,
      },
      dataType: "html",
      success: function (response) {
        $("#aproveBody").html(response);
      },
    });
  } else {
    alert("error please refresh the page");
  }
}

function add_row_approve_financier() {
  $("#remove_approve_tr").remove(); //new change
  $rowno = $("#approve_financier_table_body tr").length;
  $rowno = $rowno + 1;
  $("#approve_financier_table_body tr:last").after(
    `<tr id="financerow${$rowno}">
   <td> ${$rowno}</td>
   <td>
      <select  data-id="${$rowno}" name="source_category[]" onchange="category_change(${$rowno})" id="source_categoryrow${$rowno}" class="form-control validoutcome selected_category" required="required">
      </select>
   </td>
   <td>
      <select  data-id="${$rowno}" name="source[]" id="sourcerow${$rowno}" class="form-control selected_source" onchange="get_source_ceiling('${$rowno}')"  required="required">
         <option value="">Select Category First</option>
      </select>
   </td>
   <td>
    <input type="hidden" name="sourcefunding[]" id="source_valrow${$rowno}"/>
    <span id="source_span_row${$rowno}"></span>
   </td>
   <td>
      <input type="number" name="amountfunding[]"   id="amountfundingrow${$rowno}"  placeholder="Enter" onkeyup=amountfunding("row${$rowno}")    class="form-control" style="width: 85 %; float: right" required/>
   </td>
   <td>
      <button type="button" class="btn btn-danger btn-sm" id="delete" onclick='delete_row_approve_financier("financerow${$rowno}")' >
         <span class="glyphicon glyphicon-minus"></span>
      </button>
   </td>
</tr>`
  );
  numbering_approval_table();
  get_category($rowno);
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
