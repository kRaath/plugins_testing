{includeMailTemplate template=header type=plain}

Sehr {if $Kunde->cAnrede=="w"}geehrte{else}geehrter{/if} {$Kunde->cAnredeLocalized} {$Kunde->cNachname},

die Zahlung f�r Ihre Bestellung mit Bestellnummer {$Bestellung->cBestellNr} vom {$Bestellung->dErstelldatum_de} in H�he von {$Bestellung->WarensummeLocalized[0]} ist per {$Bestellung->Zahlungsart->cName} bei uns eingegangen.

Nachfolgend erhalten Sie nochmals einen �berblick �ber Ihre Bestellung:

{foreach name=pos from=$Bestellung->Positionen item=Position}
{if $Position->nPosTyp==1}
{$Position->nAnzahl}x {$Position->cName} - {$Position->cGesamtpreisLocalized[$NettoPreise]}
{foreach name=variationen from=$Position->WarenkorbPosEigenschaftArr item=WKPosEigenschaft}
{$WKPosEigenschaft->cEigenschaftName}: {$WKPosEigenschaft->cEigenschaftWertName}
{/foreach}
{else}
{$Position->nAnzahl}x {$Position->cName} - {$Position->cGesamtpreisLocalized[$NettoPreise]}
{/if}
{/foreach}

{foreach name=steuerpositionen from=$Bestellung->Steuerpositionen item=Steuerposition}
{$Steuerposition->cName}: {$Steuerposition->cPreisLocalized}
{/foreach}

{if isset($GuthabenNutzen) && $GuthabenNutzen == 1}
Gutschein: -{$GutscheinLocalized}
{/if}

Gesamtsumme: {$Bestellung->WarensummeLocalized[0]}


�ber den Versand der Ware werden wir Sie gesondert informieren.

Mit freundlichem Gru�,
Ihr Team von {$Firma->cName}

{includeMailTemplate template=footer type=plain}