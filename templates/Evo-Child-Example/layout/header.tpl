{* Beispiel-Datei zur Erweiterung des Evo-Templates header.tpl *}

{extends file="../../Evo/layout/header.tpl"}

{* Beispiel: Überschreiben des Page-Titles auf allen Seiten *}
{block name="head-title"}CHILD-TEMPLATE!{/block}

{* Beispiel: Anhängen eines Meta-Tags og:image im Head-Bereich auf Artikel-Seiten *}
{block name="head-resources" append}
    {if !empty($Artikel->Bilder)}
        <meta property="og:image" content="{$ShopURL}/{$Artikel->Bilder[0]->cPfadNormal}" />
    {/if}
{/block}