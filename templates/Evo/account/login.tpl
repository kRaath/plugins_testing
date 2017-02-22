<h1>{if !empty($oRedirect->cName)}{$oRedirect->cName}{else}{lang key="loginTitle" section="login"}{/if}</h1>
{if !$bCookieErlaubt}
    <div class="alert alert-danger hidden" id="no-cookies-warning" style="display:none;">
     <strong>{lang key="noCookieHeader" section="errorMessages"}</strong>
     <p>{lang key="noCookieDesc" section="errorMessages"}</p>
    </div>
    <script type="text/javascript">
       $(function() {ldelim}
           if (navigator.cookieEnabled === false) {ldelim}
               $('#no-cookies-warning').show();
           {rdelim}
       {rdelim});
    </script>
{else}
    {if !empty($cHinweis)}
        <div class="alert alert-info">{$cHinweis}</div>
    {else}
        <div class="alert alert-info">{lang key="loginDesc" section="login"} {if isset($oRedirect) && $oRedirect->cName}{lang key="redirectDesc1" section="global"} {$oRedirect->cName} {lang key="redirectDesc2" section="global"}.{/if}</div>
    {/if}
{/if}

{include file="snippets/extension.tpl"}

<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
        {block name="login-form"}
        <div class="well panel-wrap">
            <div class="panel">
                <div class="panel-body">
                    <form id="login_form" action="jtl.php" method="post" role="form">
                        {$jtl_token}
                        <fieldset>
                            <legend>{lang section="checkout" key="loginForRegisteredCustomers"}</legend>
                            <div class="form-group float-label-control required">
                                <label for="email" class="control-label">{lang key="emailadress" section="global"}</label>
                                <input
                                type="text"
                                name="email"
                                id="email"
                                class="form-control"
                                placeholder="{lang key="emailadress" section="global"}*"
                                required
                                />
                            </div>
                            <div class="form-group float-label-control required">
                                <label for="password" class="control-label">{lang key="password" section="account data"}</label>
                                <input
                                type="password"
                                name="passwort"
                                id="password"
                                class="form-control"
                                placeholder="{lang key="password" section="account data"}"
                                required
                                />
                            </div>

                            {if $showLoginCaptcha}
                                <div class="form-group text-center float-label-control">
                                    <div class="g-recaptcha" data-sitekey="{$Einstellungen.global.global_google_recaptcha_public}"></div>
                                </div>
                            {/if}

                            <div class="form-group">
                                <input type="hidden" name="login" value="1" />
                                {if !empty($oRedirect->cURL)}
                                    {foreach name=parameter from=$oRedirect->oParameter_arr item=oParameter}
                                        <input type="hidden" name="{$oParameter->Name}" value="{$oParameter->Wert}" />
                                    {/foreach}
                                    <input type="hidden" name="r" value="{$oRedirect->nRedirect}" />
                                    <input type="hidden" name="cURL" value="{$oRedirect->cURL}" />
                                {/if}
                                <input type="submit" value="{lang key="login" section="checkout"}" class="btn btn-primary btn-block submit"/>
                            </div>

                            <div class="clearfix"></div>
                            <div class="register-or-resetpw top15">
                                <small>
                                   <a class="register pull-left" href="registrieren.php"><span class="fa fa-pencil"></span> {lang key="newHere" section="global"} {lang key="registerNow" section="global"}</a>
                                   <a class="resetpw  pull-right" href="pass.php"><span class="fa fa-question-circle"></span> {lang key="forgotPassword" section="global"}</a>
                                </small>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        {/block}
    </div>
</div>
