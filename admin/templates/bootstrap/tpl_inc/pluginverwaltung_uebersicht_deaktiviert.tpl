<div id="deaktiviert" class="tab-pane fade {if isset($cTab) && $cTab === 'deaktiviert'} active in{/if}">
    {if $PluginInstalliertByStatus_arr.status_1|@count > 0}
        <form name="pluginverwaltung" method="post" action="pluginverwaltung.php">
            {$jtl_token}
            <input type="hidden" name="pluginverwaltung_uebersicht" value="1" />
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{#pluginListNotActivated#}</h3>
                </div>
                <div class="table-responsive">
                    <table class="list table">
                        <thead>
                        <tr>
                            <th></th>
                            <th class="tleft">{#pluginName#}</th>
                            <th>{#status#}</th>
                            <th>{#pluginVersion#}</th>
                            <th>{#pluginInstalled#}</th>
                            <th>{#pluginFolder#}</th>
                            <th>{#pluginEditLocales#}</th>
                            <th>{#pluginEditLinkgrps#}</th>
                            <th>{#pluginBtnLicence#}</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$PluginInstalliertByStatus_arr.status_1 item=PluginInstalliert}
                            <tr {if isset($PluginInstalliert->dUpdate) && $PluginInstalliert->dUpdate|count_characters > 0 && $PluginInstalliert->cUpdateFehler == 1}class="highlight"{/if}>
                                <td class="check">
                                    <input type="checkbox" name="kPlugin[]" id="plugin-check-{$PluginInstalliert->kPlugin}" value="{$PluginInstalliert->kPlugin}" />
                                </td>
                                <td>
                                    <label for="plugin-check-{$PluginInstalliert->kPlugin}">{$PluginInstalliert->cName}</label
                                    {if (isset($PluginInstalliert->dUpdate) && $PluginInstalliert->dUpdate|count_characters > 0) || (isset($PluginInstalliert->cInfo) && $PluginInstalliert->cInfo|count_characters > 0)}
                                        <p>
                                            {if $PluginInstalliert->cUpdateFehler == 1}
                                                {if isset($PluginInstalliert->cInfo) && $PluginInstalliert->cInfo|count_characters > 0}{$PluginInstalliert->cInfo}<br />{/if}{#pluginUpdateExists#}
                                            {else}
                                                {if isset($PluginInstalliert->cInfo) && $PluginInstalliert->cInfo|count_characters > 0}{$PluginInstalliert->cInfo}<br />{/if}{#pluginUpdateExists#}. <br />{#pluginUpdateExistsError#}: <br />{$PluginInstalliert->cUpdateFehler}
                                            {/if}
                                        </p>
                                    {/if}
                                </td>
                                <td class="tcenter plugin-status">
                                    <h4 class="label-wrap">
                                        <span class="label {if $PluginInstalliert->nStatus == 2}success label-success{elseif $PluginInstalliert->nStatus == 1}success label-info{elseif $PluginInstalliert->nStatus == 3}success label-default{elseif $PluginInstalliert->nStatus == 4 || $PluginInstalliert->nStatus == 5}info label-info{elseif $PluginInstalliert->nStatus == 6}danger label-danger{/if}">
                                            {$PluginInstalliert->cStatus}
                                        </span>
                                    </h4>
                                </td>
                                <td class="tcenter plugin-version">{$PluginInstalliert->dVersion}{if isset($PluginInstalliert->dUpdate) && $PluginInstalliert->dUpdate|count_characters > 0} <span class="label labe-success update-info">{$PluginInstalliert->dUpdate}</span>{/if}</td>
                                <td class="tcenter plugin-install-date">{$PluginInstalliert->dInstalliert_DE}</td>
                                <td class="tcenter plugin-folder">{$PluginInstalliert->cVerzeichnis}</td>
                                <td class="tcenter plugin-lang-vars">
                                    {if isset($PluginInstalliert->oPluginSprachvariableAssoc_arr) && $PluginInstalliert->oPluginSprachvariableAssoc_arr|@count > 0}
                                        <a href="pluginverwaltung.php?pluginverwaltung_uebersicht=1&sprachvariablen=1&kPlugin={$PluginInstalliert->kPlugin}" class="btn btn-default btn-sm" title="{#pluginEdit#}"><i class="fa fa-edit"></i></a>
                                    {/if}
                                </td>
                                <td class="tcenter plugin-frontend-links">
                                    {if isset($PluginInstalliert->oPluginFrontendLink_arr) && $PluginInstalliert->oPluginFrontendLink_arr|@count > 0}
                                        <a href="links.php?kPlugin={$PluginInstalliert->kPlugin}" class="btn btn-default btn-sm" title="{#pluginEdit#}"><i class="fa fa-edit"></i></a>
                                    {/if}
                                </td>
                                <td class="tcenter plugin-license">
                                    {if isset($PluginInstalliert->cLizenzKlasse) && $PluginInstalliert->cLizenzKlasse|count_characters > 0}
                                        {if isset($PluginInstalliert->cLizenzKlasse) && $PluginInstalliert->cLizenzKlasse|count_characters > 0}
                                            <button name="lizenzkey" type="submit" class="btn {if $PluginInstalliert->cLizenz && $PluginInstalliert->cLizenz|count_characters > 0}btn-default{else}btn-primary{/if} btn-sm" value="{$PluginInstalliert->kPlugin}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        {/if}
                                    {/if}
                                </td>
                                <td class="tcenter">
                                    {if isset($PluginInstalliert->dUpdate) && $PluginInstalliert->dUpdate|count_characters > 0 && $PluginInstalliert->cUpdateFehler == 1}
                                        <a onclick="ackCheck({$PluginInstalliert->kPlugin}, '#deaktiviert'); return false;" class="btn btn-primary btn-sm" title="{#pluginBtnUpdate#}"><i class="fa fa-refresh"></i></a>
                                    {/if}
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                        <tfoot>
                        <tr>
                            <td class="check"><input name="ALLMSGS" id="ALLMSGS2" type="checkbox" onclick="AllMessages(this.form);" /></td>
                            <td colspan="10"><label for="ALLMSGS2">{#pluginSelectAll#}</label></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="panel-footer">
                    <div class="save btn-group">
                        <button name="aktivieren" type="submit" class="btn btn-primary"><i class="fa fa-share"></i> {#pluginBtnActivate#}</button>
                        {*<button name="deaktivieren" type="submit" class="btn btn-warning">{#pluginBtnDeActivate#}</button>*}
                        <button name="deinstallieren" type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> {#pluginBtnDeInstall#}</button>
                    </div>
                </div>
            </div>
        </form>
    {else}
        <div class="alert alert-info" role="alert">{#noDataAvailable#}</div>
    {/if}
</div>