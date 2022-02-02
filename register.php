<?php
    //Запускаем сессию
    session_start();

    //Добавляем файл подключения к БД
    require_once("dbconnect.php");
	
	class User {
		
		public function __construct(){
			$this->login = trim($_POST["login"]);
			$this->name = trim($_POST["name"]);
			$this->email = trim($_POST["email"]);
			$this->confirm_password = trim($_POST["confirm_password"]);
			$this->password = trim($_POST["password"]);
			$this->z = 0;
			$this->e = 0;
		}
	}
	
	class Message {	
	
		public static function recurs($arr, $user){
			foreach ($arr as $key => $val) {
				$login = $user->login;
				$type = gettype($val);
				if( $type == 'object' || $type == 'array'){
					Message::recurs($val, $user);
				} else if ($key == 'login' && $val == $login){
					$user->z = 1;
				}
			}
		}
				
		public static function recursion($arr, $user){
			foreach ($arr as $key => $val) {
				$email = $user->email;
				$type = gettype($val);
				if( $type == 'object' || $type == 'array'){
					Message::recursion($val, $user);
				} else if ($key == 'email' && $val == $email){
					$user->e = 1;
				}
			}
		}
	
		public static function checks(){
			global $jsonArray;
			
		//Проверяем была ли отправлена форма с помощью AJAX. Если да, то идём дальше, если нет, значит пользователь зашёл на эту страницу напрямую. В этом случае выводим ему сообщение об ошибке.
			
			if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				exit("<p><strong>Ошибка!</strong> Вы зашли на эту страницу напрямую, поэтому нет данных для обработки. Вы можете перейти на <a href='index.php'> главную страницу </a>.</p>");
			}
			
			if (!isset($_POST["login"]) || !isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["confirm_password"]) || !isset($_POST["password"])) {
				// Сообщение об ошибке. 
				$result = 'Отсутствует поле для ввода данных';
				echo json_encode($result);
				//Останавливаем скрипт
				exit();
			}
			
			$user = new User();
			
		// (1) Login
		
			if (empty($user->login)) {
				// Сообщение об ошибке. 
				$result = 'Укажите Ваш логин';
				echo json_encode($result);
				//Останавливаем скрипт
				exit();
			}
			
			if (!preg_match("(\S{6,})", $user->login)) {
				// Сообщение об ошибке. 
				$result = 'Вы ввели неправильный логин. Минимум 6 символов, не должно присутствовать пробельных символов';
				echo json_encode($result);
				//Останавливаем  скрипт
				exit();
			}
			
			Message::recurs($jsonArray, $user);
			if ($user->z >= 1) {
				// Сообщение об ошибке. 
				$result = 'Пользователь с таким логином уже зарегистрирован';
				echo json_encode($result);
				//Останавливаем  скрипт
				exit();
			}
			
		// (2) Name
			
			if (empty($user->name)) {
				// Сообщение об ошибке. 
				$result = 'Укажите Ваше имя';
				echo json_encode($result);
				//Останавливаем скрипт
				exit();
			}
			
			if (!preg_match("/^[a-zA-Z]{2}$/", $user->name)) {
				// Сообщение об ошибке. 
				$result = 'Вы ввели неправильное имя';
				echo json_encode($result);
				//Останавливаем  скрипт
				exit();
			}
			
		// (3) Email
			
			if (empty($user->email)) {
				// Сообщение об ошибке. 
				$result = 'Укажите Ваш email';
				echo json_encode($result);
				//Останавливаем скрипт
				exit();
			}
			
			if (!preg_match("/^[a-z0-9][a-z0-9\._-]*[a-z0-9]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]+/i", $user->email)) {
				// Сообщение об ошибке. 
				$result = 'Вы ввели неправельный email';
				echo json_encode($result);
				//Останавливаем  скрипт
				exit();
			}
			
			Message::recursion($jsonArray, $user);
			if ($user->e >= 1) {
				// Сообщение об ошибке. 
				$result = 'Пользователь с таким почтовым адресом уже зарегистрирован';
				echo json_encode($result);
				//Останавливаем  скрипт
				exit();
			}
			
		// (4) Confirm_password
			
			if (empty($user->confirm_password)) {
				// Сообщение об ошибке. 
				$result = 'Укажите повторно пароль';
				echo json_encode($result);
				//Останавливаем скрипт
				exit();
			}
			
			if($user->confirm_password !== $user->password){
				// Сообщение об ошибке.
				$result = 'Пароль и повторный пароль не совпадают';
				echo json_encode($result);
				//Останавливаем  скрипт
				exit();
			}
			
		// (5) Password
			
			if (empty($user->password)) {
				// Сообщение об ошибке. 
				$result = 'Укажите Ваш пароль';
				echo json_encode($result);
				//Останавливаем скрипт
				exit();
			}
			
			if (!preg_match("/^(?=.*[a-z])(?=.*\\d)\w{6,}$/i", $user->password)) {
				// Сообщение об ошибке. 
				$result = 'Пароль не соответствует требованиям. Пароль должен состоять только из цифр и букв, не менее чем из 6 символов, обязательно должны присутствовать как минимум одна цифра и одна буква';
				echo json_encode($result);
				//Останавливаем  скрипт
				exit();
			}
			
			Db::create($user->login, $user->password, $user->email, $user->name, $jsonArray);
		}
	}
	
	class Db {
	
		public static function create($login, $pass, $email, $name, $jsonArray){
			//Шифруем пароль
			$password = md5($pass."top_secret");
			
			//Запрос на добавления пользователя в БД
			$taskList = [
				'login'=>$login,
				'password'=>$password,
				'email'=>$email,
				'name'=>$name
			];
				
			if ($taskList){
				$jsonArray[] = $taskList;
				$result_insert = file_put_contents('db.json', json_encode($jsonArray, JSON_FORCE_OBJECT));
			}
				
			if(!$result_insert){
				// Сообщение об ошибке.
				$result = 'Ошибка запроса на добавления пользователя в БД';
				echo json_encode($result);
				//Останавливаем  скрипт
				exit();
			}else{
				$result = 'Регистрация прошла успешно!!! Теперь Вы можете авторизоваться используя Ваш логин и пароль.';
				echo json_encode($result);
			}
		}
	}

	Message::checks();
  	
?>