{includeMailTemplate template=header type=plain}

Hallo{if $Benachrichtigung->cVorname} {$Benachrichtigung->cVorname}{/if}{if $Benachrichtigung->cNachname} {$Benachrichtigung->cNachname}{/if},<br>
<br>
wir freuen uns, Ihnen mitteilen zu d�rfen, dass das Produkt {$Artikel->cName} ab sofort wieder bei uns erh�ltlich ist.<br>
<br>
�ber diesen Link kommen Sie direkt zum Produkt in unserem Onlineshop: {$ShopURL}/{$Artikel->cURL}.<br>
<br>
Mit freundlichem Gru�,
Ihr Team von {$Firma->cName}

{includeMailTemplate template=footer type=plain}