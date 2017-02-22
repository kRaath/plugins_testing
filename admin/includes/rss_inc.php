<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */

/**
 * @return bool
 */
function generiereRSSXML()
{
    Jtllog::writeLog("RSS wird erstellt", JTLLOG_LEVEL_NOTICE);
    $shopURL = Shop::getURL();
    if (is_writable(PFAD_ROOT . FILE_RSS_FEED)) {
        $Einstellungen = Shop::getSettings(array(CONF_RSS));
        if ($Einstellungen['rss']['rss_nutzen'] !== 'Y') {
            return false;
        }
        $Sprache = Shop::DB()->query("SELECT * FROM tsprache WHERE cShopStandard = 'Y'", 1);
        //$seoAktiv = pruefeSeo();
        $stdKundengruppe         = Shop::DB()->query("SELECT kKundengruppe FROM tkundengruppe WHERE cStandard = 'Y'", 1);
        $_SESSION['kSprache']    = $Sprache->kSprache;
        $_SESSION['cISOSprache'] = $Sprache->cISO;

        // ISO-8859-1

        $xml = '<?xml version="1.0" encoding="' . JTL_CHARSET . '"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title>' . $Einstellungen['rss']['rss_titel'] . '</title>
		<link>' . $shopURL . '</link>
		<description>' . $Einstellungen['rss']['rss_description'] . '</description>
		<language>' . StringHandler::convertISO2ISO639($Sprache->cISO) . '</language>
		<copyright>' . $Einstellungen['rss']['rss_copyright'] . '</copyright>
		<pubDate>' . date('r') . '</pubDate>
		<atom:link href="' . $shopURL . '/rss.xml" rel="self" type="application/rss+xml" />
		<image>
			<url>' . $Einstellungen['rss']['rss_logoURL'] . '</url>
			<title>' . $Einstellungen['rss']['rss_titel'] . '</title>
			<link>' . $shopURL . '</link>
		</image>';
        //Artikel STD Sprache
        $lagerfilter = gibLagerfilter();
        $alter_tage  = intval($Einstellungen['rss']['rss_alterTage']);
        if (!$alter_tage) {
            $alter_tage = 14;
        }
        // Artikel beachten?
        if ($Einstellungen['rss']['rss_artikel_beachten'] === 'Y') {
            $artikelarr = Shop::DB()->query(
                "SELECT tartikel.kArtikel, tartikel.cName, tartikel.cKurzBeschreibung, tseo.cSeo, tartikel.dLetzteAktualisierung,
                    tartikel.dErstellt, DATE_FORMAT(tartikel.dErstellt, \"%a, %d %b %Y %H:%i:%s UTC\") as erstellt
                    FROM tartikel
                    LEFT JOIN tartikelsichtbarkeit ON tartikel.kArtikel=tartikelsichtbarkeit.kArtikel
                        AND tartikelsichtbarkeit.kKundengruppe = $stdKundengruppe->kKundengruppe
                    LEFT JOIN tseo ON tseo.cKey = 'kArtikel'
                        AND tseo.kKey = tartikel.kArtikel
                        AND tseo.kSprache = " . (int)$_SESSION['kSprache'] . "
                    WHERE tartikelsichtbarkeit.kArtikel IS NULL
                        AND tartikel.cNeu='Y'
                        $lagerfilter
                        AND cNeu='Y' AND DATE_SUB(now(),INTERVAL " . $alter_tage . " DAY) < dErstellt
                    ORDER BY dLetzteAktualisierung DESC", 2
            );

            if (is_array($artikelarr) && count($artikelarr) > 0) {
                foreach ($artikelarr as $artikel) {
                    $xml .= '
		        <item>
			        <title>' . wandelXMLEntitiesUm($artikel->cName) . '</title>
			        <description>' . wandelXMLEntitiesUm($artikel->cKurzBeschreibung) . '</description>
			        <link>' . $shopURL . '/' . baueURL($artikel, URLART_ARTIKEL) . '</link>
			        <guid>' . $shopURL . '/' . baueURL($artikel, URLART_ARTIKEL) . '</guid>
			        <pubDate>' . bauerfc2822datum($artikel->dLetzteAktualisierung) . '</pubDate>
		        </item>';
                }
            }
        }

        // News beachten?
        if ($Einstellungen['rss']['rss_news_beachten'] === 'Y') {
            $oNews_arr = Shop::DB()->query(
                "SELECT *, DATE_FORMAT(dGueltigVon, '%a, %d %b %Y %H:%i:%s UTC') AS dErstellt_RSS
                    FROM tnews
                    WHERE DATE_SUB(now(), INTERVAL " . $alter_tage . " DAY) < dGueltigVon
                        AND nAktiv = 1
                        AND dGueltigVon <= now()
                    ORDER BY dGueltigVon DESC", 2
            );

            if (is_array($oNews_arr) && count($oNews_arr) > 0) {
                foreach ($oNews_arr as $oNews) {
                    $xml .= '
                <item>
                    <title>' . wandelXMLEntitiesUm($oNews->cBetreff) . '</title>
                    <description>' . wandelXMLEntitiesUm($oNews->cVorschauText) . '</description>
                    <link>' . $shopURL . '/' . baueURL($oNews, URLART_NEWS) . '</link>
                    <guid>' . $shopURL . '/' . baueURL($oNews, URLART_NEWS) . '</guid>
                    <pubDate>' . bauerfc2822datum($oNews->dGueltigVon) . '</pubDate>
                </item>';
                }
            }
        }
        // bewertungen beachten?
        if ($Einstellungen['rss']['rss_bewertungen_beachten'] === 'Y') {
            $oBewertung_arr = Shop::DB()->query(
                "SELECT *, dDatum, DATE_FORMAT(dDatum, \"%a, %d %b %y %h:%i:%s +0100\") AS dErstellt_RSS
                    FROM tbewertung
                    WHERE DATE_SUB(now(), INTERVAL " . $alter_tage . " DAY) < dDatum
                        AND nAktiv = 1", 2
            );
            if (is_array($oBewertung_arr) && count($oBewertung_arr) > 0) {
                foreach ($oBewertung_arr as $oBewertung) {
                    $xml .= '
                <item>
                    <title>bewertung ' . wandelXMLEntitiesUm($oBewertung->cTitel) . ' von ' . wandelXMLEntitiesUm($oBewertung->cName) . '</title>
                    <description>' . wandelXMLEntitiesUm($oBewertung->cText) . '</description>
                    <link>' . $shopURL . '/' . baueURL($oBewertung, URLART_ARTIKEL) . '</link>
                    <guid>' . $shopURL . '/' . baueURL($oBewertung, URLART_ARTIKEL) . '</guid>
                    <pubDate>' . bauerfc2822datum($oBewertung->dDatum) . '</pubDate>
                </item>';
                }
            }
        }

        $xml .= '
	</channel>
</rss>
		';

        $file = fopen(PFAD_ROOT . FILE_RSS_FEED, 'w+');
        fputs($file, $xml);
        fclose($file);
    } else {
        Jtllog::writeLog('RSS Verzeichnis nicht beschreibbar!', JTLLOG_LEVEL_ERROR);

        return false;
    }

    return true;
}

/**
 * @param string $dErstellt
 * @return bool|string
 */
function bauerfc2822datum($dErstellt)
{
    if (strlen($dErstellt) > 0) {
        // Datum + Zeit
        if (count(explode(' ', $dErstellt)) > 1) {
            list($dDatum, $dZeit)               = explode(' ', $dErstellt);
            list($dJahr, $dMonat, $dTag)        = explode('-', $dDatum);
            list($dStunde, $dMinute, $dSekunde) = explode(':', $dZeit);

            return date('r', mktime($dStunde, $dMinute, $dSekunde, $dMonat, $dTag, $dJahr));
        } else {
            // Nur Datum
            list($dJahr, $dMonat, $dTag) = explode('-', $dErstellt);

            return date('r', mktime(0, 0, 0, $dMonat, $dTag, $dJahr));
        }
    }
    
    return false;
}

/**
 * @param string $cText
 * @return string
 */
function wandelXMLEntitiesUm($cText)
{
    if (strlen($cText) > 0) {
        return '<![CDATA[ ' . StringHandler::htmlentitydecode($cText) . ' ]]>';
    }

    return '';
}
