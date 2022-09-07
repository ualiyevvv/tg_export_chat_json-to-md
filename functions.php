<?php 


function searchReplyText($id, $messages) {
	
	foreach ($messages as $message):
		if($message['id'] == $id){
			return nl2br($message['text']);
		}
	endforeach;
}
function pr($data) {
	echo "<pre>";
	var_dump($data);
	echo "</pre>";
	echo "<hr>";
}
function getArticles($file): array{
	return json_decode(file_get_contents($file),TRUE);  
}

function checkOnceDate($id, $messages, $current_date) {
	foreach ($messages as $message):
		if($message['id'] == $id-1){
			$date = explode("T", $message["date"]);
			if($date[0] == $current_date){
				return true;
			}else {
				return false;
			}
			
		}
	endforeach;
}

// функция записи данных в файл markdown
function writeToMd($simple_m) {
	$mytext = '';
	$fp = fopen('thoughts.md', 'w');
	
	foreach($simple_m as $message):
		//подготовка файлов медиа
		$file =  explode('/', $message["file"])[1]; //разделяем и берем filename.* 
		$photo =  explode('/', $message["photo"])[1]; //разделяем и берем filename.* 
		// если текст был переслан
		if ($message["forwarded_from"]){
			$forwarded = " (forwarded from: ". $message["forwarded_from"].")";
		}else {
			$forwarded = '';
		}
		//готовим ответы
		$reply = '';
		$reply_id = $message['reply_to_message_id']; // если присутствуют ответы
		if($message['reply_to_message_id']){ 
			$reply = 'in reply to <a href="#'. $reply_id .'">this message</a><br>';
			$reply_text = searchReplyText($reply_id, $simple_m); // находим текст на который отвечают
			$reply .= $reply_text; // собираем ответ
		}
		//готовим дату и время
		$flagDate = false; //устанавливаем флаг для определения проверки даты
		$date = explode("T", $message["date"]); //разделяем на дату и время по отдельности
		$flagDate = checkOnceDate($message['id'], $simple_m, $date[0]); //проверяем текущую и предыдущую даты


		//-----------вывод даты-------------------
		if( !$flagDate ):
			$mytext .= "# {$date[0]}\n"; 
		endif;

		//-----------вывод времени----------------
		$mytext .= "{$date[1]}: {$message["from"]}\n"; 
		
		//-----------вывод ответа-----------------
		if($reply != ''):		
			$mytext .= "> {$reply}\n\n";
		endif;

		//-----------вывод текста-----------------
		$mytext .= nl2br($message["text"]) . $forwarded . "\n"; 

		//-----------вывод медиа------------------
		if($message["photo"]):  
			$mytext .= "![[$photo]]\n";
		endif;
		if($message["file"]):  
			$mytext .= "![[$file]]\n";
		endif;

		//-----------вывод разделителя------------
		$mytext .= "\n --- \n";
		
	endforeach;

	$test = fwrite($fp, $mytext); // Запись в файл
	if ($test) echo 'Данные в файл успешно занесены.';
	else echo 'Ошибка при записи в файл.';
	fclose($fp); //Закрытие файла	
}


//тестим запись в файл
function testTxt(){
	$fp = fopen("counter.txt", "w"); // Открываем файл в режиме записи
	$mytext = "Мне нравится семейство цитрусов\nых. А вообще чай с лимоно и мандарины напоми\nнают новый год. 2021 я встретил без чая с лимоно, что конечно я хочу делать\n \nнапротяжении следущих лет, вместо этого я выпил пару стопок водки, которая лежала в \nхолодильнике. И сейчашний я уже другой, после того вечера мое отношение\n"; // Исходная строка
	$test = fwrite($fp, $mytext); // Запись в файл
	if ($test) echo 'Данные в файл успешно занесены.';
	else echo 'Ошибка при записи в файл.';
	fclose($fp); //Закрытие файла	
}