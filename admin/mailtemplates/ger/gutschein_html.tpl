{includeMailTemplate template=header type=html}

Sehr {if $Kunde->cAnrede == "w"}geehrte{else}geehrter{/if} {$Kunde->cAnredeLocalized} {$Kunde->cNachname},<br>
<br>
wir freuen uns Ihnen mitteilen zu d�rfen, dass auf Ihrem Kundenkonto ein Gutschein f�r Sie hinterlegt wurde.<br>
<br>
<strong>Gutscheinwert:</strong> {$Gutschein->cLocalizedWert}<br>
<br>
Grund f�r die Ausstellung des Gutscheins: {$Gutschein->cGrund}<br>
<br>
Diesen Gutschein k�nnen Sie einfach bei Ihrer n�chsten Bestellung einl�sen. Der Betrag wird dann von Ihrem Einkaufswert abgezogen.<br>
<br>
Viel Spa� bei Ihrem n�chsten Einkauf in unserem Shop.<br>
<br>
Mit freundlichem Gru�,<br>
Ihr Team von {$Firma->cName}

{includeMailTemplate template=footer type=html}