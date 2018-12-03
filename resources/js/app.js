
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

function updateFormVisibility(){
    var participation = $("input[name=field-1]:checked"); // radio button
    if (participation.length === 0){
        participation = $("input[name=field-1]"); // read-only hidden input
    }
    if (participation.val() === "Yes"){
        $("#participant-form").show();
    } else {
        $("#participant-form").hide();
    }

    if (participation.val() === "No"){
        $("#field-group-2").show();
    } else {
        $("#field-group-2").hide();
    }

    $("form input").each(function(){
        var group = $(this).closest(".form-group");
        var condition = group.attr("data-condition");
        if (condition !== undefined && condition !== ""){
            group.hide();
            var inputId = condition.split(":")[0];
            var expectedValue = condition.split(":")[1];
            var input = $("input[name=field-"+inputId+"]:checked"); // radio button
            if (input.length === 0){
                input = $("input[name=field-"+inputId+"]"); // text or other
            }
            if (input.val() === expectedValue){
                group.show();
            } else {
                group.hide();
            }
        }

    });

}

$(document).ready(function () {
    $("input").on( "change", updateFormVisibility);
    updateFormVisibility();
});
