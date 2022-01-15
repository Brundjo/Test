var result;

$( document ).ready(function() {
    $("#btn_submit_register").click(
		function(){
			sendAjaxForm('block_for_messages', 'form_regist', 'register.php');
			return false; 
		}
	);
});

$( document ).ready(function() {
    $("#btn_submit_auth").click(
		function(){
			sendAjaxForm('block_for_messages', 'form_au', 'auth.php');
			return false; 
		}
	);
});
var result;
function sendAjaxForm(result_form, ajax_form, url) {
    $.ajax({
        url:     url, //url страницы
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: $("#"+ajax_form).serialize(),  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
			result = $.parseJSON(response);
			
			if(result == 1){
				location.reload();	
			}else{
				$('#block_for_messages').html(result);
			}
    	},
    	error: function(response) { // Данные не отправлены
            $('#block_for_messages').html('Ошибка. Данные не отправлены.');
    	}
 	});	
}