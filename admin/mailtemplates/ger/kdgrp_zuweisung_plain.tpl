{includeMailTemplate template=header type=plain}

Sehr {if $Kunde->cAnrede == "w"}geehrte{else}geehrter{/if} {$Kunde->cAnredeLocalized} {$Kunde->cNachname},

wir haben Ihre Kundengruppe ge�ndert. Sie m��ten ab sofort andere Preise als den Standardpreis angezeigt bekommen.

Momentan haben wir es noch nicht geschafft, alle Preise anzupassen.

Mit freundlichem Gru�,
Ihr Team von {$Firma->cName}

{includeMailTemplate template=footer type=plain}