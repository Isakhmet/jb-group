<?php

namespace App\Services;

use App\Helpers\Telegram\Bot;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use TelegramBot\Api\Types\User;

class BotService
{
    protected const DEFAULT_MESSAGE = 'Привет! Я бот F7. Нахожу запчасти и шины для вашего автомобиля. Чтобы подобрать шины на свою машину, выберите удобный для вас способ, по характеристикам или по марке автомобиля.';

    protected const DEFAULT_KEYBOARD = [
        [['text' => 'Шины', 'callback_data' => 'tires']],
        [['text' => 'Диски', 'callback_data' => 'wheels']],
        [['text' => 'Запчасти', 'callback_data' => 'parts']],
        [['text' => 'Контакты', 'callback_data' => 'contacts']],
        [['text' => 'Акции', 'callback_data' => 'actions']],
    ];

    protected const COMMANDS_DESCRIPTION = [
        'actions' => 'Список действущих акций',
        'contacts' => 'Список контактов',
        'tires' => 'Шины',
        'tires_car' => 'Подбор шин по авто',
        'tires_char' => 'Подбор шин по характеристикам',
        'vote' => 'Оценка работы бота',
        'parts' => 'Запчасти',
        'search-sku' => 'Поиск запчастей по артиклу',
        'search-vin' => 'Поиск запчастей по вин коду',
    ];

    private $config = [];

    public function __construct()
    {
        $this->config['handlers'] = config('chat-bot.handlers');
        $this->config['handler'] = 'telegram';
    }

    public function start()
    {
        $bot = new Bot($this->config);

        $bot->command('start', function ($message) use ($bot) {
            $answer = self::DEFAULT_MESSAGE;
            $chatId = $bot->chatId($message);
            //$this->logging($bot, $chatId, $message);
            $bot->lastName($bot->getUsername($message));

            if ($bot->isWhatsapp()) {
                $answer .= "\n\n*Для выбора команды необходимо написать слэш (/), а потом цифру функции, которую хотите выполнить*.\n Например: */1*";
            }

            $keyboard = self::DEFAULT_KEYBOARD;

            if (!$bot->getCache('city')) {
                $cities = $bot->repository->getCities();

                if($cities['success']) {
                    $keyboard = [];

                    foreach ($cities['data'] as $city) {
                        $keyboard[] = [['text' => $city['name'], 'callback_data' => 'cities:'.$city['slug']]];
                    }

                    $answer = 'Выберите свой город';
                }
            }

            $bot->sendMessage($chatId, $answer, null, false, null, $keyboard);
        });

        $bot->callbackQuery(function ($message) use ($bot) {
            $bot->lastName($bot->getUsername($message));
            $chatId = $bot->chatId($message);
            $messageId = $bot->getMessageId($message);
            $commandRaw = trim($bot->getCommand($message));
            $command = explode(':', $commandRaw)[0];
            //$this->logging($message->getFrom(), $commandRaw);
            $this->botCalledQuery($bot, $command, $commandRaw, $chatId, $messageId, $message);
        });

        $bot->on(function ($message) use ($bot){

            if (is_null($message)) {
                return false;
            }

            $bot->lastName($bot->getUsername($message));
            $chatId = $bot->chatId($message);
            $messageId = $bot->getMessageId($message);
            $messageText = $bot->getText($message);
            $commandRaw = '';

            if ($bot->getCache($chatId.'parts-method')) {
                $command = $bot->getCache($chatId.'parts-method');
                $commandRaw = $command.$messageText;
            }

            if (strcmp($messageText, 'start') === 0) {
                $command = 'start';
                $commandRaw = 'start';
            }

            //$this->logging($message->getFrom(), $commandRaw, true);

            if(isset($command)) {
                $this->botCalledQuery($bot, $command, $commandRaw, $chatId, $messageId, $message, $messageText, true);
            }
        });

        $bot->main();
    }

    public function logging($bot, $chatId, $data)
    {
        $bot->sendMessage($chatId, json_encode($data), 'HTML', false, null, );
    }

    public function botCalledQuery(Bot $bot, $command, $commandRaw, $chatId, $messageId, $message,  $messageText = '', $needSend = false) {

        switch ($command) {
            case 'start':
                $answer = self::DEFAULT_MESSAGE;

                if ($bot->isWhatsapp()) {
                    $answer .= "\n*Я могу распознать только команду, написанную как слэш (/), а потом цифра*. Например: */1*";
                }

                $keyboard = self::DEFAULT_KEYBOARD;

                if (!$bot->getCache('city')) {
                    $cities = $bot->repository->getCities();

                    if($cities['success']) {
                        foreach ($cities['data'] as $city) {
                            $citiesKeyboard = [['text' => $city['name'], 'callback_data' => 'cities:'.$city['slug']]];
                        }

                        $keyboard = [$citiesKeyboard];
                        $answer = 'Выберите свой город';
                    }
                }

                $bot->updateMessage($chatId, $messageId, $answer, $keyboard, true, $needSend);

                break;
            case 'cities' :
                $checkCount = explode(':', $commandRaw);

                $bot->setCache('city', $checkCount[1]);
                $answer = self::DEFAULT_MESSAGE;

                if ($bot->isWhatsapp()) {
                    $answer .= "\n*Я могу распознать только команду, написанную как слэш (/), а потом цифра*. Например: */1*";
                }

                $bot->updateMessage($chatId, $messageId, $answer, self::DEFAULT_KEYBOARD, true, $needSend);

                break;
            case 'actions':
                $result = $bot->repository->getActions();

                $bot->sendLinks($chatId, 'Активные акции и предложения', $result['result'], function () use ($bot, $chatId){
                    $answer = 'Меню';
                    $bot->sendMessage($chatId, $answer, null, false, null, [
                        [['text' => 'В меню', 'callback_data' => 'start']]
                    ]);
                });

                break;
            case 'contacts':
                $answer = 'Выберите город:';
                $realCity = '';

                if(Str::contains($commandRaw, ':')) {
                    [$command, $realCity] = explode(':', $commandRaw);
                }

                $result = $bot->repository->getContacts();
                $buttons = [];
                $back = 'start';

                $cities = [
                    'Алматы' => $result['result']['Алматы'],
                    'Астана' => $result['result']['Астана']
                ];

                unset($result['result']['Алматы'], $result['result']['Астана']);

                ksort($result['result']);
                foreach ($result['result'] as $k => $v) {
                    $cities[$k] = $v;
                }
                foreach ($cities as $city => $rows) {
                    if (!empty($realCity)) {
                        if ($city == $realCity) {
                            $answer = 'Филиал F7 в городе <b>'.$city.'</b>'."\n\n";
                            if (!empty($rows)) {
                                foreach ($rows as $companyName => $info) {
                                    $answer .= preg_replace('/\s*Город: ([^\s{2}]+)/i', '<b>'.$companyName."</b>", $info['info'])."\n";
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
                $bot->updateMessage($chatId, $messageId, $answer, $buttons, true, $needSend);
                break;
            case 'tires':
                $bot->updateMessage($chatId, $messageId, 'Пожалуйста выберите из списка подбор', [
                    [['text' => 'Легковые шины', 'callback_data' => 'tires_light']],
                    [['text' => 'Грузовые шины', 'callback_data' => 'tires_truck']],
                    [['text' => 'OTR шины', 'callback_data' => 'tires_otr']],
                    [['text' => 'Назад', 'callback_data' => 'start']],

                ], true, $needSend);

                break;
            case 'tires_light':
                $bot->updateMessage($chatId, $messageId, 'Пожалуйста выберите из списка подбор', [
                    [['text' => 'Выбор по характеристикам', 'callback_data' => 'tires_char']],
                    [['text' => 'Выбор по авто', 'callback_data' => 'tires_car']],
                    [['text' => 'Назад', 'callback_data' => 'start']],
                ], true, $needSend);
                break;
            case 'tires_truck':
                break;
            case 'tires_otr':
                [$commandRaw, $checkCount, $countCases] = $bot->parseForInlineCommand($commandRaw, $chatId);

                $city = $bot->getCache('city');
                $buttons = [];

                break;
            case 'tires_car':
                [$commandRaw, $checkCount, $countCases] = $bot->parseForInlineCommand($commandRaw, $chatId);

                $city = $bot->getCache('city');
                $buttons = [];

                switch ($countCases) {
                    case 1:
                        $result = $bot->repository->getCarFilters($city, []);
                        $buttons = $bot->generateButtons($result['data']['params']['vendor'], $commandRaw);
                        $commandRaw = 'tires_light';
                        $answer = 'Выберите авто из списка';

                        break;
                    case 2:
                        $key = $bot->getCache('backLink'.$chatId.'2');
                        $result = $bot->repository->getCarFilters($city, ['vendor' => $key]);
                        $bot->setCache('prev_'.$chatId.$countCases, json_encode($result['data']['params']));
                        $buttons = $bot->generateShortButtons($result['data']['params']['car'], $commandRaw);
                        $answer = 'Выберите модель автомобиля';

                        break;
                    case 3:
                        $key = $bot->getCache('backLink'.$chatId.'3');
                        $previousData = json_decode($bot->getCache('prev_'.$chatId.$countCases-1), true);
                        $params = [
                            'vendor' => $previousData['vendor'][0],
                            'car' => $previousData['car'][$key]
                        ];
                        $result = $bot->repository->getCarFilters($city, $params);
                        $bot->setCache('prev_'.$chatId.$countCases, json_encode($result['data']['params']));
                        $buttons = $bot->generateButtons($result['data']['params']['year'], $commandRaw);
                        $answer = 'Выберите год автомобиля';

                        break;
                    case 4:
                        $key = $bot->getCache('backLink'.$chatId.'4');
                        $previousData = json_decode($bot->getCache('prev_'.$chatId.$countCases-1), true);
                        $params = [
                            'vendor' => $previousData['vendor'][0],
                            'car' => $previousData['car'][0],
                            'year' => $key,
                        ];
                        $result = $bot->repository->getCarFilters($city, $params);
                        $bot->setCache('prev_'.$chatId.$countCases, json_encode($result['data']['params']));
                        $buttons = $bot->generateButtons($result['data']['params']['modification'], $commandRaw);
                        $answer = 'Выберите модификацию Вашего автомобиля';

                        break;
                    case 5:
                        $key = $bot->getCache('backLink'.$chatId.'5');
                        $previousData = json_decode($bot->getCache('prev_'.$chatId.$countCases-1), true);
                        $params = [
                            'vendor' => $previousData['vendor'][0],
                            'car' => $previousData['car'][0],
                            'year' => $previousData['year'][0],
                            'modification' => $key,
                        ];
                        $result = $bot->repository->getCarFilters($city, $params);
                        $bot->setCache('prev_'.$chatId.$countCases, json_encode($result['data']['params']));
                        $buttons = $bot->generateShortButtons($result['data']['params']['tyres'], $commandRaw, 1, 50);
                        $answer = 'Выберите размеры';

                        break;
                    // Поиск шин по каталогу
                    case 6:
                        $key = $bot->getCache('backLink'.$chatId.'6');
                        $previousData = json_decode($bot->getCache('prev_'.$chatId.$countCases-1), true);

                        $params = [
                            'vendor' => $previousData['vendor'][0],
                            'car' => $previousData['car'][0],
                            'year' => $previousData['year'][0],
                            'modification' => $previousData['modification'][0],
                            'tyres' => $previousData['tyres'][$key],
                        ];
                        $result = $bot->repository->getTyresByCar($city, $params)->json();

                        $buttons[] = [
                            ['text' => 'Назад', 'callback_data' => $bot->getCallBackCommand($commandRaw.':back')],
                            ['text' => 'В меню', 'callback_data' => 'start']
                        ];

                        $result['data']['more-link'] = config('chat-bot.api.front').'categories/tyres?'.http_build_query($params);

                        if (empty($result['data'])) {
                            return $bot->sendLinks($chatId, 'По вашему запросу ничего не найдено', []);
                        }

                        return $bot->sendLinks($chatId, 'Найденные товары', $result['data'], function () use ($bot, $buttons, $chatId) {
                            $bot->sendMessage($chatId, 'Меню', null, false, null, $buttons);
                        });
                }

                $buttons[] = [['text' => 'Назад', 'callback_data' => $commandRaw.':back']];

                return $bot->updateMessage($chatId, $messageId, $answer, $buttons, true, $needSend);
            /*
             Выбор шин по автомобилю
             */
            case 'tires_char':
                [$commandRaw, $checkCount, $countCases] = $bot->parseForInlineCommand($commandRaw, $chatId);
                $buttons = [];
                $city = $bot->getCache('city');

                switch ($countCases) {
                    case 1:
                        $result = $bot->repository->getCharFilters($city);
                        $params = [];

                        foreach ($result['data']['filters'] as $filter) {
                            if(strcmp($filter['slug'], 'sirina-siny') === 0) {
                                foreach ($filter['values'] as $value) {
                                    $params[$value['id']] = $value['slug'];
                                }
                            }
                        }

                        $buttons = $bot->generateShortButtons($params, $commandRaw);
                        $commandRaw = 'tires_light';
                        $answer = 'Выберите ширину шины';

                        break;
                    case 2:
                        $key = $bot->getCache('backLink'.$chatId.'2');
                        $result = $bot->repository->getCharFilters($city, [$key]);
                        $params = [];

                        foreach ($result['data']['filters'] as $filter) {
                            if(strcmp($filter['slug'], 'vysota-siny') === 0) {
                                foreach ($filter['values'] as $value) {
                                    $params[$value['id']] = $value['slug'];
                                }
                            }
                        }

                        $buttons = $bot->generateShortButtons($params, $commandRaw);
                        $answer = 'Выберите высоту шины';

                        break;
                    case 3:
                        $filters = explode(':', $commandRaw);
                        array_shift($filters);

                        $result = $bot->repository->getCharFilters($city, $filters);
                        $params = [];

                        foreach ($result['data']['filters'] as $filter) {
                            if(strcmp($filter['slug'], 'sina-diametr-diska') === 0) {
                                foreach ($filter['values'] as $value) {
                                    $params[$value['id']] = $value['slug'];
                                }
                            }
                        }

                        $buttons = $bot->generateShortButtons($params, $commandRaw);
                        $answer = 'Выберите диаметр';
                        break;
                    case 4:
                        $filters = explode(':', $commandRaw);
                        array_shift($filters);
                        $result = $bot->repository->getCharFilters($city, $filters);
                        $params = [];

                        foreach ($result['data']['filters'] as $filter) {
                            if(strcmp($filter['slug'], 'sezonnost-siny') === 0) {
                                foreach ($filter['values'] as $value) {
                                    $params[$value['id']] = $value['name'];
                                }
                            }
                        }
                        $buttons = $bot->generateShortButtons($params, $commandRaw);
                        $answer = 'Выберите сезонность шины';

                        break;
                    case 5:
                        $filters = explode(':', $commandRaw);
                        array_shift($filters);

                        $result = $bot->repository->getTiresByChar($city, $filters)->json();

                        $buttons[] = [['text' => 'Назад', 'callback_data' => $commandRaw.':back'], ['text' => 'В меню', 'callback_data' => 'start']];

                        if (empty($result['data'])) {
                            return $bot->sendMessage($chatId, 'По вашему запросу ничего не найдено', null, false, null, $buttons);
                        }

                        $query = http_build_query(['filters' => implode(',', $filters)]);
                        $result['data']['more-link'] = config('chat-bot.api.front')."categories/tyres?$query";

                        // Добавляем голосовалку
                        return $bot->sendLinks($chatId, 'Найденные товары', $result['data'], function () use ($bot, $buttons, $chatId){
                            $bot->sendMessage($chatId, 'Меню', null, false, null, $buttons);
                        });
                }

                $buttons[] = [['text' => 'Назад', 'callback_data' => $commandRaw.':back']];
                $bot->updateMessage($chatId, $messageId, $answer, $buttons);

                break;
            case 'wheels':
                $bot->updateMessage($chatId, $messageId, 'Пожалуйста выберите из списка подбор', [
                    [['text' => 'Выбор по характеристикам', 'callback_data' => 'wheels_char']],
                    [['text' => 'Выбор по авто', 'callback_data' => 'wheels_car']],
                    [['text' => 'Назад', 'callback_data' => 'start']],
                ], true, $needSend);

                break;
            case 'wheels_car':
                [$commandRaw, $checkCount, $countCases] = $bot->parseForInlineCommand($commandRaw, $chatId);

                $city = $bot->getCache('city');
                $buttons = [];

                switch ($countCases) {
                    case 1:
                        $result = $bot->repository->getCarFilters($city, [], 2);
                        $buttons = $bot->generateButtons($result['data']['params']['vendor'], $commandRaw);
                        $commandRaw = 'wheels';
                        $answer = 'Выберите авто из списка';

                        break;
                    case 2:
                        $key = $bot->getCache('backLink'.$chatId.'2');
                        $result = $bot->repository->getCarFilters($city, ['vendor' => $key], 2);
                        $bot->setCache('prev_'.$chatId.$countCases, json_encode($result['data']['params']));
                        $buttons = $bot->generateShortButtons($result['data']['params']['car'], $commandRaw);
                        $answer = 'Выберите модель автомобиля';

                        break;
                    case 3:
                        $key = $bot->getCache('backLink'.$chatId.'3');
                        $previousData = json_decode($bot->getCache('prev_'.$chatId.$countCases-1), true);
                        $params = [
                            'vendor' => $previousData['vendor'][0],
                            'car' => $previousData['car'][$key]
                        ];
                        $result = $bot->repository->getCarFilters($city, $params, 2);
                        $bot->setCache('prev_'.$chatId.$countCases, json_encode($result['data']['params']));
                        $buttons = $bot->generateButtons($result['data']['params']['year'], $commandRaw);
                        $answer = 'Выберите год автомобиля';

                        break;
                    case 4:
                        $key = $bot->getCache('backLink'.$chatId.'4');
                        $previousData = json_decode($bot->getCache('prev_'.$chatId.$countCases-1), true);
                        $params = [
                            'vendor' => $previousData['vendor'][0],
                            'car' => $previousData['car'][0],
                            'year' => $key,
                        ];
                        $result = $bot->repository->getCarFilters($city, $params, 2);
                        $bot->setCache('prev_'.$chatId.$countCases, json_encode($result['data']['params']));
                        $buttons = $bot->generateButtons($result['data']['params']['modification'], $commandRaw);
                        $answer = 'Выберите модификацию Вашего автомобиля';

                        break;
                    case 5:
                        $key = $bot->getCache('backLink'.$chatId.'5');
                        $previousData = json_decode($bot->getCache('prev_'.$chatId.$countCases-1), true);
                        $params = [
                            'vendor' => $previousData['vendor'][0],
                            'car' => $previousData['car'][0],
                            'year' => $previousData['year'][0],
                            'modification' => $key,
                        ];
                        $result = $bot->repository->getCarFilters($city, $params, 2);
                        $bot->setCache('prev_'.$chatId.$countCases, json_encode($result['data']['params']));
                        $buttons = $bot->generateShortButtons($result['data']['params']['wheels'], $commandRaw);
                        $answer = 'Выберите размеры';

                        break;
                    // Поиск шин по каталогу
                    case 6:
                        $key = $bot->getCache('backLink'.$chatId.'6');
                        $previousData = json_decode($bot->getCache('prev_'.$chatId.$countCases-1), true);
                        $params = [
                            'vendor' => $previousData['vendor'][0],
                            'car' => $previousData['car'][0],
                            'year' => $previousData['year'][0],
                            'modification' => $previousData['modification'][0],
                            'wheels' => $previousData['wheels'][$key],
                        ];
                        $result = $bot->repository->getWheels($city, $params);

                        $buttons[] = [
                            ['text' => 'Назад', 'callback_data' => $bot->getCallBackCommand($commandRaw.':back')],
                            ['text' => 'В меню', 'callback_data' => 'start']];

                        if (empty($result['data'])) {
                            return $bot->sendLinks($chatId, 'По вашему запросу ничего не найдено', []);
                        }

                        // Добавляем голосовалку
                        // Отправляем найденные результаты пользователю
                        return $bot->sendLinks($chatId, 'Найденные товары', $result['data'], function () use ($bot, $buttons, $chatId) {

                            // В вацапе происходит баг в отправке сообщения.
                            // Когда сообщения ещё не отправлены. Может вылететь сообщение которое должно быть отправлено последним
                            $bot->sendMessage($chatId, 'Меню', null, false, null, $buttons);
                        });

                        break;
                }

                $buttons[] = [['text' => 'Назад', 'callback_data' => $commandRaw.':back']];
                return $bot->updateMessage($chatId, $messageId, $answer, $buttons, true, $needSend);

                break;

            case 'wheels_char':
                [$commandRaw, $checkCount, $countCases] = $bot->parseForInlineCommand($commandRaw, $chatId);

                $city = $bot->getCache('city');
                $buttons = [];

                switch ($countCases) {
                    case 1:
                        $result = $bot->repository->getCharFilters($city, [], 2);
                        $params = [];

                        foreach ($result['data']['filters'] as $filter) {
                            if(strcmp($filter['slug'], 'diametr-diska') === 0) {
                                foreach ($filter['values'] as $value) {
                                    $params[$value['id']] = $value['slug'];
                                }
                            }
                        }

                        $buttons = $bot->generateShortButtons($params, $commandRaw);
                        $commandRaw = 'wheels';
                        $answer = 'Выберите диаметр диска';

                        break;
                    case 2:
                        $key = $bot->getCache('backLink'.$chatId.'2');
                        $result = $bot->repository->getCharFilters($city, [$key], 2);
                        $params = [];

                        foreach ($result['data']['filters'] as $filter) {
                            if(strcmp($filter['slug'], 'pcd-diska') === 0) {
                                foreach ($filter['values'] as $value) {
                                    $params[$value['id']] = $value['slug'];
                                }
                            }
                        }

                        $buttons = $bot->generateShortButtons($params, $commandRaw);
                        $commandRaw = 'wheels';
                        $answer = 'Выберите PCD диска';

                        break;
                    case 3:
                        $filters = explode(':', $commandRaw);
                        array_shift($filters);

                        $result = $bot->repository->getCharFilters($city, $filters, 2);
                        $params = [];

                        foreach ($result['data']['filters'] as $filter) {
                            if(strcmp($filter['slug'], 'diametr-stupicy-diska') === 0) {
                                foreach ($filter['values'] as $value) {
                                    $params[$value['id']] = $value['slug'];
                                }
                            }
                        }

                        $buttons = $bot->generateShortButtons($params, $commandRaw);
                        $commandRaw = 'wheels';
                        $answer = 'Выберите диаметр ступицы диска';
                        break;
                    case 4:
                        $filters = explode(':', $commandRaw);
                        array_shift($filters);
                        $result = $bot->repository->getCharFilters($city, $filters, 2);
                        $params = [];

                        foreach ($result['data']['filters'] as $filter) {
                            if(strcmp($filter['slug'], 'vynos-diska') === 0) {
                                foreach ($filter['values'] as $value) {
                                    $params[$value['id']] = $value['slug'];
                                }
                            }
                        }

                        $buttons = $bot->generateShortButtons($params, $commandRaw);
                        $commandRaw = 'wheels';
                        $answer = 'Выберите вынос диска';

                        break;
                    case 5:
                        $filters = explode(':', $commandRaw);
                        array_shift($filters);
                        $result = $bot->repository->getCharFilters($city, $filters);
                        $params = [];

                        foreach ($result['data']['filters'] as $filter) {
                            if(strcmp($filter['slug'], 'sirina-diska') === 0) {
                                foreach ($filter['values'] as $value) {
                                    $params[$value['id']] = $value['slug'];
                                }
                            }
                        }

                        $buttons = $bot->generateShortButtons($params, $commandRaw);
                        $commandRaw = 'wheels';
                        $answer = 'Выберите ширину диска';

                        break;
                    case 6:
                        $filters = explode(':', $commandRaw);
                        array_shift($filters);
                        $result = $bot->repository->getWheelsByChar($city, $filters);

                        $buttons[] = [['text' => 'Назад', 'callback_data' => $commandRaw.':back'], ['text' => 'В меню', 'callback_data' => 'start']];

                        if (empty($result['data'])) {
                            return $bot->sendMessage($chatId, 'По вашему запросу ничего не найдено', null, false, null, $buttons);
                        }

                        // Добавляем голосовалку
                        return $bot->sendLinks($chatId, 'Найденные товары', $result['data'], function () use ($bot, $buttons, $chatId){
                            $bot->sendMessage($chatId, 'Меню', null, false, null, $buttons);
                        });
                }

                $buttons[] = [['text' => 'Назад', 'callback_data' => $commandRaw.':back']];
                return $bot->updateMessage($chatId, $messageId, $answer, $buttons, true, $needSend);

            case 'vote':
                [,$vote] = explode(':', $commandRaw);
                $answer = $vote == 2 ? 'Не можете найти подходящий товар? Задайте вопрос нашему специалисту <a href="tel:7210">7210</a> (бесплатно с мобильного)'
                    : 'Спасибо что воспользовались нашим сервисом! Ждем вас снова на Formula7.';
                $bot->sendMessage($chatId, $answer, 'HTML', false, null, [
                    [
                        ['text' => 'В меню', 'callback_data' => 'start'],
                    ]
                ]);
                break;

            case 'parts':
                $answer = 'Пожалуйста выберите способ поиска';

                if(Str::contains($commandRaw, ':')) {
                    [$command, $method] = explode(':', $commandRaw);
                }

                if(!isset($method)) {
                    $bot->updateMessage($chatId, $messageId, $answer, [
                        [['text' => 'Поиск по артиклу запчасти', 'callback_data' => 'parts:sku']],
                        [['text' => 'Поиск по ВИН коду', 'callback_data' => 'parts:vin']],
                        [['text' => 'Назад', 'callback_data' => 'start']],
                    ], true, $needSend);

                    break;
                }

                switch ($method) {
                    case 'sku':
                        $answer = 'Пожалуйста введите артикул запчасти';
                        $key = $chatId.'parts-method';
                        $bot->setCache($key, 'search-sku');

                        break;
                    case 'vin':
                        $answer = 'Пожалуйста введите VIN транспорта';
                        $key = $chatId.'parts-method';
                        $bot->setCache($key, 'search-vin');

                        break;
                }

                $bot->updateMessage($chatId, $messageId, $answer, null, true, true);

                break;

            case 'search-sku':
                $result = $bot->repository->getPartsSku($messageText);
                $buttons[] = [['text' => 'В меню', 'callback_data' => 'start']];

                if (empty($result)) {
                    return $bot->sendMessage($chatId, 'По вашему запросу ничего не найдено', null, false, null, $buttons);
                }

                return $bot->sendLinks($chatId, 'Найденные товары', $result, function () use ($bot, $buttons, $chatId){
                    $bot->sendMessage($chatId, 'Меню', null, false, null, $buttons);
                });

                break;

            case 'search-vin':
                $result    = $bot->repository->getPartsVin($messageText);
                $buttons[] = [['text' => 'В меню', 'callback_data' => 'start']];

                if (empty($result['breadcrumbs'])) {
                    return $bot->sendMessage($chatId, 'По вашему запросу ничего не найдено', null, false, null, $buttons);
                }

                $link = 'https://cbc-parts.kz/catalogs/modifications/groups?';

                foreach ($result['breadcrumbs'] as $key => $breadcrumb) {
                    $link .= "$key=$breadcrumb&";
                }

                $text = "Результат пойска по <a href='$link'>ссылке</a>";
                $bot->sendMessage($chatId, $text, 'HTML', false, null, $buttons);

                break;
        }
    }
}
