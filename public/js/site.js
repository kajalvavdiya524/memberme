$(document).ready(function(){

});
function isChecked(checkbox) {
    var submitbutton = $('#submitbutton');
    if(checkbox.checked === true){
        submitbutton.removeAttr('disabled');
    }else{
        submitbutton.attr('disabled','disabled');
    }


}

