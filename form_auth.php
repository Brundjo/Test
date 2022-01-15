<?php
    //Подключение шапки
    require_once("header.php");
?>
<!-- Блок для вывода сообщений -->
<div class="block_for_messages" id="block_for_messages"></div>
 
<?php
    //Проверяем, если пользователь не авторизован, то выводим форму авторизации, 
    //иначе выводим сообщение о том, что он уже авторизован
    if(!isset($_SESSION["login"]) && !isset($_SESSION["password"]) && !isset($_SESSION["name"])){
?>
    <div id="form_auth">
        <h2>Форма авторизации</h2>
        <form method="post" name="form_auth" id="form_au">
            <table>
          
                <tbody><tr>
                    <td> Логин: </td>
                    <td>
                        <input type="text" name="login" id="login" placeholder="минимум 6 символов" required="required"><br>
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
                    <td colspan="2">
                        <input type="button" id="btn_submit_auth" value="Войти">
                    </td>
                </tr>
            </tbody></table>
        </form>
    </div>
 
<?php
    }else{
?>
 
    <div id="authorized">
        <h2>Вы уже авторизованы</h2>
    </div>
 
<?php
    }
?>
 
<?php
    //Подключение подвала
    require_once("footer.php");
?>