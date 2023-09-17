<?php
include_once ('include/prolog.php');
include_once ('include/data/services.php');
/**
 * @global $isAuth
 * @global $startSaleTimer
 * @global $isBirthdaySale
 * @global $isBirthdayModalShow
 * @global $iLoginCounter
 * @var $arServices
 */

$birthdayDiscountPercent = 5;
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <?php getHeader([
            'css' => true,
            'js' => true,
            'title' => "SPA-салон &laquo;RelaxMe&raquo;"
        ]);?>
    </head>
    <body>
        <div class="container">
            <div class="header-line">
                <div class="auth">
                    <?php if ($isAuth):?>
                        <span>Приветствуем тебя, <?=getCurrentUser();?>!
                                <?php if (isset($_SESSION['birthdayData']['diff']) && $iLoginCounter > 1):?>
                                    <?=getMsgBirthday($_SESSION['birthdayData']['diff']);?>
                                    Что бы указать другую дату <a href="javscript:void(0);" onclick="showBirthdayModal('.modal-wrapper');">Жми сюда</a>!
                                <?php endif;?>
                            </span>
                        <a href="?logout=yes">Выйти</a>
                    <?php else:?>
                        <span>Здравствуй, друг! Не желаешь войти?</span>
                        <a href="login.php">Войти</a>
                    <?php endif;?>
                </div>
            </div>

            <section class="banners <?=$isAuth?'show-sale':''?>">
                <div class="banner">
                    <img src="assets/images/main-banner.jpg" alt="banner">
                </div>
                <?php if ($isAuth):?>
                    <div class="sale-wrapper">
                        <div class="sale">
                            <div class="thumb">
                                <img src="assets/images/sale.webp" alt="sale">
                            </div>
                            <div class="notion">Только сейчас и только для тебя!</div>
                            <div class="name">Бархат шоколада</div>
                            <div class="preview">Восхитительная релаксирующая тайская процедура с шоколадным маслом.</div>
                            <div class="price">Цена за 60 мин - 2 900 &#x20bd;</div>

                            <?php if ($startSaleTimer > 0):?>
                                <div class="timer-wrapper">
                                    <span class="timer-title">До окончания акции</span>
                                    <span id="timer" data-starttimer="<?=$startSaleTimer?>">00:00:00</span>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                <?php endif;?>
            </section>
            <section class="services">
                <h2>Наши услуги</h2>
                <div class="items">
                    <?php foreach ($arServices as $key => $arService):?>
                        <?php $curPrice = $isBirthdaySale ? round($arService['price'] - $arService['price']*5/100) : $arService['price'];?>
                        <div class="item-wrapper">
                            <div class="item">
                                <?php if ($isBirthdaySale):?>
                                    <div class="labels">
                                        <span>Скидка <?=$birthdayDiscountPercent?>%</span>
                                    </div>
                                <?php endif;?>
                                <div class="top">
                                    <div class="thumb">
                                        <img src="assets/images/service-<?=$key + 1?>.jpg" alt="<?=$arService['name']?>">
                                    </div>
                                    <div class="name"><?=$arService['name']?></div>
                                    <div class="preview"><?=$arService['preview']?></div>
                                </div>
                                <div class="price-block">
                                    <div class="session">Продолжительность <?=$arService['session']?></div>
                                    <div class="price">
                                        <div class="current-price"><?=number_format($curPrice,0, '.', ' ')?> &#x20bd;</div>
                                        <?php if ($isBirthdaySale):?>
                                            <div class="old-price"><?=number_format($arService['price'],0, '.', ' ')?> &#x20bd;</div>
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
            </section>
            <section class="about">
                <h2>О нас</h2>
                <div class="thumb">
                    <img src="assets/images/about.webp" alt="about">
                </div>
                <div class="preview">
                    <p>
                        Спа салон &laquo;RelaxMe&raquo; — это особая атмосфера удовольствия и комфорта, наполняющая жизнь
                        позитивной энергией. Наш спа салон — целый мир, призванный помочь вам отдохнуть и расслабится, вернуть
                        душевный покой, обрести уверенность в себе, восстановить здоровье или просто провести время с комфортом,
                        в дали от повседневной деятельности. Наш вежливый персонал, расслабляющая, мелодичная музыка и располагающая
                        домашняя обстановка помогут вам. Вы сможете отвлечься от проблем и городской суеты.
                    </p>
                    <p>
                        Благодаря большому опыту наших мастеров с о. Бали, в спа салоне &laquo;RelaxMe&raquo; вы получите
                        только положительные эмоции, а так же отдохнете и телом, и душой. Когда дело доходит до сравнения
                        нескольких спа салонов, наши цены наиболее честные! У наших мастеров высочайшая квалификация. Все
                        наши мастера из Таиланда и с острова Бали. Понимаем, что каждый клиент имеет множество уникальных
                        медицинских и психологических аспектов. После наших процедур у всех наших посетителей повышается не
                        только настроение!
                    </p>
                    <p>
                        Для тех, кто заботится о своей фигуре, для поддержания формы или снижения веса, мы предоставляем
                        слим-программы. В нашем спа салоне есть программа «Худеем без нагрузок!», которая позволяет убрать
                        лишние сантиметры за несколько сеансов. Мы можем предложить вам тайскую сауну, это достаточно самобытная
                        процедура, которая позволит обрести новый положительный опыт. Спа салон &laquo;RelaxMe&raquo;,
                        не только спа салон для женщин и девушек. Мы приглашаем и мужчин воспользоваться услугами нашего
                        спа салона и попробовать различные виды тайского массажа.
                    </p>
                </div>
            </section>
            <footer>
                &copy; SPA-салон &laquo;RelaxMe&raquo; - <?=date('Y')?>. Все права защищены
            </footer>
            <?php if ($isAuth):?>
                <div class="modal-wrapper" style="<?=$iLoginCounter <> 1 || $isBirthdayModalShow ? 'display:none' : ''?>">
                    <div class="modal">
                        <div class="title"><?=getCurrentUser()?>, сколько дней осталось до Вашего дня рождения?</div>
                        <form method="post">
                            <label for="days">
                                <div>Введите дату своего рождения</div>
                                <input id="days" type="date" value="" name="date" required>
                            </label>
                            <input type="submit" name="birthday" value="Отправить">
                        </form>
                    </div>
                </div>
            <?php endif;?>
        </div>
    </body>
</html>
