{includeMailTemplate template=header type=plain}

Sehr {if $Kunde->cAnrede == "w"}geehrte{else}geehrter{/if} {$Kunde->cAnredeLocalized} {$Kunde->cNachname},

Ihre Bestellung vom {$Bestellung->dErstelldatum_de} mit Bestellnummer {$Bestellung->cBestellNr} wurde heute an Sie versandt. 

{if $Bestellung->cTrackingURL}
Mit nachfolgendem Link k�nnen Sie sich �ber den Status Ihrer Sendung informieren: 

{$Bestellung->cTrackingURL}
{/if}

Wir w�nschen Ihnen viel Spa� mit der Ware und bedanken f�r Ihren Einkauf und Ihr Vertrauen.

Mit freundlichem Gru�,
Ihr Team von {$Firma->cName}

{includeMailTemplate template=footer type=plain}