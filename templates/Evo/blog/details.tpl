{if !empty($hinweis)}
    <div class="alert alert-info">{$hinweis}</div>
{/if}
{if !empty($fehler)}
    <div class="alert alert-danger">{$fehler}</div>
{/if}
{include file="snippets/extension.tpl"}

{if isset($cNewsErr) && $cNewsErr !== ""}
    <div class="alert alert-danger">{lang key="newsRestricted" section="news"}</div>
{else}
    <h1>{$oNewsArchiv->cBetreff}
        <small class="date text-muted">
            {$oNewsArchiv->Datum}
        </small>
    </h1>

    <div itemprop="articleBody" class="panel-strap">
        {$oNewsArchiv->cText}
    </div>

    {if isset($Einstellungen.news.news_kategorie_unternewsanzeigen) && $Einstellungen.news.news_kategorie_unternewsanzeigen === 'Y' && !empty($oNewsKategorie_arr)}
        <div class="top10 news-categorylist">
            {foreach name=newskategorie from=$oNewsKategorie_arr item=oNewsKategorie}
                <a href="{$oNewsKategorie->cURL}" title="{$oNewsKategorie->cBeschreibung|strip_tags|escape:"html"|truncate:60}" class="badge">{$oNewsKategorie->cName}</a>
            {/foreach}
        </div>
    {/if}

    {if isset($Einstellungen.news.news_kommentare_nutzen) && $Einstellungen.news.news_kommentare_nutzen === 'Y'}
        {if $oNewsKommentar_arr|@count > 0}
            <hr>
            <div class="top10" id="comments">
                <h3 class="section-heading">{lang key="newsComments" section="news"}</h3>
                {foreach name=kommentare from=$oNewsKommentar_arr item=oNewsKommentar}
                    <blockquote class="news-comment">
                        <p>
                            {$oNewsKommentar->cKommentar}
                        </p>
                        <small>
                            {if !empty($oNewsKommentar->cVorname)}
                                {$oNewsKommentar->cVorname} {$oNewsKommentar->cNachname|truncate:1:''}.,
                            {else}
                                {$oNewsKommentar->cName},
                            {/if}
                            {if $smarty.session.cISOSprache === 'ger'}
                                {$oNewsKommentar->dErstellt_de}
                            {else}
                                {$oNewsKommentar->dErstellt}
                            {/if}
                        </small>
                    </blockquote>
                {/foreach}
            </div>

            {if isset($oBlaetterNavi->nAktiv) && $oBlaetterNavi->nAktiv == 1}
                <div class="row">
                    <div class="col-xs-7 col-md-8 col-lg-9">
                        <ul class="pagination">
                            {if $oBlaetterNavi->nAktuelleSeite == 1}
                                <li><span>&laquo; {lang key="newsNaviBack" section="news"}</span></li>
                            {else}
                                <li>
                                    <a href="news.php?s={$oBlaetterNavi->nVoherige}&kNews={$oNewsArchiv->kNews}&n={$oNewsArchiv->kNews}"><span>&laquo; {lang key="newsNaviBack" section="news"}</span></a>
                                </li>
                            {/if}
                            {if $oBlaetterNavi->nAnfang != 0}
                                <li>
                                    <a href="news.php?s={$oBlaetterNavi->nAnfang}&kNews={$oNewsArchiv->kNews}&n={$oNewsArchiv->kNews}">{$oBlaetterNavi->nAnfang}</a>
                                </li>
                            {/if}
                            {foreach name=blaetternavi from=$oBlaetterNavi->nBlaetterAnzahl_arr item=Blatt}
                                {if $oBlaetterNavi->nAktuelleSeite == $Blatt}
                                    <li class="active"><span>{$Blatt}</span></li>
                                {else}
                                    <li><a href="news.php?s={$Blatt}&kNews={$oNewsArchiv->kNews}&n={$oNewsArchiv->kNews}">{$Blatt}</a></li>
                                {/if}
                            {/foreach}

                            {if $oBlaetterNavi->nEnde != 0}
                                <li>
                                    <a href="news.php?s={$oBlaetterNavi->nEnde}&kNews={$oNewsArchiv->kNews}&n={$oNewsArchiv->kNews}">{$oBlaetterNavi->nEnde}</a>
                                </li>
                            {/if}

                            {if $oBlaetterNavi->nAktuelleSeite == $oBlaetterNavi->nSeiten}
                                <li><span>{lang key="newsNaviNext" section="news"} &raquo;</span></li>
                            {else}
                                <li>
                                    <a href="news.php?s={$oBlaetterNavi->nNaechste}&kNews={$oNewsArchiv->kNews}&n={$oNewsArchiv->kNews}"><span>{lang key="newsNaviNext" section="news"} &raquo;</span></a>
                                </li>
                            {/if}
                        </ul>
                    </div>
                    <div class="col-xs-6 col-md-4 col-lg-3 text-right">
                        <div class="pagination pagination-text">
                            {$oBlaetterNavi->nVon} - {$oBlaetterNavi->nBis} {lang key="from" section="product rating"} {$oBlaetterNavi->nAnzahl}
                        </div>
                    </div>
                </div>
            {/if}
        {/if}

        {if ($Einstellungen.news.news_kommentare_eingeloggt === 'Y' && !empty($smarty.session.Kunde->kKunde)) || $Einstellungen.news.news_kommentare_eingeloggt !== 'Y'}
            <hr>
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <div class="well panel-wrap">
                        <div class="panel panel-default">
                            <div class="panel-heading"><h4 class="panel-title">{lang key="newsCommentAdd" section="news"}</h4></div>
                            <div class="panel-body">
                                <form method="post" action="{if !empty($oNewsArchiv->cSeo)}{$ShopURL}/{$oNewsArchiv->cSeo}{else}news.php{/if}" class="form" id="news-addcomment">
                                    {$jtl_token}
                                    <input type="hidden" name="kNews" value="{$oNewsArchiv->kNews}" />
                                    <input type="hidden" name="kommentar_einfuegen" value="1" />
                                    <input type="hidden" name="s" value="{if isset($oBlaetterNavi->nAktuelleSeite)}{$oBlaetterNavi->nAktuelleSeite}{/if}" />
                                    <input type="hidden" name="n" value="{$oNewsArchiv->kNews}" />

                                    <fieldset>
                                        {if $Einstellungen.news.news_kommentare_eingeloggt === 'N'}
                                            {if empty($smarty.session.Kunde->kKunde) || $smarty.session.Kunde->kKunde == 0}
                                                <div class="row">
                                                    <div class="col-xs-12 col-md-6">
                                                        <div id="commentName" class="form-group float-label-control{if isset($nPlausiValue_arr.cName)} has-error{/if} required">
                                                            <label class="control-label commentForm" for="comment-name">{lang key="newsName" section="news"}</label>
                                                            <input class="form-control" required id="comment-name" name="cName" type="text" value="{if !empty($cPostVar_arr.cName)}{$cPostVar_arr.cName}{/if}" />
                                                            {if isset($nPlausiValue_arr.cName)}
                                                                <div class="alert alert-danger">
                                                                    {lang key="fillOut" section="global"}
                                                                </div>
                                                            {/if}
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-6">
                                                        <div id="commentEmail" class="form-group float-label-control{if isset($nPlausiValue_arr.cEmail)} has-error{/if} required">
                                                            <label class="control-label commentForm" for="comment-email">{lang key="newsEmail" section="news"}</label>
                                                            <input class="form-control" required id="comment-email" name="cEmail" type="text" value="{if !empty($cPostVar_arr.cEmail)}{$cPostVar_arr.cEmail}{/if}" />
                                                            {if isset($nPlausiValue_arr.cEmail)}
                                                                <div class="alert alert-danger">
                                                                    {lang key="fillOut" section="global"}
                                                                </div>
                                                            {/if}
                                                        </div>
                                                    </div>
                                                </div>
                                            {/if}

                                            <div id="commentText" class="form-group float-label-control{if isset($nPlausiValue_arr.cKommentar)} has-error{/if} required">
                                                <label class="control-label commentForm" for="comment-text">{lang key="newsComment" section="news"}</label>
                                                <textarea id="comment-text" required class="form-control" name="cKommentar">{if !empty($cPostVar_arr.cKommentar)}{$cPostVar_arr.cKommentar}{/if}</textarea>
                                                {if isset($nPlausiValue_arr.cKommentar)}
                                                    <div class="alert alert-danger">
                                                        {lang key="fillOut" section="global"}
                                                    </div>
                                                {/if}
                                            </div>

                                            <div class="form-group float-label-control">
                                                {if (!isset($smarty.session.bAnti_spam_already_checked) || $smarty.session.bAnti_spam_already_checked !== true) &&
                                                    isset($Einstellungen.global.anti_spam_method) && $Einstellungen.global.anti_spam_method !== 'N' &&
                                                    isset($Einstellungen.news.news_sicherheitscode) && $Einstellungen.news.news_sicherheitscode !== 'N' && empty($smarty.session.Kunde->kKunde)}
                                                    {if !empty($nPlausiValue_arr.captcha)}
                                                        <div class="alert alert-danger" role="alert">{lang key="invalidToken" section="global"}</div>
                                                    {/if}
                                                    <div class="g-recaptcha" data-sitekey="{$Einstellungen.global.global_google_recaptcha_public}"></div>
                                                {/if}
                                            </div>

                                            <input class="btn btn-primary" name="speichern" type="submit" value="{lang key="newsCommentSave" section="news"}" />
                                        {elseif $Einstellungen.news.news_kommentare_eingeloggt === 'Y' && !empty($smarty.session.Kunde->kKunde)}
                                            <div class="form-group float-label-control required">
                                                <label class="control-label" for="comment-text"><strong>{lang key="newsComment" section="news"}</strong></label>
                                                <textarea id="comment-text" class="form-control" name="cKommentar" required></textarea>
                                            </div>
                                            <input class="btn btn-primary" name="speichern" type="submit" value="{lang key="newsCommentSave" section="news"}" />
                                        {/if}
                                    </fieldset>
                                </form>
                            </div>
                        </div>{* /panel *}
                    </div>{* /well *}
                </div>
            </div>
        {else}
            <hr>
            <div class="alert alert-danger">{lang key="newsLogin" section="news"}</div>
        {/if}
    {/if}
{/if}
