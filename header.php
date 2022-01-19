<?php
    //Запускаем сессию
    session_start();
?>
 
<!DOCTYPE html>
<html>
    <head>
        <title>Название нашего сайта</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                "use strict";
				//================ Проверка логина ==================
                var login = $('input[name=login]');
                 
                login.blur(function(){
                    if(login.val() != ''){
                        //Если длина введенного логина меньше шести символов, то выводим сообщение об ошибке
                        if(login.val().length < 6){
                            //Выводим сообщение об ошибке
                            $('#valid_login_message').text('Минимальная длина логина 6 символов');
                        }else{
                            // Убираем сообщение об ошибке
                            $('#valid_login_message').text('');
                        }
					}else{
                        $('#valid_login_message').text('Введите логин');
                    }
                });
				
                //================ Проверка email ==================
         
                //регулярное выражение для проверки email
                var pattern_mail = /^[a-z0-9][a-z0-9\._-]*[a-z0-9]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]+/i;
                var mail = $('input[name=email]');
                 
                mail.blur(function(){
                    if(mail.val() != ''){
         
                        // Проверяем, если введенный email соответствует регулярному выражению
                        if(mail.val().search(pattern_mail) == 0){
                            // Убираем сообщение об ошибке
                            $('#valid_email_message').text('');
                        }else{
                            //Выводим сообщение об ошибке
                            $('#valid_email_message').text('Неправильный Email');
                        }
                    }else{
                        $('#valid_email_message').text('Введите Ваш email');
                    }
                });
         
                //================ Проверка валидности пароля ==================
				var pattern_password = /^(?=.*[a-z])(?=.*\d)\w{6,}$/i;
                var password = $('input[name=password]');
                 
                password.blur(function(){
                    if(password.val() != ''){
         
                        //Если длина введенного пароля меньше шести символов, то выводим сообщение об ошибке
                        if(password.val().length < 6){
                            //Выводим сообщение об ошибке
                            $('#valid_password_message').text('Минимальная длина пароля 6 символов');
                        }else{
                            // Убираем сообщение об ошибке
                            $('#valid_password_message').text('');
                        }
						
						// Проверяем, если введенный пароль соответствует регулярному выражению
                        if(password.val().search(pattern_password) == 0){
                            // Убираем сообщение об ошибке
                            $('#valid_password_message').text('');
                        }else{
                            //Выводим сообщение об ошибке
                            $('#valid_password_message').text('Состоять должен из цифр и букв. Обязательно должны присутствовать как минимум одна цифра и одна буква');
						}
                    }else{
                        $('#valid_password_message').text('Введите пароль');
                    }
                });
				
				//================ Проверка валидности повторного пароля ==================
				var pattern_confirm_password = /^(?=.*[a-z])(?=.*\d)\w{6,}$/i;
                var confirm_password = $('input[name=confirm_password]');
                 
                confirm_password.blur(function(){
                    if(confirm_password.val() != ''){
         
                        //Если длина введенного пароля меньше шести символов, то выводим сообщение об ошибке
                        if(confirm_password.val().length < 6){
                            //Выводим сообщение об ошибке
                            $('#valid_confirm_password_message').text('Пароли не совпадают');
                        }else{
                            // Убираем сообщение об ошибке
                            $('#valid_confirm_password_message').text('');
                        }
						
						// Проверяем, если введенный пароль соответствует регулярному выражению
                        if(confirm_password.val().search(pattern_confirm_password) == 0){
                            // Убираем сообщение об ошибке
                            $('#valid_confirm_password_message').text('');
                        }else{
                            //Выводим сообщение об ошибке
                            $('#valid_confirm_password_message').text('Пароли не совпадают');
						}
                    }else{
                        $('#valid_confirm_password_message').text('Введите повторно пароль');
                    }
                });
				
				//================ Проверка валидности имени ==================
                var name = $('input[name=name]');
                 
                name.blur(function(){
                    if(name.val() != ''){
         
                        //Если длина введенного пароля меньше шести символов, то выводим сообщение об ошибке
                        if(name.val().length != 2){
                            //Выводим сообщение об ошибке
                            $('#valid_name_message').text('Имя должно состоять из двух букв');
                        }else{
                            // Убираем сообщение об ошибке
                            $('#valid_name_message').text('');
                        }
					}else{
                        $('#valid_name_message').text('Введите имя');
                    }
                });
            });
        </script>
		<script src="ajax.js"></script>
    </head>
    <body>
 
        <div id="header">
            <h2>Шапка сайта</h2>
 
            <a href="index.php">Главная</a>
			<a href="secondary.php">Не главная</a>
 
            <div id="auth_block">
            <?php
                //Проверяем авторизован ли пользователь
                if(!isset($_SESSION['login']) && !isset($_SESSION['password']) && !isset($_SESSION['name'])){
                    // если нет, то выводим блок с ссылками на страницу регистрации и авторизации
            ?>
                    <div id="link_register">
                        <a href="form_register.php">Регистрация</a>
                    </div>
             
                    <div id="link_auth">
                        <a href="form_auth.php">Авторизация</a>
                    </div>
            <?php
                }else{
                    //Если пользователь авторизован, то выводим ссылку Выход
            ?> 
                    <div>
						Hello <?php echo $_SESSION['name']; ?>
					</div>
					<div id="link_logout">
                        <a href="logout.php">Выход</a>
                    </div>
            <?php
                }
            ?>
            </div>
             <div class="clear"></div>
        </div>