<? if(!empty($errors)): ?>
	<ul class="errors">
	<? foreach($errors as $error): ?>
		<li><?=$error?>
	<? endforeach ?>
	</ul>
<? endif; ?>
