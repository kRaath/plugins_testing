{includeMailTemplate template=header type=plain}

Sehr {if $Kunde->cAnrede == "w"}geehrte{else}geehrter{/if} {$Kunde->cAnredeLocalized} {$Kunde->cNachname},

wie von Ihnen gew�nscht haben wir heute Ihr Kundenkonto mit der
Emailadresse {$Kunde->cMail} gel�scht.

Sollten Sie mit unserem Service nicht zufrieden gewesen sein, so
teilen Sie uns dies bitte mit, damit wir unseren Service verbessern
k�nnen.

Falls Sie zu einem sp�teren Zeitpunkt wieder bei uns einkaufen
m�chten, melden Sie sich einfach erneut an und er�ffnen Sie ein neues
Kundenkonto bei uns.

Mit freundlichem Gru�,
Ihr Team von {$Firma->cName}

{includeMailTemplate template=footer type=plain}