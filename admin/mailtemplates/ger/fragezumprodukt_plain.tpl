{includeMailTemplate template=header type=plain}

Shop: {$Einstellungen.global.global_shopname}

Frage zu folgendem Produkt: {$Artikel->cName}

Emailadresse des Kunden: {$Nachricht->cMail}

Frage: {$Nachricht->cNachricht}

Anfrage von:
{if $Nachricht->cAnredeLocalized}{$Nachricht->cAnredeLocalized} {/if}{if $Nachricht->cVorname}{$Nachricht->cVorname} {/if}{if $Nachricht->cNachname}{$Nachricht->cNachname}{/if}
{if $Nachricht->cFirma}{$Nachricht->cFirma}{/if}

Email: {$Nachricht->cMail}
{if $Nachricht->cTel}Tel: {$Nachricht->cTel}{/if}
{if $Nachricht->cMobil}Mobil: {$Nachricht->cMobil}{/if}
{if $Nachricht->cFax}Fax: {$Nachricht->cFax}{/if}

{includeMailTemplate template=footer type=plain}