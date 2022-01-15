<?php
    //Запускаем сессию
    session_start();

    //Добавляем файл подключения к БД
    require_once("dbconnect.php");
    /*
        Проверяем была ли отправлена форма с помощью AJAX. Если да, то идём дальше, если нет, значит пользователь зашёл на эту страницу напрямую. В этом случае выводим ему сообщение об ошибке.
	*/    
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

		// (1) Login

        // Проверяем если в глобальном массиве $_POST существуют данные отправленные из формы и заключаем переданные данные в обычные переменные.
        if(isset($_POST["login"])){
                
			//Обрезаем пробелы с начала и с конца строки
            $login = trim($_POST["login"]);

            //Проверяем переменную на пустоту
            if(!empty($login)){
                // Для безопасности, преобразуем специальные символы в HTML-сущности
                $login = htmlspecialchars($login, ENT_QUOTES);
					
				$reg_login = "(.{6,})";

                //Если формат полученного логин не соответствует регулярному выражению
                if( !preg_match($reg_login, $login)){
                    // Сообщение об ошибке. 
					$result = 'Вы ввели неправильный логин';
                    echo json_encode($result);
						
                    //Останавливаем  скрипт
                    exit();
                }
					
				$z = 0;
				function recurs($arr){
					foreach ($arr as $key => $val) {
						global $login;
						$type = gettype($val);
						if( $type == 'object' || $type == 'array'){
							recurs($val);
						} else if ($key == 'login' && $val == $login){
							global $z;
							$z++;
						}
					}
				}
				recurs($jsonArray);
                    
                //Если кол-во полученных строк ровно единице, значит пользователь с таким логином уже зарегистрирован
                if($z >= 1){
                    //Если полученный результат не равен false
                    if($z != false){
                        // Сообщение об ошибке. 
						$result = 'Пользователь с таким логином уже зарегистрирован';
                    }else{
                        // Сообщение об ошибке. 
						$result = 'Ошибка в запросе к БД';
                    }
					echo json_encode($result);
                    //Останавливаем  скрипт
                    exit();
                }
					
            }else{
                // Сообщение об ошибке. 
				$result = 'Укажите Ваш логин';
				echo json_encode($result);
                //Останавливаем скрипт
                exit();
            }
		   
        }else{
            // Сообщение об ошибке.
			$result = 'Отсутствует поле с логином';
			echo json_encode($result);
               
            //Останавливаем скрипт
            exit();
        }

        // (2) Name
		
        if(isset($_POST["name"])){

            //Обрезаем пробелы с начала и с конца строки
            $name = trim($_POST["name"]);

            if(!empty($name)){
                // Для безопасности, преобразуем специальные символы в HTML-сущности
                $name = htmlspecialchars($name, ENT_QUOTES);
				$reg_name = "/^[a-zA-Z]{2}$/";

                //Если формат полученного имени не соответствует регулярному выражению
                if( !preg_match($reg_name, $name)){
                    // Сообщение об ошибке. 
                    $result = 'Вы ввели неправильное имя';
					echo json_encode($result);
                    //Останавливаем  скрипт
                    exit();
                }
					
            }else{
                // Сообщение об ошибке. 
                $result = 'Укажите Ваше имя';
				echo json_encode($result);
                //Останавливаем  скрипт
                exit();
            }

        }else{
            // Сообщение об ошибке. 
            $result = 'Отсутствует поле с именем';
			echo json_encode($result);
            //Останавливаем  скрипт
            exit();
        }
		
		// (3) Email
		
        if(isset($_POST["email"])){
            //Обрезаем пробелы с начала и с конца строки
            $email = trim($_POST["email"]);

            if(!empty($email)){
                $email = htmlspecialchars($email, ENT_QUOTES);

                //Проверяем формат полученного почтового адреса с помощью регулярного выражения
                $reg_email = "/^[a-z0-9][a-z0-9\._-]*[a-z0-9]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]+/i";

                //Если формат полученного почтового адреса не соответствует регулярному выражению
                if( !preg_match($reg_email, $email)){
                    // Сообщение об ошибке.
                    $result = 'Вы ввели неправельный email';
					echo json_encode($result);
                    //Останавливаем  скрипт
                    exit();
                }

                //Проверяем нет ли уже такого адреса в БД.
				$z = 0;
				function recursion($arr){
					foreach ($arr as $key => $val) {
						global $email;
						$type = gettype($val);
						if( $type == 'object' || $type == 'array'){
							recursion($val);
						} else if ($key == 'email' && $val == $email){
							global $z;
							$z++;
						}
					}
				}
				recursion($jsonArray);
					
                //Если кол-во полученных строк равно единице, значит пользователь с таким почтовым адресом уже зарегистрирован
                if($z >= 1){
                    //Если полученный результат не равен false
                    if(($z) != false){
                        // Сообщение об ошибке.
						$result = 'Пользователь с таким почтовым адресом уже зарегистрирован';
                    }else{
                        // Сообщение об ошибке.
                        $result = 'Ошибка в запросе к БД';
                    }
					echo json_encode($result);
                    //Останавливаем  скрипт
                    exit();
                }
            }else{
                // Сообщение об ошибке. 
                $result = 'Укажите Ваш email';
				echo json_encode($result);
                    
                //Останавливаем  скрипт
                exit();
            }
        }else{
            // Сообщение об ошибке.
            $result = 'Отсутствует поле для ввода Email';
			echo json_encode($result);
                
            //Останавливаем  скрипт
            exit();
        }
			
		// (4) Confirm_password
			
		if(isset($_POST["confirm_password"])){
			//Обрезаем пробелы с начала и с конца строки
            $confirm_password = trim($_POST["confirm_password"]);
			$password = trim($_POST["password"]);

            if(!empty($confirm_password)){
				if($confirm_password === $password){
				
				}else{
					// Сообщение об ошибке.
					$result = 'Пароль и повторный пароль не совпадают';
					echo json_encode($result);
						
					//Останавливаем  скрипт
					exit();
				}
            }else{
                // Сообщение об ошибке. 
                $result = 'Укажите повторно пароль';
				echo json_encode($result);
                    
                //Останавливаем  скрипт
                exit();
            }
        }else{
            // Сообщение об ошибке. 
            $result = 'Отсутствует поле для ввода повторного пароля';
			echo json_encode($result);
               
            //Останавливаем  скрипт
            exit();
        }
            
		// (5) Password
			
        if(isset($_POST["password"])){
            //Обрезаем пробелы с начала и с конца строки
            $password = trim($_POST["password"]);

            if(!empty($password)){
                $password = htmlspecialchars($password, ENT_QUOTES);
				$reg_password = "/^(?=.*[a-z])(?=.*\\d).{6,}$/i";

                //Если формат полученного пароля не соответствует регулярному выражению
                if( !preg_match($reg_password, $password)){
                    // Сообщение об ошибке. 
                    $result = 'Пароль не соответствует требованиям. Пароль должен состоять не менее чем из 6 символов, обязательно должны присутствовать как минимум одна цифра и одна буква';
					echo json_encode($result);
                       
                    //Останавливаем  скрипт
                    exit();
                }	
                //Шифруем пароль
                $password = md5($password."top_secret");
				
            }else{
                // Сообщение об ошибке.
                $result = 'Укажите Ваш пароль';
				echo json_encode($result);
                //Останавливаем  скрипт
                exit();
            }
        }else{
            // Сообщение об ошибке. 
            $result = 'Отсутствует поле для ввода пароля';
			echo json_encode($result);
                
            //Останавливаем  скрипт
            exit();
        }

        // (6) Добавления пользователя в БД

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
    }else{
		exit("<p><strong>Ошибка!</strong> Вы зашли на эту страницу напрямую, поэтому нет данных для обработки. Вы можете перейти на <a href='index.php'> главную страницу </a>.</p>");
    }
	
?>