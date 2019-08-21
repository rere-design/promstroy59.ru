
<!-- COUNTERS SECTION START -->

		<?php
			Landing::ShowMetrika($cms_object->codes->metrikas);
			Landing::ShowAnalytics($cms_object->codes->analytics);
			Landing::ShowCode($cms_object->codes->footer);
		?>


<? if ($this->debug): ?><div id="goals"></div><? endif; ?>
