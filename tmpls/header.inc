<? Landing::ShowCode($cms_object->codes->body); ?>

<header class="header">
    <div class="art container">
        <nav class="navbar is-spaced">
            <div class="navbar-brand">
                <a class="navbar-item header__logo" href="/">
                    <img src="/styles/img/logo.png" width="188" height="40">
                </a>
                <a role="button" class="navbar-burger burger" data-toggle="menu">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="menu" class="navbar-menu">
                <ul class="navbar-start">
                    <? foreach ($cms_object->options->menu as $item): ?>
                        <? if (empty($item->active)) continue; ?>
                        <li class="navbar-item" style="float: none; margin:0;">
                            <a href="<?= $item->url; ?>"<?= Landing::IfThen(strpos($item->url, '#') > -1, ' class="scroll"', ''); ?>><?= $item->title; ?></a>
                        </li>
                    <? endforeach; ?>
                </ul>
                <ul class="navbar-end contacts">
                    <li class="email navbar-item" style="float: none; margin:0;"><a href="<?= Landing::GetEmail($cms_object->options->contacts->email); ?>"
                                          target="_blank" class="clickgoal"
                                          data-goal="goal_email"><?= $cms_object->options->contacts->email; ?></a></li>
                    <? foreach ($cms_object->options->contacts->phones as $id => $phone): ?>
                        <? if ($id > 1) break; ?>
                        <li class="phone navbar-item" style="float: none; margin:0;">
                            <a href="<?= Landing::GetPhone($phone); ?>" class="clickgoal"
                               data-goal="goal_phone"><?= $phone; ?></a>
                        </li>
                    <? endforeach; ?>
                </ul>
            </div>
        </nav>
    </div>
</header>