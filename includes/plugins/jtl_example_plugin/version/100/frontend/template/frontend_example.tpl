<div class="jtl_example_test_cases">
	<p>Dieser Text wird durch das Plugin "JTL Example" erzeugt. Es dient nur Demonstrationszwecken f&uuml;r Plugin-Entwickler.</p>
	<p>Deinstallieren Sie es, falls Sie diese Ausgabe nicht sehen wollen.</p>

	<div class="jtl_example_stuff" id="foo">
		<p class="test">Foooooo! (This should have a yellow background)</p>
	</div>
	<div class="jtl_example_stuff" id="bar">
		<p class="test">Baaaaar! (This should have a blue background)</p>
	</div>
	<div class="jtl_example_stuff" id="calculated_pi">
		<p class="pi">3</p>
	</div>
	<div class="jtl_example_stuff" id="db_text">
		<p class="text">Text loaded from DB: {$some_text}</p>
	</div>

	<form class="form" id="jtl-example-form" method="post">
		{$jtl_token} {*add csrf protection*}
		<p class="input-group">
			<span class="input-group-addon">
				<label for="jtl-example-input-1">Eine Zahl</label>
			</span>
			<input id="jtl-example-input-1" class="form-control" type="number" name="jtl-number" placeholder="Nummer" required />
		</p>
		<p class="input-group">
			<span class="input-group-addon">
				<label for="jtl-example-input-2">0 oder 1</label>
			</span>
			<input id="jtl-example-input-2" class="form-control" type="number" name="jtl-number-two" placeholder="Nummer 2" min="0" max="1" required />
		</p>
		<p class="input-group">
			<span class="input-group-addon">
				<label for="jtl-example-input-3">Ein Text</label>
			</span>
			<input id="jtl-example-input-3" class="form-control" type="text" name="jtl-text" placeholder="Text" required />
		</p>
		<p>
			<button class="btn btn-primary" type="submit" name="jtl-example-post" value="1"><i class="fa fa-save"></i> Speichern</button>
		</p>
		{if !empty($jtlExmpleSuccess)}
			<p class="alert alert-success">{$jtlExmpleSuccess}</p>
		{elseif !empty($jtlExmpleError)}
			<p class="alert alert-danger">{$jtlExmpleError}</p>
		{/if}
	</form>
</div>