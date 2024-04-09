/*$('input').each(function(){

if($(this).after('required')==='required'){



$(this).after('<span class="asterisk">*</span>');


}
});  */

var passField = $('.password');

$('.show-pass').hover(function() {

passField.attr('type','text');

}, function(){
    passField.attr('type','password');


});
$('.confirm').click(function(){

return confirm('are you sure?')


})

