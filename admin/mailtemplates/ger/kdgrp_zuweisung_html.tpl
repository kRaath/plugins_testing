{includeMailTemplate template=header type=html}

Sehr {if $Kunde->cAnrede == "w"}geehrte{else}geehrter{/if} {$Kunde->cAnredeLocalized} {$Kunde->cNachname},<br>
<br>
wir haben Ihre Kundengruppe ge�ndert. Sie m��ten ab sofort andere Preise als den Standardpreis angezeigt bekommen.<br>
<br>
Momentan haben wir es noch nicht geschafft, alle Preise anzupassen.<br>
<br>
Mit freundlichem Gru�,<br>
Ihr Team von {$Firma->cName}

{includeMailTemplate template=footer type=html}