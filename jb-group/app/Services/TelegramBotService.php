<?php


namespace App\Services;


use App\Helpers\Telegram\Bot;
use App\Helpers\Telegram\Contacts;
use App\Helpers\Telegram\Tires;
use Illuminate\Support\Str;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotService
{
    protected const DEFAULT_MESSAGE = 'Привет! Я бот F7. Нахожу запчасти и шины для вашего автомобиля.\nЧтобы подобрать шины на свою машину, выберите удобный для вас способ, по характеристикам или по марке автомобиля.';

    protected const DEFAULT_KEYBOARD = [
        [['text' => 'Шины', 'callback_data' => 'tires']],
        [['text' => 'Контакты', 'callback_data' => 'contacts']],
        [['text' => 'Акции', 'callback_data' => 'actions']],
    ];

    public function botCalledQuery(Bot $bot, $command, $command_raw, $chat_id, $message_id, $message,  $message_text = '', $need_send = false) {

        switch ($command) {

            case 'start':
                $answer = "Привет! Я бот F7. Нахожу запчасти и шины для вашего автомобиля.\nЧтобы подобрать шины на свою машину, выберите удобный для вас способ, по характеристикам или по марке автомобиля.";

                if ($bot->isWhatsapp()) {
                    $answer .= "\n*Я могу распознать только команду, написанную как слэш (/), а потом цифра*. Например: */1*";
                }

                $bot->updateMessage($chat_id, $message_id, $answer, self::DEFAULT_MESSAGE, true, $need_send);

                break;

            /**
             * Проводимые компанией акции
             */
            case 'actions':

                $result = $bot->cached('actions', function() use ($bot){
                    return $bot->callApi->getActions();
                });

                $bot->sendLinks($chat_id, 'Активные акции и предложения', $result['result'], function () use ($bot, $chat_id){

                    $answer = 'Меню';
                    $bot->sendMessage($chat_id, $answer, null, false, null, [
                        [['text' => 'В меню', 'callback_data' => 'start']]
                    ]);
                });

                break;

            /**
             * Вывод контактов
             */
            case 'contacts':

                $answer = 'Выберите город:';
                $realCity = '';

                if(Str::contains($command, ':')) {
                    [$command, $realCity] = explode(':', $command_raw);
                }

                $result = $bot->callApi->getContacts();
                $buttons = [];
                $back = 'start';

                $res = [
                    'Алматы' => $result['result']['Алматы'],
                    'Нур-Султан' => $result['result']['Нур-Султан']
                ];

                unset($result['result']['Алматы'], $result['result']['Нур-Султан']);

                ksort($result['result']);
                foreach ($result['result'] as $k => $v) {
                    $res[$k] = $v;
                }
                foreach ($res as $city => $rows) {
                    if (!empty($realCity)) {
                        if ($city == $realCity) {
                            $answer = 'Филиал F7 в городе <b>'.$city.'</b>'."\n\n";
                            if (!empty($rows)) {
                                foreach ($rows as $company_name => $info) {
                                    $answer .= preg_replace('/\s*Город: ([^\s{2}]+)/i', '<b>'.$company_name."</b>", $info['info'])."\n";
                                    $answer .= "Режим работы:".$info['schedule']."\n\n";
                                }
                            } else {
                                $answer .= "В выбранном городе представители не найдены";
                            }

                            $answer = str_replace(['   ','&nbsp;','&quot;'], ["\n",' ','"'], $answer);
                            $answer = preg_replace_callback('/\+\d\s*(\(?\d{3}\)?)(-|\s*)\s*(\d{3})\s*(\d{2})(-|\s*)(\d{2})/', function($match){
                                return '<a href="https://wa.me/'.str_replace(['(',')', ' ', '-'], '', $match[0]).'">'.$match[0].'</a>';
                            }, $answer);

                            $back = 'contacts';
                            break;
                        }
                    } else {
                        $buttons[] = [['text' => $city, 'callback_data' => 'contacts:'.$city]];
                    }
                }

                $buttons[] = [['text' => 'Назад', 'callback_data' => $back]];
                if ($back == 'contacts') {
                    $buttons[count($buttons)-1][] = ['text' => 'В меню', 'callback_data' => 'start'];
                }
                $bot->updateMessage($chat_id, $message_id, $answer, $buttons, true, $need_send);
                break;

            /*
              Выбор шин
            */
            case 'tires':
                $answer = 'Пожалуйста выберите из списка подбор';
                $bot->updateMessage($chat_id, $message_id, $answer, [
                    [['text' => 'Выбор по характеристикам', 'callback_data' => 'tires_char']],
                    [['text' => 'Выбор по авто', 'callback_data' => 'tires_car']],
                    [['text' => 'Назад', 'callback_data' => 'start']],
                ], true, $need_send);

                break;

            /**
             * Голосование кнопки будут выводиться после вывода списка каталога
             */
            case 'vote':

                [,$vote_id] = explode(':', $command_raw);
                $bot->setVote($chat_id, [
                    1 => $chat_id,
                    2 => $vote_id,
                    3 => $bot->handler == 'whatsapp' ? 3 : 4
                ]);
                $answer = $vote_id == 2 ? 'Не можете найти подходящий товар? Задайте вопрос нашему специалисту <a href="tel:7210">7210</a> (бесплатно с мобильного)' : 'Спасибо что воспользовались нашим сервисом! Ждем вас снова на Formula7.';
                $bot->sendMessage($chat_id, $answer, 'HTML', false, null, [
                    [
                        ['text' => 'В меню', 'callback_data' => 'start'],
                    ]
                ]);
                break;

            /**
             * Выбор по характеристикам
             */
            case 'tires_car':

                // Парсим команду
                [$command_raw, $check_count, $count_cases] = $bot->parseForInlineCommand($command_raw, $chat_id);

                $buttons = [];
                switch ($count_cases) {

                    // Если это выбор марки авто
                    case 1:
                        $result = $bot->callApi->getTiresMark();

                        $command_raw = 'tires_car';
                        $buttons = $bot->generateButtons($result, $command_raw, 2);
                        $command_raw = 'tires';
                        $answer = 'Выберите авто из списка';
                        break;

                    // Если нужно выбрать модель
                    case 2:

                        //$bot->setStats(14, $chat_id);

                        // Получаем марки машин
                        $result = $bot->cached($bot->cacheGet('backLink'.$chat_id.'2'), function($key) use($bot) {
                            return $bot->callApi->getTiresModel($key);
                        });

                        // Собираем ответ
                        $buttons = $bot->generateButtons($result, $command_raw);

                        $answer = 'Выберите модель автомобиля';

                        break;

                    // Выбор года машины
                    case 3:
                        // Получаем год автомобилей
                        $result = $bot->cached($bot->cacheGet('backLink'.$chat_id.'3'), function($key) use($bot) {
                            return $bot->callApi->getTiresYear($key);
                        });

                        // Собираем ответ
                        $buttons = $bot->generateButtons($result, $command_raw);

                        $answer = 'Выберите год автомобиля';

                        break;

                    // Выбор модификации
                    case 4:
                        $result = $bot->cached($bot->cacheGet('backLink'.$chat_id.'4'), function($key) use($bot) {
                            return $bot->callApi->getTiresModify($key);
                        });

                        // Собираем ответ
                        $buttons = $bot->generateButtons($result, $command_raw);

                        $answer = 'Выберите модификацию Вашего автомобиля';
                        break;

                    // Выдача результата оригинал или замена
                    case 5:


                        $result = $bot->cached($bot->cacheGet('backLink'.$chat_id.'5'), function($key) use ($bot){
                            return $bot->callApi->getTiresCharacters($key);
                        });


                        // Собираем ответ
                        $buttons = [];
                        foreach ($result as $row) {
                            $size = $row['width'].' /'.$row['height'].' '.$row['radius'];
                            $buttons[] = [['text' => 'Размер: '.$size.' - '.$row['title'], 'callback_data' => $command.':1:2:3:'.$check_count[4].':'.$row['width'].'x'.$row['height'].'x'.$row['radius']]];
                        }

                        $answer = 'Подходящие результаты:';
                        $buttons[] = [['text' => 'Назад', 'callback_data' => $command_raw.':back'],['text' => 'В меню', 'callback_data' => 'start']];
                        return $bot->updateMessage($chat_id, $message_id, $answer, $buttons, true, $need_send);
                        break;

                    // Поиск шин по каталогу
                    case 6:

                        //$bot->setStats(18, $chat_id);

                        [$width, $height, $radius] = explode('x', $check_count[5]);
                        $result = $bot->callApi->getTiresItems('light', $width, $height, 'R'.$radius, 'summer');

                        $buttons[] = [['text' => 'Назад', 'callback_data' => $bot->getCallBackCommand($command_raw.':back')], ['text' => 'В меню', 'callback_data' => 'start']];

                        if (empty($result['items'])) {
                            return $bot->sendLinks($chat_id, 'По вашему запросу ничего не найдено', []);
                        }

                        // Добавляем голосовалку
                        $bot->addQueueForVote($chat_id);
                        // Отправляем найденные результаты пользователю
                        return $bot->sendLinks($chat_id, 'Найденные товары', $result['items'], function () use ($bot, $buttons, $chat_id) {

                            // В вацапе происходит баг в отправке сообщения.
                            // Когда сообщения ещё не отправлены. Может вылететь сообщение которое должно быть отправлено последним
                            $bot->sendMessage($chat_id, 'Меню', null, false, null, $buttons);
                        });

                        break;

                }

                $buttons[] = [['text' => 'Назад', 'callback_data' => $command_raw.':back']];
                return $bot->updateMessage($chat_id, $message_id, $answer, $buttons, true, $need_send);

                break;

            /*
             Выбор шин по автомобилю
             */
            case 'tires_char':

                // Парсим команду
                [$command_raw, $check_count, $count_cases] = $bot->parseForInlineCommand($command_raw);


                $buttons = [];
                switch ($count_cases) {
                    case 1:
                        $answer = 'Выберите тип шин:';
                        $buttons = [
                            [['text' => 'Легковые шины', 'callback_data' => $command_raw.':light']],
                            // [['text' => 'Коммерческие шины', 'callback_data' => $command_raw.':commerc']],
                            // [['text' => 'Грузовые шины', 'callback_data' => $command_raw.':truck']],
                            // [['text' => 'Шины для спецтехники', 'callback_data' => $command_raw.':industrial']],
                        ];
                        $command_raw = 'tires';
                        break;

                    case 2:
                        $answer = 'Выберите сезонность:';
                        $buttons = [
                            [['text' => 'Всесезонная', 'callback_data' => $command_raw.':allseason']],
                            [['text' => 'Лето', 'callback_data' => $command_raw.':summer']],
                            [['text' => 'Зима', 'callback_data' => $command_raw.':winter']],
                        ];
                        break;

                    /**
                     * Ширина покрышки
                     */
                    case 3:
                        $answer = 'Выберите ширину покрышки';
                        $result = [
                            'light' => [0,10,11,12,135,145,155,165,175,185,195,205,215,225,235,245,255,265,275,285,295,305,'30X',315,'31X',325,'32X','33X',385,9],
                            'commerc' => ['155', '175', '185', '195', '205', '215', '225', '235', '255'],
                            'truck' => ['1', '10', '11', '12', '13', '215', '235', '245', '265', '275', '285', '295', '315', '355', '385', '435', '445', '8.25'],
                            'industrial' => ['10', '11', '12', '12.5', '13', '14', '15', '15.5', '16', '16.9', '17.5', '175', '18', '18.4', '20.5', '21', '23.5', '250', '26.5', '300', '4', '4.50', '400', '405', '5', '6', '7', '8', '8.15', '8.25', '8.3', '9']
                        ];

                        $result = array_combine($result[$check_count[1]], $result[$check_count[1]]);
                        $buttons = $bot->generateButtons($result, $command_raw);
                        break;

                    /**
                     * Высота покрышки
                     */
                    case 4:
                        $answer = 'Выберите высоту покрышки';
                        $result = [
                            'light' => [ '10.50', '11.5', '12.5', '30', '31', '32', '33', '35', '37', '40', '45', '50', '55', '60', '65', '70', '75', '80', '85', '9.50', '90'],
                            'commerc' => ['60', '65', '70', '75'],
                            'truck' => ['00', '1', '45', '50', '55', '60', '65', '70', '75', '80'],
                            'industrial' => ['00', '16', '18', '21', '23', '28', '70', '80']
                        ];
                        $result = array_combine($result[$check_count[1]], $result[$check_count[1]]);
                        $buttons = $bot->generateButtons($result, $command_raw);
                        break;

                    /**
                     * Радиус покрышки
                     */
                    case 5:
                        $answer = 'Выберите радиус';
                        $result = [
                            'light' => [ 'R', 'R12', 'R13', 'R14', 'R15', 'R15C', 'R16', 'R16C', 'R17', 'R17.5', 'R18', 'R19', 'R20', 'R21', 'R22', 'R22.5', 'ZR15C', 'ZR17', 'ZR18', 'ZR19', 'ZR20'],
                            'commerc' => ['R12C', 'R13C', 'R14C', 'R15C', 'R16C'],
                            'truck' => ['11', '22.5', 'R15', 'R16', 'R16LT', 'R17.5', 'R19.5', 'R20', 'R22.5'],
                            'industrial' => ['-15', '10', '12', '14', '15', '16', '16.5', '17.5', '18', '19.5', '20', '22.5', '24', '25', '28', '8', '9', 'R25']
                        ];

                        $result = array_combine($result[$check_count[1]], $result[$check_count[1]]);
                        $buttons = $bot->generateButtons($result, $command_raw);
                        break;

                    case 6:

                        //$bot->setStats(18, $chat_id);

                        [,$catalog,$season, $width, $height, $radius] = $check_count;

                        $result = $bot->callApi->getTiresItems($catalog, $width, $height, str_replace('.', '', $radius), $season);

                        $buttons[] = [['text' => 'Назад', 'callback_data' => $command_raw.':back'], ['text' => 'В меню', 'callback_data' => 'start']];

                        if (empty($result['items'])) {
                            return $bot->sendMessage($chat_id, 'По вашему запросу ничего не найдено', null, false, null, $buttons);
                        }

                        // Добавляем голосовалку
                        $bot->addQueueForVote($chat_id);
                        return $bot->sendLinks($chat_id, 'Найденные товары', $result['items'], function () use ($bot, $buttons, $chat_id){
                            $bot->sendMessage($chat_id, 'Меню', null, false, null, $buttons);
                        });

                        break;
                }
                $buttons[] = [['text' => 'Назад', 'callback_data' => $command_raw.':back'],['text' => 'В меню', 'callback_data' => 'start']];
                $bot->updateMessage($chat_id, $message_id, $answer, $buttons);

                break;
        }
    }
}
