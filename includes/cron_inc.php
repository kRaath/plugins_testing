<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */
defined('JTLCRON') || define('JTLCRON', true);

if (!defined('PFAD_LOGFILES')) {
    require dirname(__FILE__) . '/globalinclude.php';
}

define('JOBQUEUE_LOCKFILE', PFAD_LOGFILES . 'jobqueue.lock');

if (file_exists(JOBQUEUE_LOCKFILE)) {
    if ((time() - filemtime(JOBQUEUE_LOCKFILE)) < 600) {
        exit;
    } else {
        touch(JOBQUEUE_LOCKFILE);
    }
} else {
    touch(JOBQUEUE_LOCKFILE);
}
require_once PFAD_ROOT . PFAD_CLASSES . 'class.JTL-Shop.Cron.php';

$oCron_arr = Shop::DB()->query(
    "SELECT tcron.*
        FROM tcron
        LEFT JOIN tjobqueue ON tjobqueue.kCron = tcron.kCron
        WHERE (tcron.dLetzterStart='0000-00-00 00:00:00' OR (UNIX_TIMESTAMP(now()) > (UNIX_TIMESTAMP(tcron.dLetzterStart) + (3600 * tcron.nAlleXStd))))
            AND tcron.dStart < now()
            AND tjobqueue.kJobQueue IS NULL", 2
);
if (is_array($oCron_arr) && count($oCron_arr) > 0) {
    foreach ($oCron_arr as $oCronTMP) {
        $oCron = new Cron(
            $oCronTMP->kCron,
            $oCronTMP->kKey,
            $oCronTMP->nAlleXStd,
            $oCronTMP->cName,
            $oCronTMP->cJobArt,
            $oCronTMP->cTabelle,
            $oCronTMP->cKey,
            $oCronTMP->dStart,
            $oCronTMP->dStartZeit,
            $oCronTMP->dLetzterStart
        );
        if (Jtllog::doLog(JTLLOG_LEVEL_NOTICE)) {
            Jtllog::writeLog(print_r($oCron, true), JTLLOG_LEVEL_NOTICE, false, 'kCron', $oCronTMP->kCron);
        }
        $nLimitM = 100;
        switch ($oCron->cJobArt) {
            case 'newsletter' :
                if (JOBQUEUE_LIMIT_M_NEWSLETTER > 0) {
                    $nLimitM = JOBQUEUE_LIMIT_M_NEWSLETTER;
                }
                break;

            case 'exportformat' :
                if (JOBQUEUE_LIMIT_M_EXPORTE > 0) {
                    $nLimitM = JOBQUEUE_LIMIT_M_EXPORTE;
                }
                break;

            case 'statusemail' :
                if (JOBQUEUE_LIMIT_M_STATUSEMAIL > 0) {
                    $nLimitM = JOBQUEUE_LIMIT_M_STATUSEMAIL;
                }
                break;

            case 'tskundenbewertung' :
                $nLimitM = 5;
                break;

            case 'clearcache' :
                $nLimitM = 10;
                break;

            default:
                break;

        }
        executeHook(HOOK_CRON_INC_SWITCH, array('nLimitM' => &$nLimitM));

        $oCron->dLetzterStart = date('Y-m-d H:i');
        $oCron->speicherInJobQueue($oCron->cJobArt, $oCron->dStart, $nLimitM);
        $oCron->updateCronDB();
    }
}
// JobQueue include
require_once PFAD_ROOT . PFAD_INCLUDES . 'jobqueue_inc.php';

if (file_exists(JOBQUEUE_LOCKFILE)) {
    unlink(JOBQUEUE_LOCKFILE);
}

if (!isset($bCronManuell) || !$bCronManuell) {
    header('Content-type: image/gif');
    echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
}
