<?php
    //Запускаем сессию
    session_start();

    //Добавляем файл подключения к БД
    require_once("dbconnect.php");
    /*
    Проверяем была ли отправлена форма с помощью AJAX. Если да, то идём дальше, если нет, то выведем пользователю сообщение об ошибке, о том что он зашёл на эту страницу напрямую.
    */
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        
        //(1) Login
		
        //Обрезаем пробелы с начала и с конца строки
        $login = trim($_POST["login"]);
        if(isset($_POST["login"])){
             
            if(!empty($login)){
                $login = htmlspecialchars($login, ENT_QUOTES);
             
                //Проверяем формат полученного логина с помощью регулярного выражения
                $reg_login = "(.{6,})";
             
                //Если формат полученного логина не соответствует регулярному выражению
                if( !preg_match($reg_login, $login)){
					// Сообщение об ошибке.
					$result = 'Вы ввели неправильный логин';
                    echo json_encode($result);
                    //Останавливаем скрипт
                    exit();
                }
            }else{
                // Сообщение об ошибке.
				$result = 'Поле для ввода логина не должна быть пустым.';
                echo json_encode($result);
                //Останавливаем скрипт
                exit();
            }
                 
        }else{
            // Сообщение об ошибке. 
			$result = 'Отсутствует поле для ввода логина';
            echo json_encode($result);
            //Останавливаем скрипт
            exit();
        }
             
        //(2) Password
        if(isset($_POST["password"])){
 
            //Обрезаем пробелы с начала и с конца строки
            $password = trim($_POST["password"]);
             
            if(!empty($password)){
                $password = htmlspecialchars($password, ENT_QUOTES);
             
                //Шифруем пароль
                $password = md5($password."top_secret");
            }else{
                // Сообщение об ошибке. 
				$result = 'Укажите Ваш пароль';
				echo json_encode($result);
                //Останавливаем скрипт
                exit();
            }
        }else{
            // Сообщение об ошибке. 
			$result = 'Отсутствует поле для ввода логина';
			echo json_encode($result);
            //Останавливаем скрипт
            exit();
        }

        //(3) Cоставления запроса к БД
        //Запрос в БД на выборке пользователя.
           
		$z = 0;
		$login;
		$name;
			
		function recursion($arr){
			foreach ($arr as $key){
				global $login;
				global $password;
				if ($key["login"] == $login && $key["password"] == $password){
					global $z;
					$z++;
					global $name;
					$name = $key["name"];
				} 
			}
		}
					
		recursion($jsonArray);
			
        if(!$z && $z != 0){
            // Сообщение об ошибке. 
			$result = 'Ошибка запроса на выборке пользователя из БД';
			echo json_encode($result);
            //Останавливаем скрипт
            exit();
        }else{
            //Проверяем, если в базе нет пользователя с такими данными, то выводим сообщение об ошибке
            if($z == 1){
                // Если введенные данные совпадают с данными из базы, то сохраняем логин, пароль и имя в массив сессий.
                $_SESSION['login'] = $login;
                $_SESSION['password'] = $password;
				$_SESSION['name'] = $name;
				
				$result = 1;
				echo json_encode($result);
				
				//Останавливаем скрипт
                exit();
            }else{
                // Сообщение об ошибке.
				$result = 'Неправильный логин и/или пароль';
				echo json_encode($result);
                //Останавливаем скрипт
                exit();
            }
        }
    }else{
        exit("<p><strong>Ошибка!</strong> Вы зашли на эту страницу напрямую, поэтому нет данных для обработки. Вы можете перейти на <a href='index.php'> главную страницу </a>.</p>");
    }