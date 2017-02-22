<link rel="stylesheet" type="text/css" href="{$URL_ADMINMENU}/template/css/agws_ts_features_main.css">

<div id="ts_features_wrapper">
    {if $ts_id_all_arr|@count == 0}
    <div id="ts-image">
        <a title="Trusted Shops Registration Centre" href="https://www.trustedshops.com/integration/?backend_language={$smarty.const.TS_URL_BACKEND_LANGUAGE}&shopsw={$smarty.const.TS_URL_SHOPSW}&shopsw_version={$smarty.const.TS_URL_SHOPSW_VERSION}&plugin_version={$smarty.const.TS_URL_PLUGIN_VERSION}&context=membership&" target="_blank">
            <img src="{$URL_ADMINMENU}/template/image/{$smarty.const.TS_GRAFIK_FILENAME}">
        </a>
    </div>
    {/if}

    {if $ts_message !=""}
        <div class="{$ts_message_class}">
            <span>{$ts_message}</span>
        </div>
    {/if}
    <div id="ts-id-add">
        <form name="ts_id_add" action="{$ts_id_add_form_action}" method="post">
            <fieldset>
                <legend>Trusted Shops IDs verwalten</legend>
                <ul class="input_block">
                    <li>
                        <label for="first">Neue TS-ID:</label>
                        <input type="text" name="ts_id" id="ts_id" size="50" value="" placeholder="Trusted Shops ID einf�gen�"/>
                        <input name="ts_id_add" type="submit" class="button add" value="Hinzuf�gen"/>
                    </li>
                </ul>
            </fieldset>
            <input type="hidden" name="ts_id_is_add" value="1">
            <input type="hidden" name="kPlugin" value="{$oPlugin->kPlugin}">
        </form>
    </div>
    <div id="ts-id-all">
        <table>
            <thead>
            <th>Installierte TS-IDs</th>
            <th>Sprache</th>
            <th style="width:100px;">&nbsp;</th>
            <th style="width:100px;">&nbsp;</th>
            </thead>
            <tbody>
            {if $ts_id_all_arr|@count != 0}
                {foreach from=$ts_id_all_arr item=ts_id_all}

                    {assign var="ts_id_config_error" value="0"}
                    {if ($ts_id_all->iTS_Sprache=="0" || $ts_id_all->cNameDeutsch=="" || $ts_id_all->cTS_BadgeCode=="")}{assign var="ts_id_config_error" value="1"}{/if}

                    <tr>

                        <td>{$ts_id_all->cTS_ID}</td>
                        <td>{if $ts_id_config_error=='1'}<span class="error">Erweiterte Konfiguration pr�fen!</span>{else}<span>{$ts_id_all->cNameDeutsch}</span>{/if}</td>
                        <td>
                            <form name="ts_id_edit" action="{$ts_id_edit_form_action}" method="post">
                                <input type="hidden" name="kPlugin" value="{$oPlugin->kPlugin}">
                                <input type="hidden" name="ts_id" value="{$ts_id_all->cTS_ID}" />
                                <input type="hidden" name="ts_id_is_edit" value="1" />
                                <input name="ts_id_edit" type="submit" class="button edit" value="�ndern"/>
                            </form>
                        </td>
                        <td>
                            <form name="ts_id_delete" action="{$ts_id_delete_form_action}" method="post">
                                <input type="hidden" name="kPlugin" value="{$oPlugin->kPlugin}">
                                <input type="hidden" name="ts_id" value="{$ts_id_all->cTS_ID}" />
                                <input type="hidden" name="ts_id_is_delete" value="1" />
                                <input name="ts_id_delete" type="submit" class="button delete" value="L�schen"/>
                            </form>
                        </td>
                    </tr>
                {/foreach}
            {else}
                <tr>
                    <td colspan="4"><span class="info">Es wurden noch keine TS-IDs installiert</span></td>
                </tr>
            {/if}
            </tbody>
        </table>
    </div>
</div>
