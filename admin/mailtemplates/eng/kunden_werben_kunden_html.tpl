{includeMailTemplate template=header type=html}

Hallo {$Kunde->cVorname},<br><br>

anbei bekommst du ein Guthaben von {$Neukunde->fGuthaben} f�r {$Firma->cName}.<br><br>

�brigens, ich werbe Dich im Rahmen der {$Firma->cName} Kunden werben Kunden Aktion.<br><br>

Viele Gr��e,<br>
{$Bestandskunde->cVorname} {$Bestandskunde->cNachname}

{includeMailTemplate template=footer type=html}