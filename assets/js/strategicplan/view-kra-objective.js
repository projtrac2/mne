function add_strow() {
	$rwno = $("#strategy_table tr").length;
	$rwno = $rwno + 1;
	$("#strategy_table tr:last").after(`
        <tr id="row${$rwno}">
            <td>
                <input type="text" name="strategic[]" id="strategic" class="form-control"  placeholder="Strategic Objective" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm"  onclick=delete_strow("row${$rwno}")>
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </td>
        </tr>
    `);
}  

function delete_strow(rwno) { 
	$("#" + rwno).remove();
}