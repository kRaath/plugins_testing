{includeMailTemplate template=header type=html}

Dear {if $Kunde->cAnrede == "w"}geehrte{else}geehrter{/if} {$Kunde->cAnredeLocalized} {$Kunde->cNachname},<br>
<br>
Thank you for your product rating. You can redeem your bonus credit of {$oBewertungGuthabenBonus->fGuthabenBonusLocalized} for any of your future purchases.<br>
<br>
Yours sincerely,<br>
{$Firma->cName}

{includeMailTemplate template=footer type=html}