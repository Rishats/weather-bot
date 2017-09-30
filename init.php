<?php

include('vendor/autoload.php');
include('TelegramBot.php');
include('Weather.php');

// Получаем сообщение. 
$TelegramApi = new TelegramBot();

$weatherApi = new Weather();

while (true) {
	
	sleep(2);

	$updates = $TelegramApi->getUpdates();


	// По каждому сообщению пробегаемся.
	foreach ($updates as $update) {
		if(isset($update->message->location)) {

			// Получаем погоду

			$result = $weatherApi->getWeather($update->message->location->latitude, $update->message->location->longitude);

			switch ($result->weather[0]->main) {
				case 'Clear':
					$response = "Clear!";
					break;
				case 'Clouds':
					$response = "Clouds!";
					break;
				case 'Rain':
					$response = "Rain!";
					break;
				default:
					$response = "Не могу ответить точно! Посмотрите пожалуйста сами!";
					break;
			}

			// На каждое сообщение отвечаем
			$TelegramApi->sendMessage($update->message->chat->id, $response);

		} else {
			$TelegramApi->sendMessage($update->message->chat->id, 'Отправьте свою локацию!');
		}
	}
}