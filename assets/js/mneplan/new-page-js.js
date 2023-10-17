
function add_impact_questions() {
    var source = $("#impactdataSource").val();
    if (source == 1) {
		const input = document.getElementById('impactmainquestion');
		input.setAttribute('required', '');
        $(".impactquestions").show();
    } else {
        $(".impactquestions").hide();
        $('.impactquerry').removeAttr('required');
        $('#impactmainquestion').removeAttr('required');
    }
}