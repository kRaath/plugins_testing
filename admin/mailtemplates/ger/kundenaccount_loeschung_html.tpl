{includeMailTemplate template=header type=html}

Sehr {if $Kunde->cAnrede == "w"}geehrte{else}geehrter{/if} {$Kunde->cAnredeLocalized} {$Kunde->cNachname},<br>
<br>
wie von Ihnen gew�nscht haben wir heute Ihr Kundenkonto mit der
Emailadresse {$Kunde->cMail} gel�scht.<br>
<br>
Sollten Sie mit unserem Service nicht zufrieden gewesen sein, so
teilen Sie uns dies bitte mit, damit wir unseren Service verbessern
k�nnen.<br>
<br>
Falls Sie zu einem sp�teren Zeitpunkt wieder bei uns einkaufen
m�chten, melden Sie sich einfach erneut an und er�ffnen Sie ein neues
Kundenkonto bei uns.<br>
<br>
Mit freundlichem Gru�,<br>
Ihr Team von {$Firma->cName}

{includeMailTemplate template=footer type=html}