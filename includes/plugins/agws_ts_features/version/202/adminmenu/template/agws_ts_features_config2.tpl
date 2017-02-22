{if $ts_id_all_arr|@count != 0}
    <div id="ts_features_wrapper2">
        {if $ts_message !=""}
            <div class="{$ts_message_class}">
                <span>{$ts_message}</span>
            </div>
        {/if}

        <form name="ts_id_options" action="" method="post">
            {foreach from=$ts_id_all_arr item=ts_id_all}
            <fieldset>
                <ul class="input_block">
                    <li>
                        <label for="ts-id">TS-ID:</label>
                        <input type="text" name="ts_id" id="ts_id" size="50" readonly="readonly" value="{$ts_id}"/>
                    </li>
                    <li class="spacer"></li>
                    <li>
                        <label for="ts-sprache"><b>Shop-Sprache:</b></label>
                        <select name="ts_sprache">
                            <option value="0" disabled {if $ts_id_all->iTS_Sprache==0}selected="selected"{/if}>Wähle Shop-Sprache</option>
                            {foreach from=$ts_id_shopsprachen item=ts_id_sprache}
                                <option value="{$ts_id_sprache->kSprache}" {if $ts_id_sprache->kSprache == $ts_id_all->iTS_Sprache}selected="selected"{/if}>{$ts_id_sprache->cNameDeutsch}</option>
                            {/foreach}
                        </select>
                    </li>
                    <li class="spacer"></li>
                    <li>
                        <legend>Konfigurieren Sie Ihr Trustbadge</legend>
                    </li>
                    <li>
                    <span style="float: left;">
                        In unserem Integration Center finden Sie eine Schritt-für-Schritt Anleitung passend zu Ihrem Shopsystem.<br /> Klicken Sie <a href="https://www.trustedshops.com/integration/?shop_id={$ts_id}&backend_language={$smarty.const.TS_URL_BACKEND_LANGUAGE}&shopsw={$smarty.const.TS_URL_SHOPSW}&shopsw_version={$smarty.const.TS_URL_SHOPSW_VERSION}&plugin_version={$smarty.const.TS_URL_PLUGIN_VERSION}&context=trustbadge" target="_blank">hier</a>.
                    </span>
                    </li>
                    <li>
                        <textarea rows="10" cols="120" name="ts_BadgeCode" placeholder="Bitte TrustBadge-Code hier einfügen ...">{$ts_id_all->cTS_BadgeCode}</textarea>
                    </li>
                    <li>
                    <span style="float: left;">
                        Sofern keine weiteren Installationsschritte im Integration Center beschrieben wurden, wird das Trustbadge im Frontend Ihres Shops angezeigt, sobald Sie die Änderungen speichern.
                    </span>
                    </li>
                    <li class="spacer"></li>
                    <li>
                        <legend>Kundenbewertungen konfigurieren</legend>
                    </li>
                    <li>
                        <label>Kundenbewertungswidget aktiveren</label>
                        <span>
                            <input type="hidden" name="ts_RatingWidgetShow" value="0">
                            <input type="checkbox" name="ts_RatingWidgetShow" value="1" {if $ts_id_all->bTS_RatingWidgetShow==1}checked="checked"{/if} >
                            <select name="ts_RatingWidgetPosition">
                                <option value="2" {if $ts_id_all->iTS_RatingWidgetPosition=="2"}selected="selected"{/if}>Sidebar rechts (empfohlen)</option>
                                <option value="1" {if $ts_id_all->iTS_RatingWidgetPosition=="1"}selected="selected"{/if}>Sidebar links</option>
                                <option value="3" {if $ts_id_all->iTS_RatingWidgetPosition=="3"}selected="selected"{/if}>Footer</option>
                            </select>
                        </span>
                    </li>
                    <li>
                        <label>Review Sticker aktivieren</label>
                        <span>
                            <input type="hidden" name="ts_ReviewStickerShow" value="0">
                            <input type="checkbox" name="ts_ReviewStickerShow" value="1" {if $ts_id_all->bTS_ReviewStickerShow==1}checked="checked"{/if}>
                            <select name="ts_ReviewStickerPosition">
                                <option value="3" {if $ts_id_all->iTS_ReviewStickerPosition=="3"}selected="selected"{/if}>Footer (empfohlen)</option>
                                <option value="1" {if $ts_id_all->iTS_ReviewStickerPosition=="1"}selected="selected"{/if}>Sidebar links</option>
                                <option value="2" {if $ts_id_all->iTS_ReviewStickerPosition=="2"}selected="selected"{/if}>Sidebar rechts</option>
                            </select>
                        </span>
                    </li>
                    <li class="spacer"></li>
                    <li>
                        <legend>Rich Snippets konfigurieren</legend>
                    </li>
                    <li>
                        <label>Rich Snippets Anzeige in Google aktivieren</label>
                        <small><a href="https://support.google.com/webmasters/answer/146750?hl=en" target="_blank">Google Rich Snippets Dokumentation</a></small>
                        <span>
                            <input type="hidden" name="ts_RichSnippetsCategory" value="0">
                            <input type="checkbox" name="ts_RichSnippetsCategory" value="1" {if $ts_id_all->bTS_RichSnippetsCategory==1}checked="checked"{/if}><span>&nbsp;Kategorieseiten</span><br/>
                            <input type="hidden" name="ts_RichSnippetsProduct" value="0">
                            <input type="checkbox" name="ts_RichSnippetsProduct" value="1" {if $ts_id_all->bTS_RichSnippetsProduct==1}checked="checked"{/if}><span>&nbsp;Produktdetailseiten</span><br/>
                            <input type="hidden" name="ts_RichSnippetsMain" value="0">
                            <input type="checkbox" name="ts_RichSnippetsMain" value="1" {if $ts_id_all->bTS_RichSnippetsMain==1}checked="checked"{/if}><span>&nbsp;Startseite (nicht empfohlen)</span>
                        </span>
                    </li>
                    <li class="clear">
                        <a href="#" onclick="return false;" class="button" id="btn_show_reviewstickercode">Zeige weitere Optionen</a>
                    </li>
                    <hr>
                    <div id="hide_reviewstickercode">
                        <li>
                            <label>Review Sticker Code editieren</label>
                            <textarea rows="10" cols="120" name="ts_ReviewStickerCode" placeholder="Bitte ReviewSticker-Code hier einfügen ...">{$ts_id_all->cTS_ReviewStickerCode}</textarea>
                        </li>
                        <li class="clear">
                            <a href="#" onclick="return false;" class="button" id="btn_hide_reviewstickercode">Schließe weitere Optionen</a>
                        </li>
                    </div>
                    <li class="spacer"></li>
                    <li style="float: right;">
                        <input type="reset" id="btn_ts_id_cancel" value="Abbrechen" class="button reset" name="ts_id_cancel">
                        <input type="submit" id="btn_ts_id_save" value="Speichern" class="button add" name="ts_id_save">
                    </li>
                </ul>
            </fieldset>
            {/foreach}
        </form>
    </div>

    <script type="text/javascript">
        {literal}
        $(document).ready(function(){
            $('#btn_show_reviewstickercode').click(function(){
                $('#hide_reviewstickercode').fadeIn({duration: "fast", easing: "linear"});
            })

            $('#btn_hide_reviewstickercode').click(function(){
                $('#hide_reviewstickercode').hide();
            })

            $('#hide_reviewstickercode').hide();

            $('#btn_ts_id_cancel').click(function(){
                $('form[name=ts_id_options]').attr('action','{/literal}{$ts_id_cancel_form_action}{literal}');
                $('form[name=ts_id_options]').append('<input type="hidden" name="ts_id_options_cancel" value="1">');
                $('form[name=ts_id_options]').submit();
            });
            $('#btn_ts_id_save').click(function(){
                $('form[name=ts_id_options]').attr('action','{/literal}{$ts_id_save_form_action}{literal}');
                $('form[name=ts_id_options]').append('<input type="hidden" name="ts_id_options_save" value="1">');
                $('form[name=ts_id_options]').submit();
            });
        });
        {/literal}
    </script>
{else}
    <div class="box_info">
        <span>Bitte fügen Sie eine <b>neue TS-ID</b> ein oder wählen Sie aus der Liste der <b>installierten TS-IDs</b> eine aus!</span>
    </div>

{/if}

