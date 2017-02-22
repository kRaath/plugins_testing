{includeMailTemplate template=header type=html}

{if isset($Kunde->kKunde) && $Kunde->kKunde > 0}
    Sehr {if $Kunde->cAnrede=="w"}geehrte Frau{else}geehrter Herr{/if} {$Kunde->cNachname},<br>
    <br>
{else}
    Sehr {if $NewsletterEmpfaenger->cAnrede=="w"}geehrte Frau{else}geehrter Herr{/if} {$NewsletterEmpfaenger->cNachname},<br>
    <br>
{/if}
wir freuen uns, Sie als Newsletter-Abonnent bei {$Firma->cName} begr��en zu k�nnen.<br>
<br>
Bitte klicken Sie den folgenden Freischaltcode, um Newsletter zu empfangen:<br>
<a href="{$NewsletterEmpfaenger->cFreischaltURL}">{$NewsletterEmpfaenger->cFreischaltURL}</a><br>
<br>
Sie k�nnen sich jederzeit vom Newsletter abmelden indem Sie entweder den L�schcode <a href="{$NewsletterEmpfaenger->cLoeschURL}">{$NewsletterEmpfaenger->cLoeschURL}</a> eingeben oder den Link Newsletter im Shop besuchen.<br>
<br>
Mit freundlichem Gru�,<br>
Ihr Team von {$Firma->cName}

{includeMailTemplate template=footer type=html}