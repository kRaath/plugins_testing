{includeMailTemplate template=header type=html}

Sehr {if $Kunde->cAnrede == "w"}geehrte{else}geehrter{/if} {$Kunde->cAnredeLocalized} {$Kunde->cNachname},<br>
<br>
vielen Dank f�r Ihre Bewertung eines Artikels. Ihr Guthaben Bonus in H�he von {$oBewertungGuthabenBonus->fGuthabenBonusLocalized} steht Ihnen ab sofort zur Verf�gung.<br>
Sie k�nnen Ihr Guthaben jederzeit bei einem Ihrer n�chsten Eink�ufe einl�sen.<br>
<br>
Mit freundlichem Gru�,<br>
Ihr Team von {$Firma->cName}

{includeMailTemplate template=footer type=html}