<?php
    //Подключение шапки
    require_once("header.php");
?>
<!-- Блок для вывода сообщений -->
<div  class="block_for_messages" id="block_for_messages" ></div>
 
<?php
    //Проверяем, если пользователь не авторизован, то выводим форму регистрации, 
    //иначе выводим сообщение о том, что он уже зарегистрирован
    if(!isset($_SESSION["login"]) && !isset($_SESSION["password"]) && !isset($_SESSION["name"])){
?>
        <div id="form_register">
            <h2>Форма регистрации</h2>
 
            <form  method="post" name="form_register" id="form_regist">
                <table>
                    <tbody><tr>
                        <td> Логин: </td>
                        <td>
                            <input type="text" name="login" id="login" placeholder="минимум 6 символов" required="required">
							<span id="valid_login_message" class="mesage_error"></span>
                        </td>
                    </tr>
					
					<tr>
                        <td> Пароль: </td>
                        <td>
                            <input type="password" name="password" placeholder="минимум 6 символов" required="required"><br>
                            <span id="valid_password_message" class="mesage_error"></span>
                        </td>
                    </tr>
					
					<tr>
                        <td> Повторите пароль: </td>
                        <td>
                            <input type="password" name="confirm_password" placeholder="минимум 6 символов" required="required"><br>
                            <span id="valid_confirm_password_message" class="mesage_error"></span>
                        </td>
                    </tr>
              
                    <tr>
                        <td> Email: </td>
                        <td>
                            <input type="email" name="email" required="required"><br>
                            <span id="valid_email_message" class="mesage_error"></span>
                        </td>
                    </tr>
					
					<tr>
                        <td> Имя: </td>
                        <td>
                            <input type="text" name="name" placeholder="2 буквы" required="required"><br>
							<span id="valid_name_message" class="mesage_error"></span>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <input type="button" id="btn_submit_register" value="Зарегистрироваться!">
                        </td>
                    </tr>
                </tbody></table>
            </form>
        </div>
<?php
    }else{
?>
        <div id="authorized">
            <h2>Вы уже зарегистрированы</h2>
        </div>
<?php
    }
     
    //Подключение подвала
    require_once("footer.php");
?>