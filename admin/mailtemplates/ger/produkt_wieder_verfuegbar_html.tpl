{includeMailTemplate template=header type=html}

Hallo{if $Benachrichtigung->cVorname} {$Benachrichtigung->cVorname}{/if}{if $Benachrichtigung->cNachname} {$Benachrichtigung->cNachname}{/if},<br>
<br>
wir freuen uns, Ihnen mitteilen zu d�rfen, dass das Produkt {$Artikel->cName} ab sofort wieder bei uns erh�ltlich ist.<br>
<br>
�ber diesen Link kommen Sie direkt zum Produkt in unserem Onlineshop: <a href="{$ShopURL}/{$Artikel->cURL}">{$Artikel->cName}</a><br>
<br>
Mit freundlichem Gru�,<br>
Ihr Team von {$Firma->cName}

{includeMailTemplate template=footer type=html}