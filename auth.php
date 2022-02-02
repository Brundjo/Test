<?php
    //Запускаем сессию
    session_start();

    //Добавляем файл подключения к БД
    require_once("dbconnect.php");
	
	class User {
		
		public function __construct(){
			$this->login = trim($_POST["login"]);
			$this->password = trim($_POST["password"]);
			$this->name = null;
			$this->z = 0;
		}
	}
	
	class Message {
		
		public static function checks(){
			global $jsonArray;
			
		//Проверяем была ли отправлена форма с помощью AJAX. Если да, то идём дальше, если нет, значит пользователь зашёл на эту страницу напрямую. В этом случае выводим ему сообщение об ошибке.
			
			if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				exit("<p><strong>Ошибка!</strong> Вы зашли на эту страницу напрямую, поэтому нет данных для обработки. Вы можете перейти на <a href='index.php'> главную страницу </a>.</p>");
			}
			
			if (!isset($_POST["login"]) || !isset($_POST["password"])) {
				// Сообщение об ошибке. 
				$result = 'Отсутствует поле для ввода данных';
				echo json_encode($result);
				//Останавливаем скрипт
				exit();
			}
			
			$user = new User();
			
		//(1) Login
		
			if (empty($user->login)) {
				// Сообщение об ошибке. 
				$result = 'Поле для ввода логина не должна быть пустым.';
				echo json_encode($result);
				//Останавливаем скрипт
				exit();
			}
			
			if (!preg_match("(\S{6,})", $user->login)) {
				// Сообщение об ошибке. 
				$result = 'Вы ввели неправильный логин.';
				echo json_encode($result);
				//Останавливаем  скрипт
				exit();
			}
			
		//(2) Password	
			
			if (empty($user->password)) {
				// Сообщение об ошибке. 
				$result = 'Укажите Ваш пароль';
				echo json_encode($result);
				//Останавливаем скрипт
				exit();
			}
			
			Db::read($user, $user->password, $jsonArray);
		}
	}
		
	class Db {
		
		public static function recursion($arr, $user){
			foreach ($arr as $key){
				$login = $user->login;
				$password = $user->password;
				if ($key["login"] == $login && $key["password"] == $password){
					$user->z++;
					$user->name = $key["name"];
				} 
			}
		}
		
		public static function read($user, $pass, $jsonArray){
			//Шифруем пароль
			$user->password = md5($pass."top_secret");
			
			Db::recursion($jsonArray, $user);
			
            //Проверяем, если в базе нет пользователя с такими данными, то выводим сообщение об ошибке
            if($user->z == 1){
                // Если введенные данные совпадают с данными из базы, то сохраняем логин, пароль и имя в массив сессий.
                $_SESSION['login'] = $user->login;
                $_SESSION['password'] = $user->password;
				$_SESSION['name'] = $user->name;
				
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
	}
	
	Message::checks();

?>