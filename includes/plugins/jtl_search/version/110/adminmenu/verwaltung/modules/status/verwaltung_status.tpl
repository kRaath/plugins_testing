<div class="jtlsearch_actioncolumn">
	<div class="jtlsearch_inner">
		<a class="btn btn-primary button orange" href="plugin.php?kPlugin={$oPlugin->kPlugin}">Aktuallisieren</a>
	</div>
</div>
<div class="jtlsearch_infocolumn">
	<div class="jtlsearch_inner">
		{if $xIndexStatus_arr|@count > 0}
			<p>Ihre Shop-ID: <strong>{$xIndexStatus_arr.0->kUserShop}</strong></p>
			<br />
			<ul class="infolist list-unstyled">
				{foreach from=$xIndexStatus_arr item=xIndexStatus}
					{if $xIndexStatus->nItemCount > 0}
						<li>
							<h4 class="label-wrap"><span class="label label-success">Suchindex f�r Sprache "{$xIndexStatus->cLanguageISO}" ist verf�gbar!</span></h4>
						</li>
					{else}
						<li>
                            <h4 class="label-wrap"><span class="label label-info">F�r den Suchindex "{$xIndexStatus->cLanguageISO}" wurden noch keine Daten importiert!</span></h4>
						</li>
					{/if}
				{/foreach}
			</ul>
		{else}
			<div class="alert alert-info">F�r Ihren Shop wurden auf dem Suchserver noch keine Daten indiziert. Bitte Export starten.</div>
		{/if}
	</div>
</div>
<div class="jtlsearch_clear"></div>
