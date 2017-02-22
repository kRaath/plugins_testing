{assign var="is_dropdown" value=false}
{if ($Merkmal->cTyp === 'SELECTBOX') && $Merkmal->oMerkmalWerte_arr|@count > 1}
    {assign var="is_dropdown" value=true}
{/if}

<ul {if $is_dropdown}class="dropdown-menu" role="menu" {elseif isset($class)}class="{$class}" {else}class="nav nav-list"{/if}>
    {foreach name=filter from=$Merkmal->oMerkmalWerte_arr item=MerkmalWert}
        {assign var=kMerkmalWert value=$MerkmalWert->kMerkmalWert}
        {if $MerkmalWert->nAktiv}
            <li class="active">
                <a rel="nofollow" href="{if !empty($MerkmalWert->cURL)}{$MerkmalWert->cURL}{else}#{/if}"{if $Merkmal->cTyp === 'BILD'} title="{$MerkmalWert->cWert}"{/if}>
                    <i class="fa fa-check-square-o text-muted"></i>
                    {if $MerkmalWert->cBildpfadKlein !== 'gfx/keinBild_kl.gif' && $Merkmal->cTyp !== 'TEXT'}
                        <img src="{$MerkmalWert->cBildpfadKlein}" alt="" class="vmiddle" />
                    {/if}
                    {if $Merkmal->cTyp !== 'BILD'}
                        {$MerkmalWert->cWert}
                    {/if}
                    <span class="badge">{$MerkmalWert->nAnzahl}</span>
                </a>
            </li>
        {else}
            <li>
                <a rel="nofollow" href="{$MerkmalWert->cURL}"{if $Merkmal->cTyp === 'BILD'} title="{$MerkmalWert->cWert}"{/if}>
                    <i class="fa fa-square-o text-muted"></i>
                    {if $MerkmalWert->cBildpfadKlein !== 'gfx/keinBild_kl.gif' && $Merkmal->cTyp !== 'TEXT'}
                        <img src="{$MerkmalWert->cBildpfadKlein}" alt="" class="vmiddle" />
                    {/if}
                    {if $Merkmal->cTyp !== 'BILD'}
                        {$MerkmalWert->cWert}
                    {/if}
                    <span class="badge">{$MerkmalWert->nAnzahl}</span>
                </a>
            </li>
        {/if}
    {/foreach}
</ul>