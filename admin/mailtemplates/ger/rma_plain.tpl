{includeMailTemplate template=header type=plain}

vielen Dank f�r Ihre Warenr�cksendung bei {$Einstellungen.global.global_shopname}.

Ihre Warenr�cksendung mit der RMA Nummer {$oRMA->cRMANumber} umfasst folgende Positionen:

{if isset($oRMA->oRMAArtikel_arr) && $oRMA->oRMAArtikel_arr|@count > 0}
{foreach name=artikel from=$oRMA->oRMAArtikel_arr item=oRMAArtikel}
Artikel: {$oRMAArtikel->cArtikelName}
Anzahl: {$oRMAArtikel->fAnzahl}
{/foreach}
{/if}

Sobald Ihre R�cksendung eintrifft, werden wir eine Erstattung �ber den
Warenwert veranlassen. Dieser Betrag wird auf das bei Ihrer Bestellung
genutzte Bankkonto zur�ckgebucht. Die Erstattung kann einige Tage
dauern. Wir bitten um Ihr Verst�ndnis.

Sollten Sie noch weitere Fragen haben, z�gern Sie nicht, uns zu schreiben.

{includeMailTemplate template=footer type=plain}