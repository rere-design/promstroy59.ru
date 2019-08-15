
<!-- COUNTERS SECTION START -->

		<?php
			Landing::ShowMetrika($cms_object->codes->metrikas);
			Landing::ShowAnalytics($cms_object->codes->analytics);
			Landing::ShowCode($cms_object->codes->footer);
		?>

<!-- /COUNTERS SECTION END -->

<? Landing::ShowAuthorInfo(); ?>

<? if ($this->debug): ?><div id="goals"></div><? endif; ?>
