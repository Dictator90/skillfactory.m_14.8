<?php

function processAuth()
{
    global $isAuth;
    if ($isAuth)
        header( 'Refresh: 2; url=index.php' );
}

function getHeader($params)
{
    $incCss = false;
    $incJs = false;
    $title = '';

    if (key_exists('css', $params) && $params['css'] === true)
        $incCss = true;

    if (key_exists('js', $params) && $params['js'] === true)
        $incJs = true;

    $title = $params['title'] ?: '';

    require_once ('include/header.php');
}

function getUsersList()
{
    $fileName = $_SERVER['DOCUMENT_ROOT'] . '/db/users.txt';
    if (file_exists($fileName)) {
        $fileContent = file_get_contents($fileName);
        $arFileContent = explode(PHP_EOL, $fileContent);

        if (empty($arFileContent))
            return false;

        $arUsers = [];
        foreach ($arFileContent as $str) {
            if (strlen(trim($str)) === 0) continue;

            $ar = explode(':', $str);
            $arUsers[trim($ar[0])] = trim($ar[1]);
        }

        if (empty($arUsers))
            return false;

        return $arUsers;
    } else {
        return false;
    }
}

/*
 * проверяет, существует ли пользователь с указанным логином
 */
function existsUser($login)
{
    $login = trim($login);
    if (strlen($login) === 0)
        return false;

    $arUsers = getUsersList();
    if (!$arUsers)
        return false;

    if (isset($arUsers[$login]) && !empty($arUsers[$login]))
        return true;
    else
        return false;
}

/*
 * пусть возвращает 'true' тогда, когда существует пользователь с указанным логином
 * и введенный им пароль прошел проверку, иначе — 'false'
 */
function checkPassword($login, $password)
{
    $login = trim($login);
    if (strlen($login) === 0 || strlen($password) === 0)
        return false;

    if (!existsUser($login))
        return false;

    $arUsers = getUsersList();
    if (!$arUsers)
        return false;

    $md5_pass = md5($password . 'custom_salt');

    if ($arUsers[$login] === $md5_pass)
        return true;
    else
        return false;
}

/*
 * возвращает либо имя вошедшего на сайт пользователя, либо null
 */
function getCurrentUser()
{
    return $_SESSION['login'] ?? null;
}

/*
 * добавляет нового пользователя
 */
function addNewUser($login, $password)
{
    $md5_pass = md5($password . 'custom_salt');
    $user = $login . ':' . $md5_pass . PHP_EOL;
    return file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/db/users.txt', $user, FILE_APPEND | LOCK_EX);
}

/*
 * Возвращает комбинацию число + склонение слова в зависимости от числа
 */
function numWord($value, $arWords)
{
    $num = $value % 100;
    if ($num > 19) {
        $num = $num % 10;
    }

    $out = $value . ' ';
    switch ($num) {
        case 1:  $out .= $arWords[0]; break;
        case 2:
        case 3:
        case 4:  $out .= $arWords[1]; break;
        default: $out .= $arWords[2]; break;
    }

    return $out;
}

/*
 * Возвращает сообщение по ДР
 */
function getMsgBirthday($days) {
    $days = intval($days);
    if ($days === 0) {
        return '<font color="green">У тебя сегодня день рождения! Лови скидки!</font>';
    } else {
        return 'До твоего дня рождения осталось ' . numWord($days,['день', 'дня', 'дней']);
    }
}

/*
 * сохраняет данные сессии в соответствующий логину файл
 */
function saveSessionData()
{
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/db/session_${_SESSION['login']}.txt" , serialize($_SESSION));
}

function getSessionData($login)
{
    $fileName = $_SERVER['DOCUMENT_ROOT'] . "/db/session_$login.txt";
    if (file_exists($fileName))
        return unserialize(file_get_contents($fileName));
    else
        return false;
}

session_start();

// аутентификация и авторизация
if (!empty($_POST['auth']))
{
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $arMsg = [];

    if (empty($login))
        $arMsg['error'][] = 'Не введен логин';

    if (empty($password))
        $arMsg['error'][] = 'Не введен пароль';

    if (empty($arMsg['error']))
    {
        if (existsUser($login))
        {
            if (checkPassword($login, $password))
            {
                $_SESSION['auth'] = true;
                $_SESSION['auth_time'] = time();
                $_SESSION['login'] = $login;

                $arSession = getSessionData($_SESSION['login']);
                if ($arSession) {
                    $_SESSION = array_merge($_SESSION, $arSession);
                }

                //Счетчик авторизаций
                $count = $_SESSION['login_counter'] ?? 0;
                $count++;
                $_SESSION['login_counter'] = $count;

                $arMsg['success'][] = 'Вы авторизованы и будете перенаправлены на главную страницу через 1 секунду';

                //Перенаправляем на главную через 1 секунду
                //header( 'Refresh: 1; url=index.php' );
            } else {
                $arMsg['error'][] = 'Пользователя с таким логином и паролем не существует';
            }
        } else {
            $arMsg['error'][] = 'Пользователя с таким логином не существует';
        }
    }
}

// регистрация нового пользователя
if (!empty($_POST['register']))
{
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $arErrorMsg = [];

    if (empty($login))
        $arMsg['error'][] = 'Не введен логин';

    if (empty($password))
        $arMsg['error'][] = 'Не введен пароль';

    if (empty($arMsg['error'])) {
        if (!existsUser($login)) {
            addNewUser($login, $password);

            $_SESSION['auth'] = true;
            $_SESSION['auth_time'] = time();
            $_SESSION['login'] = $login;

            //счетчик авторизаций
            $_SESSION['login_counter'] = 1;

            $arMsg['success'][] = 'Вы зарегистрированы и авторизованы, и будете перенаправлены на главную страницу через 1 секунду';

            //Перенаправляем на главную через 1 секунду
            //header( 'Refresh: 1; url=index.php' );
        } else {
            $arMsg['error'][] = 'Такой пользователь уже существует. Используйте другой логин';
        }
    }
}

if (!empty($_POST['birthday']) && !empty($_POST['date']))
{
    $_SESSION['birthdayData']['~birthdayDate'] = $_POST['date'];
    $_SESSION['birthdayData']['birthdayModalShow'] = true;
}

if (isset($_SESSION['birthdayData']['~birthdayDate']))
{
    $birthdayDate = new DateTime($_SESSION['birthdayData']['~birthdayDate']);
    $todayDate = new DateTime(date('Y-m-d'));

    //Приравниваем года дат
    $birthdayDate->setDate(
        $todayDate->format('Y'),
        $birthdayDate->format('m'),
        $birthdayDate->format('d')
    );

    if ($birthdayDate > $todayDate)
        $interval = $birthdayDate->diff($todayDate);
    elseif ($birthdayDate < $todayDate)
        $birthdayDate->add(new DateInterval('P1Y'));

    $interval = $birthdayDate->diff($todayDate);
    $diff = $interval->format('%a');

    $arBData = [
        'birthdayDate' => $birthdayDate,
        'todayDate' => $todayDate,
        'diff' => $diff,
    ];

    $_SESSION['birthdayData'] = array_merge($_SESSION['birthdayData'], $arBData);

    if (intval($diff) === 0)
        $_SESSION['birthdayData']['birthdaySale'] = true;
    else
        $_SESSION['birthdayData']['birthdaySale'] = false;
}

//Выход из ЛК
if (!empty($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
}

global $isAuth;
global $startSaleTimer;
global $isBirthdayModalShow;
global $isBirthdaySale;
global $iLoginCounter;

$isAuth = $_SESSION['auth'] ?? null;

$startSaleTimer = 0;
$isBirthdayModalShow = false;
$isBirthdaySale = false;
$iLoginCounter = 0;

if ($isAuth)
{
    saveSessionData();

    $iLoginCounter = $_SESSION['login_counter'] ?? 0;
    $startSaleTimer = isset($_SESSION['auth_time']) ? intval($_SESSION['auth_time'])+86400-time() : 0;

    $isBirthdayModalShow = $_SESSION['birthdayData']['birthdayModalShow'] ?? false;

    if (
        (isset($_SESSION['birthdayData']['birthdaySale']) && $_SESSION['birthdayData']['birthdaySale'])
        && $iLoginCounter > 1
    ) {
        $isBirthdaySale = true;
    }
}
