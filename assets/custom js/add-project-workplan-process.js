function get_table(opid = null, projid = null) {
    if (opid) {
        var frequency = $(`#outputMonitorigFreq${opid}`).val();
        $.ajax({
            type: "post",
            url: "assets/processor/add-project-workplan-process",
            data: { opid: opid, projid: projid, frequency: frequency, get_workplan: "get_workplan" },
            dataType: "html",
            success: function (response) {
                $(`#workplan_table${opid}`).html(response);
            }
        });
    }
}


function opyear(opid, year) {
    var ctarget = parseInt($(`#ctarget${opid}${year}`).val());
    var target = $(`#yearTargets${opid}${year}`).val();
    var balance = year_calculate(year, opid, ctarget);
    if (target != "") {
        target = parseInt(target);
        if (target >= 0) {
            if (ctarget >= target) {
                var balance = year_calculate(year, opid, ctarget);
                $(`#year_targetmsg${year}${opid}`).html(balance);
            } else {
                $(`#yearTargets${opid}${year}`).val("");
                alert("Ensure you do not surpass the ceiling");
                var balance = year_calculate(year, opid, ctarget);
                $(`#year_targetmsg${year}${opid}`).html(balance);
            }
        } else {
            alert("This field cannot accept a negative value");
            var balance = year_calculate(year, opid, ctarget);
            $(`#year_targetmsg${year}${opid}`).html(balance);
            $(`#yearTargets${opid}${year}`).val("");
        }
    } else {
        alert("This field does not accept a negative value ");
        var balance = year_calculate(year, opid, ctarget);
        $(`#year_targetmsg${year}${opid}`).html(balance);
    }
}

function year_calculate(year, opid, ctarget) {
    var semi_total = 0;
    $(`.year${year}${opid}`).each(function () {
        if ($(this).val() != "") {
            semi_total += parseInt($(this).val());
        }
    });
    var balance = ctarget - semi_total;
    return balance;
}

function semi(year, opid, rowno) {
    var ctarget = parseInt($(`#semi_targetc${year}${opid}`).val());
    var targetVal = $(`#semi_target${year}${opid}${rowno}`).val();
    if (targetVal != "") {
        targetVal = parseInt(targetVal);
        if (targetVal > 0) {
            var balance = semi_calculate(year, opid, ctarget);
            if (balance >= 0) {
                $(`#semi_targetmsg${year}${opid}`).html(balance);
            } else {
                $(`#semi_target${year}${opid}${rowno}`).val("");
                var balance = semi_calculate(year, opid, ctarget);
                $(`#semi_targetmsg${year}${opid}`).html(balance);
                alert("Ensure you do not exceed the approved target ");
            }
        } else {
            alert("This field does not accept a negative value ");
            var balance = semi_calculate(year, opid, ctarget);
            $(`#semi_targetmsg${year}${opid}`).html(balance);
            $(`#semi_target${year}${opid}${rowno}`).val("");
        }
    } else {
        alert("Ensure that this field has a value ");
        var balance = semi_calculate(year, opid, ctarget);
        $(`#semi_targetmsg${year}${opid}`).html(balance);
    }
}

function semi_calculate(year, opid, ctarget) {
    var semi_total = 0;
    $(`.semi${year}${opid}`).each(function () {
        if ($(this).val() != "") {
            semi_total += parseInt($(this).val());
        }
    });
    var balance = ctarget - semi_total;
    return balance;
}


function quarter(year, opid, rowno) {
    var ctarget = parseFloat($(`#quarter_targetc${year}${opid}`).val());
    var targetVal = $(`#quarter_target${year}${opid}${rowno}`).val();
    if (targetVal != "") {
        targetVal = parseFloat(targetVal);
        if (targetVal >= 0) {
            var balance = quarter_calculate(year, opid, ctarget);
            if (balance >= 0) {
                $(`#quarter_targetmsg${year}${opid}`).html(balance);
            } else {
                $(`#quarter_target${year}${opid}${rowno}`).val("");
                var balance = quarter_calculate(year, opid, ctarget);
                $(`#quarter_targetmsg${year}${opid}`).html(balance);
                alert("Ensure you do not exceed the approved target ");
            }
        } else {
            $(`#quarter_target${year}${opid}${rowno}`).val("");
            var balance = quarter_calculate(year, opid, ctarget);
            $(`#quarter_targetmsg${year}${opid}`).html(balance);
            alert("This field does not accept a negative value ");
        }
    } else {
        alert("Ensure that this field has a value ");
        var balance = quarter_calculate(year, opid, ctarget);
        $(`#quarter_targetmsg${year}${opid}`).html(balance);
    }
}


function quarter_calculate(year, opid, ctarget) {
    var quarter_total = 0;
    $(`.quarter${year}${opid}`).each(function () {
        if ($(this).val() != "") {
            quarter_total += parseFloat($(this).val());
        }
    });
    var balance = ctarget - quarter_total;
    return balance;
}


function month(year, opid, rowno) {
    var ctarget = parseInt($(`#month_targetc${year}${opid}`).val());
    var targetVal = $(`#month_target${year}${opid}${rowno}`).val();
    if (targetVal != "") {
        targetVal = parseInt(targetVal);
        if (targetVal > 0) {
            var balance = month_calculate(year, opid, ctarget);
            if (balance >= 0) {
                $(`#month_targetmsg${year}${opid}`).html(balance);
            } else {
                $(`#month_target${year}${opid}${rowno}`).val("");
                var balance = month_calculate(year, opid, ctarget);
                $(`#month_targetmsg${year}${opid}`).html(balance);
                alert("Ensure you do not exceed the approved target ");
            }
        } else {
            $(`#month_target${year}${opid}${rowno}`).val("");
            var balance = month_calculate(year, opid, ctarget);
            $(`#month_targetmsg${year}${opid}`).html(balance);
            alert("This field doe not accept a negative value ");
        }
    } else {
        alert("Ensure that this field has a value ");
        var balance = month_calculate(year, opid, ctarget);
        $(`#month_targetmsg${year}${opid}`).html(balance);
    }
}

function month_calculate(year, opid, ctarget) {
    var month_total = 0;
    $(`.month${year}${opid}`).each(function () {
        if ($(this).val() != "") {
            month_total += parseInt($(this).val());
        }
    });
    var balance = ctarget - month_total;
    return balance;
}


function week(year, opid, rowno) {
    var ctarget = parseInt($(`#week_targetc${year}${opid}`).val());
    var targetVal = $(`#week_target${year}${opid}${rowno}`).val();

    if (targetVal != "") {
        targetVal = parseInt(targetVal);
        if (targetVal > 0) {
            var balance = week_calculate(year, opid, ctarget);
            if (balance >= 0) {
                $(`#week_targetmsg${year}${opid}`).html(balance);
            } else {
                $(`#week_target${year}${opid}${rowno}`).val("");
                var balance = week_calculate(year, opid, ctarget);
                $(`#week_targetmsg${year}${opid}`).html(balance);
                alert("Ensure you do not exceed the approved target");
            }
        } else {
            $(`#week_target${year}${opid}${rowno}`).val("");
            var balance = week_calculate(year, opid, ctarget);
            $(`#week_targetmsg${year}${opid}`).html(balance);
            alert("This field does not accept a negative value");
        }
    } else {
        alert("Ensure that this field has a value ");
        var balance = week_calculate(year, opid, ctarget);
        $(`#week_targetmsg${year}${opid}`).html(balance);
    }
}

function week_calculate(year, opid, ctarget) {
    var week_total = 0;
    $(`.week${year}${opid}`).each(function () {
        if ($(this).val() != "") {
            week_total += parseInt($(this).val());
        }
    });
    var balance = ctarget - week_total;
    return balance;
}


function day(year, opid, rowno) {
    var ctarget = parseInt($(`#day_targetc${year}${opid}`).val());
    var targetVal = $(`#day_target${year}${opid}${rowno}`).val();

    if (targetVal != "") {
        targetVal = parseInt(targetVal);
        if (targetVal > 0) {
            var balance = day_calculate(year, opid, ctarget);
            console.log(balance);

            if (balance >= 0) {
                $(`#day_targetmsg${year}${opid}`).html(balance);
            } else {
                $(`#day_target${year}${opid}${rowno}`).val("");
                var balance = day_calculate(year, opid, ctarget);
                $(`#day_targetmsg${year}${opid}`).html(balance);
                alert("Ensure you do not exceed the approved target ");
            }
        } else {
            $(`#day_target${year}${opid}${rowno}`).val("");
            var balance = day_calculate(year, opid, ctarget);
            $(`#day_targetmsg${year}${opid}`).html(balance);
            alert("This field does not accept a negative value");
        }
    } else {
        alert("Ensure that this field has a value ");
        var balance = day_calculate(year, opid, ctarget);
        $(`#day_targetmsg${year}${opid}`).html(balance);
    }
}


function day_calculate(year, opid, ctarget) {
    var day_total = 0;
    $(`.day${year}${opid}`).each(function () {
        if ($(this).val() != "") {
            day_total += parseInt($(this).val());
        }
    });
    console.log(day_total)

    var balance = ctarget - day_total;
    return balance;
}




function year_validate() {
    var data = [];
    $(".opids").each(function () {
        if ($(this).val() != "") {
            var opid = $(this).val();
            $(`.t_year${opid}`).each(function () {
                if ($(this).val() != "") {
                    var target_year = $(this).val();
                    var ctarget = parseInt($(`#ctarget${opid}${target_year}`).val());
                    var sum_year = 0;
                    $(`.target_value${target_value}${opid}`).each(function () {
                        if ($(this).val() != "") {
                            var sum_year = sum_year + parseInt($(this).val());
                        }
                    });

                    var balance = ctarget - sum_year;
                    console.log(balance);
                    if (balance == 0) {
                        data.push(true);
                    } else {
                        data.push(false);
                    }
                }
            });
        }
    });

    if (data.includes(false)) {
        return false;
    } else {
        return true;
    }
}


function validateForm() {
    var year = year_validate();
    console.log(year);
    // if (year) {
    //     return true;
    // } else {
    //     return false;
    // }
    return false;

}

