{**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 *}

<fieldset>
   <legend>{lang key="address" section="account data"}</legend>
    {* salutation / title *}
    <div class="row">
        {if $Einstellungen.kunden.kundenregistrierung_abfragen_anrede !== 'N'}
            <div class="col-xs-12 col-md-6">
                <div class="form-group float-label-control{if isset($fehlendeAngaben.anrede)} has-error{/if} required">
                    <label for="salutation" class="control-label">{lang key="salutation" section="account data"}</label>
                    <select name="anrede" id="salutation" class="form-control" required>
                        <option value="" selected="selected" disabled>{lang key="salutation" section="account data"}</option>
                        <option value="w" {if isset($Kunde->cAnrede) && $Kunde->cAnrede === 'w'}selected="selected"{/if}>{$Anrede_w}</option>
                        <option value="m" {if isset($Kunde->cAnrede) && $Kunde->cAnrede === 'm'}selected="selected"{/if}>{$Anrede_m}</option>
                    </select>
                    {if isset($fehlendeAngaben.anrede)}<div class="alert alert-danger">{lang key="fillOut" section="global"}</div>{/if}
                </div>
            </div>
        {/if}

        {if $Einstellungen.kunden.kundenregistrierung_abfragen_titel !== 'N'}
            <div class="col-xs-12 col-md-6">
                <div class="form-group float-label-control{if isset($fehlendeAngaben.titel)} has-error{/if}{if $Einstellungen.kunden.kundenregistrierung_abfragen_titel === 'Y'} required{/if}">
                    <label for="title" class="control-label">{lang key="title" section="account data"}</label>
                    <input 
                    type="text" 
                    name="titel" 
                    value="{if isset($Kunde->cTitel)}{$Kunde->cTitel}{/if}" 
                    id="title" 
                    class="form-control" 
                    placeholder="{lang key="title" section="account data"}" 
                    {if $Einstellungen.kunden.kundenregistrierung_abfragen_titel === 'Y'}required{/if} 
                    >
                    {if isset($fehlendeAngaben.titel)}<div class="alert alert-danger">{lang key="fillOut" section="global"}</div>{/if}
                </div>
            </div>
        {/if}
    </div>
    {* firstname lastname *}
    <div class="row">   
        <div class="col-xs-12 col-md-6">
            <div class="form-group float-label-control{if isset($fehlendeAngaben.vorname)} has-error{/if}{if $Einstellungen.kunden.kundenregistrierung_pflicht_vorname === 'Y'} required{/if}">
                <label for="firstName" class="control-label">{lang key="firstName" section="account data"}</label>
                <input 
                type="text" 
                name="vorname" 
                value="{if isset($Kunde->cVorname)}{$Kunde->cVorname}{/if}" 
                id="firstName" 
                class="form-control" 
                placeholder="{lang key="firstName" section="account data"}"
                {if $Einstellungen.kunden.kundenregistrierung_pflicht_vorname === 'Y'} required{/if} 
                >
                {if isset($fehlendeAngaben.vorname)}
                    {if $fehlendeAngaben.vorname==1}
                        <div class="alert alert-danger">{lang key="fillOut" section="global"}</div>
                    {elseif $fehlendeAngaben.vorname==2}
                        <div class="alert alert-danger">{lang key="firstNameNotNumeric" section="account data"}</div>
                    {/if}
                {/if}
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group float-label-control{if isset($fehlendeAngaben.nachname)} has-error{/if} required">
                <label for="lastName" class="control-label">{lang key="lastName" section="account data"}</label>
                <input 
                type="text" 
                name="nachname" 
                value="{if isset($Kunde->cNachname)}{$Kunde->cNachname}{/if}" 
                id="lastName" 
                class="form-control" 
                placeholder="{lang key="lastName" section="account data"}" 
                required 
                >
                {if isset($fehlendeAngaben.nachname)}
                    {if $fehlendeAngaben.nachname==1}
                        <div class="alert alert-danger">{lang key="fillOut" section="global"}</div>
                    {elseif $fehlendeAngaben.nachname==2}
                        <div class="alert alert-danger">{lang key="lastNameNotNumeric" section="account data"}</div>
                    {/if}
                {/if}
            </div>
        </div>
    </div>
    {* firm / firmtext *}
    <div class="row">
        {if $Einstellungen.kunden.kundenregistrierung_abfragen_firma !== 'N'}
        <div class="col-xs-12 col-md-6">
            <div class="form-group float-label-control{if isset($fehlendeAngaben.firma)} has-error{/if}{if $Einstellungen.kunden.kundenregistrierung_abfragen_firma === 'Y'} required{/if}">
                <label for="firm" class="control-label">{lang key="firm" section="account data"}</label>
                <input 
                type="text" 
                name="firma" 
                value="{if !empty($Kunde->cFirma)}{$Kunde->cFirma}{/if}"
                id="firm" 
                class="form-control" 
                placeholder="{lang key="firm" section="account data"}" 
                {if $Einstellungen.kunden.kundenregistrierung_abfragen_firma === 'Y'} required{/if} 
                >
                {if isset($fehlendeAngaben.firma)}<div class="alert alert-danger">{lang key="fillOut" section="global"}</div>{/if}
            </div>
        </div>
        {/if}

        {if $Einstellungen.kunden.kundenregistrierung_abfragen_firmazusatz !== 'N'}
        <div class="col-xs-12 col-md-6">
            <div class="form-group float-label-control{if isset($fehlendeAngaben.firmazusatz)} has-error{/if}{if $Einstellungen.kunden.kundenregistrierung_abfragen_firmazusatz === 'Y'} required{/if}">
                <label for="firmext" class="control-label">{lang key="firmext" section="account data"}</label>
                <input 
                type="text" 
                name="firmazusatz" 
                value="{if isset($Kunde->cZusatz)}{$Kunde->cZusatz}{/if}" 
                id="firm" 
                class="form-control" 
                placeholder="{lang key="firmext" section="account data"}"
                {if $Einstellungen.kunden.kundenregistrierung_abfragen_firmazusatz === 'Y'} required{/if} 
                />
                {if isset($fehlendeAngaben.firmazusatz)}<div class="alert alert-danger">{lang key="fillOut" section="global"}</div>{/if}
            </div>
        </div>
        {/if}
    </div>
    {* street / number *}
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group float-label-control{if isset($fehlendeAngaben.strasse)} has-error{/if} required">
                <label class="control-label" for="street">{lang key="street" section="account data"}</label>
                <input 
                type="text" 
                name="strasse" 
                value="{if isset($Kunde->cStrasse)}{$Kunde->cStrasse}{/if}" 
                id="street" 
                class="form-control" 
                placeholder="{lang key="street" section="account data"}" 
                required 
                >
                {if isset($fehlendeAngaben.strasse)}<div class="alert alert-danger">{lang key="fillOut" section="global"}</div>{/if}
            </div>
        </div>

        <div class="col-xs-12 col-md-3">
            <div class="form-group float-label-control{if isset($fehlendeAngaben.hausnummer)} has-error{/if} required">
                <label class="control-label" for="streetnumber">{lang key="streetnumber" section="account data"}</label>
                <input 
                type="text" 
                name="hausnummer" 
                value="{if isset($Kunde->cHausnummer)}{$Kunde->cHausnummer}{/if}" 
                id="streetnumber" 
                class="form-control" 
                placeholder="{lang key="streetnumber" section="account data"}" 
                required 
                >
                {if isset($fehlendeAngaben.hausnummer)}<div class="alert alert-danger">{lang key="fillOut" section="global"}</div>{/if}
            </div>
        </div>
    </div>
    {* adress addition *}
    {if $Einstellungen.kunden.kundenregistrierung_abfragen_adresszusatz !== 'N'}
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group float-label-control{if isset($fehlendeAngaben.adresszusatz)} has-error{/if}{if $Einstellungen.kunden.kundenregistrierung_abfragen_adresszusatz === 'Y'} required{/if}">
                    <label class="control-label" for="street2">{lang key="street2" section="account data"}</label>
                    <input 
                    type="text" 
                    name="adresszusatz" 
                    value="{if isset($Kunde->cAdressZusatz)}{$Kunde->cAdressZusatz}{/if}" 
                    id="street2" 
                    class="form-control"
                    placeholder="{lang key="street2" section="account data"}" 
                    {if $Einstellungen.kunden.kundenregistrierung_abfragen_adresszusatz === 'Y'} required{/if} 
                    />
                    {if isset($fehlendeAngaben.adresszusatz)}<div class="alert alert-danger">{lang key="fillOut" section="global"}</div>{/if}
                </div>
            </div>
        </div>
    {/if}
    {* country *}
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group float-label-control required">
                <label class="control-label" for="country">{lang key="country" section="account data"}</label>
                <select name="land" id="country" class="country_input form-control" required>
                <option value="" disabled>{lang key="country" section="account data"}</option>
                {foreach name=land from=$laender item=land}
                    <option value="{$land->cISO}" {if ($Einstellungen.kunden.kundenregistrierung_standardland==$land->cISO && empty($Kunde->cLand)) || !empty($Kunde->cLand) && $Kunde->cLand == $land->cISO}selected="selected"{/if}>{$land->cName}</option>
                {/foreach}
                </select>
            </div>
        </div>
    {if $Einstellungen.kunden.kundenregistrierung_abfragen_bundesland === 'N'}
    </div>
    {/if} {* close row if there won't follow another form-group *}

    {if $Einstellungen.kunden.kundenregistrierung_abfragen_bundesland !== 'N'}
        <div class="col-xs-12 col-md-6">
            <div class="form-group float-label-control{if isset($fehlendeAngaben.bundesland)} has-error{/if}{if $Einstellungen.kunden.kundenregistrierung_abfragen_bundesland === 'Y'} required{/if}">
                <label class="control-label" for="state">{lang key="state" section="account data"}</label>
                <input 
                type="text" 
                title="{lang key=pleaseChoose}" 
                name="bundesland" 
                value="{if !empty($Kunde->cBundesland)}{$Kunde->cBundesland}{/if}"
                id="state" 
                class="form-control"
                placeholder="{lang key="state" section="account data"}"
                {if $Einstellungen.kunden.kundenregistrierung_abfragen_bundesland === 'Y'} required{/if}
                >
                {if isset($fehlendeAngaben.bundesland)}<div class="alert alert-danger">{lang key="fillOut" section="global"}</div>{/if}
            </div>
        </div>
    </div>{* close row for country *}
    {/if}
    {* zip / city *}
    <div class="row">
        <div class="col-xs-12 col-md-3">
            <div class="form-group float-label-control{if isset($fehlendeAngaben.plz)} has-error{/if} required">
                <label class="control-label" for="plz">{lang key="plz" section="account data"}</label>
                <input 
                type="text" 
                name="plz" 
                value="{if isset($Kunde->cPLZ)}{$Kunde->cPLZ}{/if}" 
                id="plz" 
                class="plz_input form-control" 
                placeholder="{lang key="plz" section="account data"}" 
                required 
                >
                {if isset($fehlendeAngaben.plz)}<div class="alert alert-danger">{if $fehlendeAngaben.plz >= 2}{lang key="checkPLZCity" section="checkout"}{else}{lang key="fillOut" section="global"}{/if}</div>{/if}
            </div>
        </div>
        
        <div class="col-xs-12 col-md-6">
            <div class="form-group float-label-control required{if isset($fehlendeAngaben.ort)} has-error{/if}">
                <label class="control-label" for="city">{lang key="city" section="account data"}</label>
                <input 
                type="text" 
                name="ort" 
                value="{if isset($Kunde->cOrt)}{$Kunde->cOrt}{/if}" 
                id="city" 
                class="city_input form-control" 
                placeholder="{lang key="city" section="account data"}" 
                required 
                >
                {if isset($fehlendeAngaben.ort)}
                    {if $fehlendeAngaben.ort==3}
                        <div class="alert alert-danger">{lang key="cityNotNumeric" section="account data"}</div>
                    {else}
                        <div class="alert alert-danger">{lang key="fillOut" section="global"}</div>
                    {/if}
                {/if}
            </div>
        </div>
    </div>
    {* UStID *}
    {if $Einstellungen.kunden.kundenregistrierung_abfragen_ustid !== 'N'}
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group float-label-control{if isset($fehlendeAngaben.ustid)} has-error{/if}{if $Einstellungen.kunden.kundenregistrierung_abfragen_ustid === 'Y'} required{/if}">
                <label class="control-label" for="ustid">{lang key="ustid" section="account data"}</label>
                <input 
                type="text" 
                name="ustid" 
                value="{if isset($Kunde->cUSTID)}{$Kunde->cUSTID}{/if}" 
                id="ustid" 
                class="form-control" 
                placeholder="{lang key="ustid" section="account data"}" 
                {if $Einstellungen.kunden.kundenregistrierung_abfragen_ustid === 'Y'} required{/if} 
                >
                {if isset($fehlendeAngaben.ustid)}
                <div class="alert alert-danger">
                    {if $fehlendeAngaben.ustid==1}{lang key="fillOut" section="global"}
                    {elseif $fehlendeAngaben.ustid==2}{lang key="ustIDCaseTwo" section="global"}. {if $fehlendeAngaben.ustid_err|count > 0 && $fehlendeAngaben.ustid_err !== false}{lang key="ustIDCaseTwoB" section="global"}: {$fehlendeAngaben.ustid_err}{/if}
                    {elseif $fehlendeAngaben.ustid==5}{lang key="ustIDCaseFive" section="global"}.{/if}
                </div>
                {/if}
            </div>
        </div>
    </div>
    {/if}
</fieldset>

<fieldset>
   <legend>{lang key="contactInformation" section="account data"}</legend>
    {* E-Mail *}
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group float-label-control required{if isset($fehlendeAngaben.email)} has-error{/if}">
                <label class="control-label" for="email">{lang key="email" section="account data"}</label>
                <input 
                type="email" 
                name="email"
                value="{if isset($Kunde->cMail)}{$Kunde->cMail}{/if}" 
                id="email" 
                class="form-control"
                placeholder="{lang key="email" section="account data"}" 
                required 
                >
                {if isset($fehlendeAngaben.email)}
                <div class="alert alert-danger">
                    {if $fehlendeAngaben.email==1}{lang key="fillOut" section="global"}
                    {elseif $fehlendeAngaben.email==2}{lang key="invalidEmail" section="global"}
                    {elseif $fehlendeAngaben.email==3}{lang key="blockedEmail" section="global"}
                    {elseif $fehlendeAngaben.email==4}{lang key="noDnsEmail" section="account data"}
                    {elseif $fehlendeAngaben.email==5}{lang key="emailNotAvailable" section="account data"}{/if}
                </div>
                {/if}
            </div>
        </div>
    </div>
    {* phone & fax *}
    {if $Einstellungen.kunden.kundenregistrierung_abfragen_tel !== 'N' || $Einstellungen.kunden.kundenregistrierung_abfragen_fax !== 'N'}
        <div class="row">
            {if $Einstellungen.kunden.kundenregistrierung_abfragen_tel !== 'N'}
            <div class="col-xs-12 col-md-6">
                <div class="form-group float-label-control{if isset($fehlendeAngaben.tel)} has-error{/if}{if $Einstellungen.kunden.kundenregistrierung_abfragen_tel === 'Y'} required{/if}">
                    <label class="control-label" for="tel">{lang key="tel" section="account data"}</label>
                    <input 
                    type="tel" 
                    name="tel" 
                    value="{if isset($Kunde->cTel)}{$Kunde->cTel}{/if}" 
                    id="tel" 
                    class="form-control"
                    placeholder="{lang key="tel" section="account data"}" 
                    {if $Einstellungen.kunden.kundenregistrierung_abfragen_tel === 'Y'} required{/if} 
                    />
                    {if isset($fehlendeAngaben.tel)}
                    <div class="alert alert-danger">
                        {if $fehlendeAngaben.tel==1}{lang key="fillOut" section="global"}{elseif $fehlendeAngaben.tel==2}{lang key="invalidTel" section="global"}{/if}
                    </div>
                    {/if}
                </div>
            </div>
            {/if}

            {if $Einstellungen.kunden.kundenregistrierung_abfragen_fax !== 'N'}
            <div class="col-xs-12 col-md-6">
                <div class="form-group float-label-control{if isset($fehlendeAngaben.fax)} has-error{/if}{if $Einstellungen.kunden.kundenregistrierung_abfragen_fax === 'Y'} required{/if}">
                    <label class="control-label" for="fax">{lang key="fax" section="account data"}</label>
                    <input 
                    type="tel" 
                    name="fax" 
                    value="{if isset($Kunde->cFax)}{$Kunde->cFax}{/if}" 
                    id="fax" 
                    class="form-control"
                    placeholder="{lang key="fax" section="account data"}" 
                    {if $Einstellungen.kunden.kundenregistrierung_abfragen_fax === 'Y'} required{/if}
                    />
                    {if isset($fehlendeAngaben.fax)}
                        <div class="alert alert-danger">
                            {if $fehlendeAngaben.fax==1}{lang key="fillOut" section="global"}{elseif $fehlendeAngaben.fax==2}{lang key="invalidTel" section="global"}{/if}
                        </div>
                    {/if}
                </div>
            </div>
            {/if}
        </div>
    {/if}

    {if $Einstellungen.kunden.kundenregistrierung_abfragen_mobil !== 'N' || $Einstellungen.kunden.kundenregistrierung_abfragen_www !== 'N'}
        <div class="row">
            {if $Einstellungen.kunden.kundenregistrierung_abfragen_mobil !== 'N'}
                <div class="col-xs-12 col-md-6">
                    <div class="form-group float-label-control{if isset($fehlendeAngaben.mobil)} has-error{/if}{if $Einstellungen.kunden.kundenregistrierung_abfragen_mobil === 'Y'} required{/if} ">
                        <label class="control-label" for="mobile">{lang key="mobile" section="account data"}</label>
                        <input 
                        type="tel" 
                        name="mobil" 
                        value="{if isset($Kunde->cMobil)}{$Kunde->cMobil}{/if}" 
                        id="mobile" 
                        class="form-control"
                        placeholder="{lang key="mobile" section="account data"}" 
                        {if $Einstellungen.kunden.kundenregistrierung_abfragen_mobil === 'Y'} required{/if} 
                        />
                        {if isset($fehlendeAngaben.mobil)}
                            <div class="alert alert-danger">
                                {if $fehlendeAngaben.mobil==1}{lang key="fillOut" section="global"}{elseif $fehlendeAngaben.mobil==2}{lang key="invalidTel" section="global"}{/if}
                            </div>
                        {/if}
                    </div>
                </div>
            {/if}

            {if $Einstellungen.kunden.kundenregistrierung_abfragen_www !== 'N'}
                <div class="col-xs-12 col-md-6">
                    <div class="form-group float-label-control{if isset($fehlendeAngaben.www)} has-error{/if}{if $Einstellungen.kunden.kundenregistrierung_abfragen_www === 'Y'} required{/if}">
                        <label class="control-label" for="www">{lang key="www" section="account data"}</label>
                        <input 
                        type="text" 
                        name="www" 
                        value="{if isset($Kunde->cWWW)}{$Kunde->cWWW}{/if}" 
                        id="www" 
                        class="form-control"
                        placeholder="{lang key="www" section="account data"}" 
                        {if $Einstellungen.kunden.kundenregistrierung_abfragen_www === 'Y'} required{/if} 
                        />
                        {if isset($fehlendeAngaben.www)}<div class="alert alert-danger">{lang key="fillOut" section="global"}</div>{/if}
                    </div>
                </div>
            {/if}
        </div>
    {/if}

    {if $Einstellungen.kunden.kundenregistrierung_abfragen_geburtstag !== 'N'}
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group float-label-control{if isset($fehlendeAngaben.geburtstag)} has-error{/if}{if $Einstellungen.kunden.kundenregistrierung_abfragen_geburtstag === 'Y'} required{/if}">
                    <label class="control-label" for="birthday">{lang key="birthday" section="account data"}</label>
                    <input 
                    type="text" 
                    name="geburtstag" 
                    value="{if isset($Kunde->dGeburtstag) && $Kunde->dGeburtstag !== '00.00.0000'}{$Kunde->dGeburtstag}{/if}"
                    id="birthday" 
                    class="birthday form-control" 
                    placeholder="{lang key="birthday" section="account data"}" 
                    {if $Einstellungen.kunden.kundenregistrierung_abfragen_geburtstag === 'Y'} required{/if} 
                    >
                    {if isset($fehlendeAngaben.geburtstag)}
                        <div class="alert alert-danger">
                            {if $fehlendeAngaben.geburtstag==1}{lang key="fillOut" section="global"}{elseif $fehlendeAngaben.geburtstag==2}{lang key="invalidDateformat" section="global"}{elseif $fehlendeAngaben.geburtstag==3}{lang key="invalidDate" section="global"}{/if}
                        </div>
                    {/if}
                </div>
            </div>
        </div>
    {/if}
</fieldset>

{if $Einstellungen.kundenfeld.kundenfeld_anzeigen === 'Y' && !empty($oKundenfeld_arr)}
<fieldset>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            {foreach name=kundenfeld from=$oKundenfeld_arr item=oKundenfeld}
                {if $step === 'formular' || $step === 'unregistriert bestellen' || ($step === 'rechnungsdaten' && $oKundenfeld->nEditierbar != 0)}
                    {if (empty($smarty.session.Kunde->kKunde) || ($oKundenfeld->nEditierbar == 1 && isset($smarty.session.Kunde->kKunde) && $smarty.session.Kunde->kKunde > 0))}
                        {assign var=kKundenfeld value=$oKundenfeld->kKundenfeld}
                        <div class="form-group float-label-control{if isset($fehlendeAngaben.custom[$kKundenfeld])} has-error{/if}{if $oKundenfeld->nPflicht == 1} required{/if}">
                            {if $oKundenfeld->cTyp !== 'auswahl'}
                                <label class="control-label" for="custom_{$kKundenfeld}">{$oKundenfeld->cName}</label>
                                <input
                                type="{if $oKundenfeld->cTyp === 'zahl'}number{elseif $oKundenfeld->cTyp === 'datum'}date{else}text{/if}"
                                name="custom_{$kKundenfeld}"
                                id="custom_{$kKundenfeld}"
                                value="{if isset($cKundenattribut_arr[$kKundenfeld]->cWert) && ($step === 'formular' || $step === 'unregistriert bestellen')}{$cKundenattribut_arr[$kKundenfeld]->cWert}{elseif isset($Kunde->cKundenattribut_arr[$kKundenfeld]->cWert)}{$Kunde->cKundenattribut_arr[$kKundenfeld]->cWert}{/if}"
                                class="form-control"
                                placeholder="{$oKundenfeld->cName}"
                                {if $oKundenfeld->nPflicht == 1} required{/if}
                                data-toggle="floatLabel"
                                data-value="no-js" />
                                {if isset($fehlendeAngaben.custom[$kKundenfeld])}
                                    <div class="alert alert-danger">
                                        {if $fehlendeAngaben.custom[$kKundenfeld] === 1}{lang key="fillOut" section="global"}{elseif $fehlendeAngaben.custom[$kKundenfeld] === 2}{lang key="invalidDateformat" section="global"}{elseif $fehlendeAngaben.custom[$kKundenfeld] === 3}{lang key="invalidDate" section="global"}{elseif $fehlendeAngaben.custom[$kKundenfeld] === 4}{lang key="invalidInteger" section="global"}{/if}
                                    </div>
                                {/if}
                            {else}
                                <label class="control-label" for="custom_{$kKundenfeld}">{$oKundenfeld->cName}</label>
                                <select name="custom_{$kKundenfeld}" class="form-control{if $oKundenfeld->nPflicht == 1} required{/if}">
                                    <option value="" selected disabled>{lang key="pleaseChoose" section="global"}</option>
                                    {foreach name=select from=$oKundenfeld->oKundenfeldWert_arr item=oKundenfeldWert}
                                        <option value="{$oKundenfeldWert->cWert}" {if $step === 'formular'}{if !empty($cKundenattribut_arr[$kKundenfeld]->cWert) && $oKundenfeldWert->cWert === $cKundenattribut_arr[$kKundenfeld]->cWert}selected{/if}{else}{if !empty($Kunde->cKundenattribut_arr[$kKundenfeld]->cWert) && $oKundenfeldWert->cWert === $Kunde->cKundenattribut_arr[$kKundenfeld]->cWert}selected{/if}{/if}>{$oKundenfeldWert->cWert}</option>
                                    {/foreach}
                                </select>
                            {/if}
                        </div>
                    {/if}
                {/if}
            {/foreach}
        </div>
    </div>
</fieldset>
{/if}
{if !isset($fehlendeAngaben)}
    {assign var=fehlendeAngaben value=array()}
{/if}
{if !isset($cPost_arr)}
    {assign var=cPost_arr value=array()}
{/if}
{hasCheckBoxForLocation nAnzeigeOrt=$nAnzeigeOrt cPlausi_arr=$fehlendeAngaben cPost_arr=$cPost_arr bReturn="bHasCheckbox"}
{if $bHasCheckbox}
<fieldset>
    {include file='snippets/checkbox.tpl' nAnzeigeOrt=$nAnzeigeOrt cPlausi_arr=$fehlendeAngaben cPost_arr=$cPost_arr}
</fieldset>
{/if}

{if (!isset($smarty.session.bAnti_spam_already_checked) || $smarty.session.bAnti_spam_already_checked !== true) && 
    isset($Einstellungen.global.anti_spam_method) && $Einstellungen.global.anti_spam_method !== 'N' &&
    isset($Einstellungen.kunden.registrieren_captcha) && $Einstellungen.kunden.registrieren_captcha !== 'N' && empty($Kunde->kKunde)}
    <hr>
    {if isset($fehlendeAngaben.captcha) && $fehlendeAngaben.captcha != false}
        <div class="alert alert-danger" role="alert">{lang key="invalidToken" section="global"}</div>
    {/if}
    <div class="g-recaptcha" data-sitekey="{$Einstellungen.global.global_google_recaptcha_public}"></div>
    <hr>
{/if}