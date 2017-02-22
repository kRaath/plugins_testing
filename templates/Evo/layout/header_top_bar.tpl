{nocache}
{strip}
{if isset($smarty.session.Waehrungen) && $smarty.session.Waehrungen|@count > 1 || isset($smarty.session.Sprachen) && $smarty.session.Sprachen|@count > 1}
    {block name="top-bar-user-settings"}
    <ul class="list-inline user-settings pull-right">
        {if isset($smarty.session.Waehrungen) && $smarty.session.Waehrungen|@count > 1}
            <li class="currency-dropdown dropdown">
                <a href="#" class="dropdown-toggle btn btn-default btn-xs" data-toggle="dropdown">{if $smarty.session.Waehrung->cName == 'EUR'}<i class="fa fa-eur" title="{$smarty.session.Waehrung->cName}"></i>{else}{$smarty.session.Waehrung->cName}{/if} <span class="caret"></span></a>
                <ul id="currency-dropdown" class="dropdown-menu dropdown-menu-right">
                {foreach from=$smarty.session.Waehrungen item=oWaehrung}
                    <li>
                        <a href="{$oWaehrung->cURL}" rel="nofollow">{$oWaehrung->cName}</a>
                    </li>
                {/foreach}
                </ul>
            </li>
        {/if}
        {if isset($smarty.session.Sprachen) && $smarty.session.Sprachen|@count > 1}
        <li class="language-dropdown dropdown">
            <a href="#" class="dropdown-toggle btn btn-default btn-xs" data-toggle="dropdown">
                <i class="fa fa-language"></i>
                {foreach from=$smarty.session.Sprachen item=Sprache}
                    {if $Sprache->kSprache == $smarty.session.kSprache}
                        <span="lang-{$lang}"> {if $lang === 'ger'}{$Sprache->cNameDeutsch}{else}{$Sprache->cNameEnglisch}{/if}</span>
                    {/if}
                {/foreach}
                <span class="caret"></span>
            </a>
            <ul id="language-dropdown" class="dropdown-menu dropdown-menu-right">
            {foreach from=$smarty.session.Sprachen item=oSprache}
                {if $oSprache->kSprache != $smarty.session.kSprache}
                    <li>
                        <a href="{$oSprache->cURL}" class="link_lang {$oSprache->cISO}" rel="nofollow">{if $lang === 'ger'}{$oSprache->cNameDeutsch}{else}{$oSprache->cNameEnglisch}{/if}</a>
                    </li>
                {/if}
                {/foreach}
            </ul>
        </li>
        {* /language-dropdown *}
        {/if}
    </ul>{* user-settings *}
    {/block}
{/if}
{if isset($linkgroups->Kopf) && $linkgroups->Kopf}
<ul class="cms-pages list-inline pull-right">
    {block name="top-bar-cms-pages"}
        {foreach name=headlinks from=$linkgroups->Kopf->Links item=Link}
            {if $Link->cLocalizedName|has_trans}
                <li class="{if isset($Link->aktiv) && $Link->aktiv == 1}active{/if}">
                    <a href="{$Link->URL}"{if $Link->cNoFollow == 'Y'} rel="nofollow"{/if}>{$Link->cLocalizedName|trans}</a>
                </li>
            {/if}
        {/foreach}
    {/block}
</ul>
{/if}
{/strip}
{/nocache}