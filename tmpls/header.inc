<? Landing::ShowCode($cms_object->codes->body); ?>

<header>
	<div class="art">
		<div class="logo"><a href="/"><img src="/styles/img/logo.png" alt=""></a></div>
		<nav>
			<ul>
				<? foreach ($cms_object->options->menu as $item): ?>
					<? if (empty($item->active)) continue; ?>
					<li><a href="<?= $item->url; ?>"<?= Landing::IfThen(strpos($item->url, '#') > -1, ' class="scroll"', ''); ?>><?= $item->title; ?></a></li>
				<? endforeach; ?>
			</ul>
		</nav>
		<div class="contacts">
			<ul>
				<li class="email"><a href="<?= Landing::GetEmail($cms_object->options->contacts->email); ?>" target="_blank" class="clickgoal" data-goal="goal_email"><?= $cms_object->options->contacts->email; ?></a></li>
				<? foreach ($cms_object->options->contacts->phones as $id => $phone): ?>
					<? if ($id > 1) break; ?>
					<li class="phone"><a href="<?= Landing::GetPhone($phone); ?>" class="clickgoal" data-goal="goal_phone"><?= $phone; ?></a></li>
				<? endforeach; ?>
			</ul>
		</div>
	</div>
</header>