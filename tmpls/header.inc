<? Landing::ShowCode($cms_object->codes->body); ?>

<?
$headerMenu = array(
    ['TITLE' => 'Продажа компрессоров', 'URL'=>'compressors'],
    ['TITLE' => 'Выкуп Б/У', 'URL'=>'buyout'],
    ['TITLE' => 'Отзывы', 'URL'=>'comments'],
    ['TITLE' => 'Аренда', 'URL'=>'rent'],
    ['TITLE' => 'Выкуп', 'URL'=>'buyout'],
    ['TITLE' => 'Контакты', 'URL'=>'contacts'],
)
?>
<header class="header">
    <div class="art container">
        <nav class="navbar is-spaced">
            <div class="navbar-brand">
                <a class="navbar-item header__logo" href="/">
                    <img src="/styles/img/logo.jpg" width="188" height="43">
                </a>
                <a role="button" class="navbar-burger burger" data-toggle="menu">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="menu" class="navbar-menu">
                <ul class="navbar-start">
                    <? foreach ($headerMenu as $value):?>
                        <li class="navbar-item" style="float: none; margin:0;">
                            <a href="#<?= $value['URL']?>" class="scroll"><?= $value['TITLE']?></a>
                        </li>
                    <?endforeach;?>
                </ul>
                <ul class="navbar-end contacts">
                    <li class="phone navbar-item" style="float: none; margin:0;">
                        <a href="tel:+79082500119" class="clickgoal" data-goal="goal_phone">
                            8 (908) 25-00-119
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>