var ajax_url = "ajax/monitoring/discussion";
$(document).ready(function () {
    $("#modal_form_submit").submit(function (e) {
        e.preventDefault();
        $("#tag-form-submit").prop("disabled", true);
        var data = $(this)[0];
        var form = new FormData(data);
        $.ajax({
            type: "post",
            url: ajax_url,
            data: form,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    success_alert("Success!");
                } else {
                    sweet_alert("Error!");
                }
                $("#tag-form-submit").prop("disabled", false);
                setTimeout(() => {
                    window.location.reload(true);
                }, 3000);
            }
        });
    });
});

const BORDER_SIZE = 14;
const panel = document.getElementById("right_panel");

let m_pos;

function resize(e) {
    const dx = m_pos - e.x;
    m_pos = e.x;
    panel.style.width = (parseInt(getComputedStyle(panel, '').width) - dx) + "px";
}

panel.addEventListener("mousedown", function (e) {
    if (e.offsetX < BORDER_SIZE) {
        m_pos = e.x;
        document.addEventListener("mousemove", resize, false);
    }
}, false);

document.addEventListener("mouseup", function () {
    document.removeEventListener("mousemove", resize, false);
}, false);