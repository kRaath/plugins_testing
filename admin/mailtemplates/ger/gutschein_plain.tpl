{includeMailTemplate template=header type=plain}

Sehr {if $Kunde->cAnrede == "w"}geehrte{else}geehrter{/if} {$Kunde->cAnredeLocalized} {$Kunde->cNachname},

wir freuen uns Ihnen mitteilen zu d�rfen, dass auf Ihrem Kundenkonto ein Gutschein f�r Sie hinterlegt wurde. 

Gutscheinwert: {$Gutschein->cLocalizedWert}

Grund f�r die Ausstellung des Gutscheins: {$Gutschein->cGrund} 

Diesen Gutschein k�nnen Sie einfach bei Ihrer n�chsten Bestellung einl�sen. Der Betrag wird dann von Ihrem Einkaufswert abgezogen. 

Viel Spa� bei Ihrem n�chsten Einkauf in unserem Shop. 

Mit freundlichem Gru�,
Ihr Team von {$Firma->cName}

{includeMailTemplate template=footer type=plain}