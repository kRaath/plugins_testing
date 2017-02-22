{includeMailTemplate template=header type=plain}

Sehr {if $Kunde->cAnrede == "w"}geehrte{else}geehrter{/if} {$Kunde->cAnredeLocalized} {$Kunde->cNachname},

m�chten Sie Ihre Erfahrungen mit Ihren k�rzlich bei uns erworbenen Produkten mit anderen teilen, so w�rden wir uns sehr freuen, wenn Sie eine Bewertung abgeben.

Zur Abgabe einer Bewertung klicken Sie einfach auf eines Ihrer erworbenen Produkte:

{foreach name=pos from=$Bestellung->Positionen item=Position}
{if $Position->nPosTyp==1}
{$Position->cName} ({$Position->cArtNr})
{$ShopURL}/index.php?a={$Position->kArtikel}&bewertung_anzeigen=1

{foreach name=variationen from=$Position->WarenkorbPosEigenschaftArr item=WKPosEigenschaft}

{$WKPosEigenschaft->cEigenschaftName}: {$WKPosEigenschaft->cEigenschaftWertName}
{/foreach}
{/if}
{/foreach}

Vielen Dank f�r Ihre M�he.

{if !empty($oTrustedShopsBewertenButton->cURL)}
Waren Sie mit Ihrer Bestellung zufrieden? Dann w�rden wir uns �ber eine Empfehlung freuen ... es dauert auch nur eine Minute.
Bewerten Sie uns unter {$oTrustedShopsBewertenButton->cURL}
{/if}

Mit freundlichem Gru�,
Ihr Team von {$Firma->cName}

{includeMailTemplate template=footer type=plain}