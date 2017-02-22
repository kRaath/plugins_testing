<div id="trustedShopsCheckout" style="display: none;">
    <span id="tsCheckoutOrderNr">{$Bestellung->cBestellNr}</span>
    <span id="tsCheckoutBuyerEmail">{$Kunde->cMail}</span>
    <span id="tsCheckoutOrderAmount">{$Bestellung->fGesamtsummeKundenwaehrung|string_format:"%.2f"}</span>
    <span id="tsCheckoutOrderCurrency">{$Bestellung->Waehrung->cISO}</span>
    <span id="tsCheckoutOrderPaymentType">{$Bestellung->cZahlungsartName}</span>
    <span id="tsCheckoutOrderEstDeliveryDate">{$ts_features_max_deliverydate}</span>
</div>