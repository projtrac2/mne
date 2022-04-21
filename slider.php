<script type="text/javascript">
$(document).ready(function () {
    $("div.slider").slider({
        min: 0,
        max: $(this).data("max"),
        slide: function (event, ui) {
            $("input#slider_value").val(ui.value);
        }
    });
});
</script>

<h1>Howdy!</h1>

<div class="slider" data-max="10"></div>

<label for="slider_value">Slider Value:</label>
<input type="text" id="slider_value" />





<div class="slider"
        data-on-change="dropValueToInput"
        data-role="slider"
        data-max-value="1000"
        data-min-value="0"></div>
<div class="input-control text">
    <input id="slider_input" value="0">
</div>
<script>
    function dropValueToInput(value, slider){
        $("#slider_input").val(value);
    }
</script>