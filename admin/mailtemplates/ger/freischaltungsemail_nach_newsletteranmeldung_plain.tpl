{includeMailTemplate template=header type=plain}

{if isset($Kunde->kKunde) && $Kunde->kKunde > 0}
	Sehr {if $Kunde->cAnrede=="w"}geehrte Frau{else}geehrter Herr{/if} {$Kunde->cNachname},
{else}
	Sehr {if $NewsletterEmpfaenger->cAnrede=="w"}geehrte Frau{else}geehrter Herr{/if} {$NewsletterEmpfaenger->cNachname},
{/if}

wir freuen uns, Sie als Newsletter-Abonnent bei {$Firma->cName} begr��en zu k�nnen.

Bitte klicken Sie den folgenden Freischaltcode, um Newsletter zu empfangen:
{$NewsletterEmpfaenger->cFreischaltURL}

Sie k�nnen sich jederzeit vom Newsletter abmelden indem Sie entweder den L�schcode <a href="{$NewsletterEmpfaenger->cLoeschURL}">{$NewsletterEmpfaenger->cLoeschURL}</a>} eingeben oder den Link Newsletter im Shop besuchen.

Mit freundlichem Gru�,
Ihr Team von {$Firma->cName}

{includeMailTemplate template=footer type=plain}