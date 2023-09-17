<?php
include_once ('include/prolog.php');

/* @global $arMsg */
/* @global $isAuth */

processAuth();
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <?php getHeader([
            'css' => true,
            'title' => "Авторизация"
        ]);?>
    </head>
    <body>
        <div class="container">
            <br>
            <div class="row-middle">
                <a href="index.php">На главную</a>
                <h1>Авторизация</h1>
                <?php if (!$isAuth): ?>
                    <?php if (isset($arMsg) && !empty($arMsg['error'])):?>
                        <div class="error-msg"><?=implode('<br>', $arMsg['error'])?></div>
                    <?php endif;?>
                    <?php if (isset($arMsg) && !empty($arMsg['success'])):?>
                        <div class="success-msg"><?=implode('<br>', $arMsg['success'])?></div>
                    <?php endif;?>
                    <form method="POST">
                        <label for="login" title="от 3х до 10ти латинских символов в нижнем регистре">
                            <div>Введите логин</div>
                            <input type="text" name="login" id="login" value="<?= $_POST['login'] ?? '' ?>" pattern="[a-z]{3,10}" required>
                        </label>
                        <label for="password">
                            <div>Введите пароль</div>
                            <input type="password" name="password" id="password" required>
                        </label>
                        <input type="submit" name="auth" value="Авторизоваться">
                    </form>
                    <a href="registration.php">Зарегистрироваться</a>
                <?php else: ?>
                    <div class="success-msg">Вы авторизованы и будете перенаправлены на главную через 2 секунды</div>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>
