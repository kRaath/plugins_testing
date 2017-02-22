<?php
/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */

/**
 * Class Updater
 */
class Updater
{
    /**
     * @var null|array
     */
    protected static $availableVersions = null;

    /**
     * @var boolean
     */
    protected static $isVerified = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->verify();
    }

    /**
     * Check database integrity
     */
    public function verify()
    {
        if (static::$isVerified !== true) {
            MigrationHelper::verifyIntegrity();
            $dbVersion = $this->getCurrentDatabaseVersion();

            // While updating from 3.xx to 4.xx provide a default admin-template row
            if ($dbVersion < 400) {
                $count = (int) Shop::DB()->query("SELECT * FROM `ttemplate` WHERE `eTyp`='admin'", 3);
                if ($count === 0) {
                    Shop::DB()->query("ALTER TABLE `ttemplate` CHANGE `eTyp` `eTyp` ENUM('standard','mobil','admin') NOT NULL", 3);
                    Shop::DB()->query("INSERT INTO `ttemplate` (`cTemplate`, `eTyp`) VALUES ('bootstrap', 'admin')", 3);
                }
            }

            if ($dbVersion < 404) {
                Shop::DB()->query("ALTER TABLE `tversion` CHANGE `nTyp` `nTyp` INT(4) UNSIGNED NOT NULL", 3);
            }

            static::$isVerified = true;
        }
    }

    /**
     * Has pending updates to execute
     *
     * @return bool
     */
    public function hasPendingUpdates()
    {
        $fileVersion = $this->getCurrentFileVersion();
        $dbVersion   = $this->getCurrentDatabaseVersion();

        if ($fileVersion > $dbVersion || $dbVersion <= 219) {
            return true;
        }

        foreach ($this->getPendingMigrations() as $version => $migrations) {
            if (count($migrations) > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Create a database backup file including structure and data
     *
     * @param string $file
     * @param bool $compress
     */
    public function createSqlDump($file, $compress = true)
    {
        if ($compress) {
            $info = pathinfo($file);
            if ($info['extension'] !== 'gz') {
                $file .= '.gz';
            }
        }

        if (file_exists($file)) {
            @unlink($file);
        }

        $connectionStr = sprintf('mysql:host=%s;dbname=%s', DB_HOST, DB_NAME);
        $sql           = new Ifsnop\Mysqldump\Mysqldump($connectionStr, DB_USER, DB_PASS, [
            'skip-comments'  => true,
            'skip-dump-date' => true,
            'compress'       => $compress === true
                ? Ifsnop\Mysqldump\Mysqldump::GZIP
                : Ifsnop\Mysqldump\Mysqldump::NONE
        ]);

        $sql->start($file);
    }

    /**
     * @param bool $compress
     * @return string
     */
    public function createSqlDumpFile($compress = true)
    {
        $file = PFAD_ROOT . PFAD_EXPORT_BACKUP . date('YmdHis') . '_backup.sql';
        if ($compress) {
            $file .= '.gz';
        }

        return $file;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getVersion()
    {
        $v = Shop::DB()->query("SELECT * FROM tversion", 1);
        if ($v === null) {
            throw new \Exception('Unable to identify application version');
        }

        return $v;
    }

    /**
     * @return int
     */
    public function getCurrentFileVersion()
    {
        return (int) JTL_VERSION;
    }

    /**
     * @return int
     */
    public function getCurrentDatabaseVersion()
    {
        $v = $this->getVersion();

        return (int) $v->nVersion;
    }

    /**
     * @param int $version
     * @return int|mixed
     */
    public function getTargetVersion($version)
    {
        $version = (int) $version;
        $majors  = [219 => 300, 320 => 400];

        if (array_key_exists($version, $majors)) {
            $targetVersion = $majors[$version];
        } else {
            $targetVersion = ++$version;
        }

        return $targetVersion;
    }

    /**
     * getPreviousVersion
     *
     * @param int $version
     * @return int|mixed
     */
    public function getPreviousVersion($version)
    {
        $version = (int) $version;
        $majors  = [300 => 219, 400 => 320];

        if (array_key_exists($version, $majors)) {
            $previousVersion = $majors[$version];
        } else {
            $previousVersion = --$version;
        }

        return $previousVersion;
    }

    /**
     * @return int
     */
    public function getLatestVersion()
    {
        $versions = $this->getAvailableVersions();

        return (int) end($versions);
    }

    /**
     * @return array|null
     */
    public function getAvailableVersions()
    {
        if (static::$availableVersions === null || !is_array(static::$availableVersions)) {
            $content = http_get_contents('http://api.jtl-software.de/shop/versions');
            if ($content !== null && !empty($content)) {
                $versions = json_decode($content);
                if (is_array($versions)) {
                    static::$availableVersions = $versions;
                }
            }
        }

        return static::$availableVersions;
    }

    /**
     * @param int $targetVersion
     * @return string
     */
    protected function getUpdateDir($targetVersion)
    {
        return sprintf('%s%d', PFAD_ROOT . PFAD_UPDATE, (int) $targetVersion);
    }

    /**
     * @param int $targetVersion
     * @return string
     */
    protected function getSqlUpdatePath($targetVersion)
    {
        return sprintf('%s/update1.sql', $this->getUpdateDir($targetVersion));
    }

    /**
     * @param int $targetVersion
     * @return array
     * @throws Exception
     */
    protected function getSqlUpdates($targetVersion)
    {
        $sqlFile = $this->getSqlUpdatePath($targetVersion);

        if (!file_exists($sqlFile)) {
            throw new Exception("Sql file in path '{$sqlFile}' not found");
        }

        $lines = file($sqlFile);
        foreach ($lines as $i => $line) {
            $line = trim($line);
            if (substr($line, 0, 2) === '--' || substr($line, 0, 1) === '#') {
                unset($lines[$i]);
            }
        }

        return $lines;
    }

    /**
     * @return int|null
     * @throws Exception
     */
    public function update()
    {
        if ($this->hasPendingUpdates()) {
            return $this->updateToNextVersion();
        }

        return;
    }

    /**
     * @return int|mixed
     */
    protected function updateToNextVersion()
    {
        $version = $this->getVersion();

        $currentVersion = (int) $version->nVersion;
        $targetVersion  = (int) $this->getTargetVersion($currentVersion);

        if ($targetVersion <= $currentVersion) {
            return $currentVersion;
        }

        return ($targetVersion < 403)  ?
             $this->updateBySqlFile($currentVersion, $targetVersion) :
             $this->updateByMigration($currentVersion, $targetVersion);
    }

    /**
     * @param int $currentVersion
     * @param int $targetVersion
     * @return mixed
     * @throws Exception
     */
    protected function updateBySqlFile($currentVersion, $targetVersion)
    {
        $currentLine = 0;
        $sqls        = $this->getSqlUpdates($currentVersion);

        try {
            Shop::DB()->beginTransaction();

            foreach ($sqls as $i => $sql) {
                $currentLine = $i;
                Shop::DB()->executeQuery($sql, 3);
            }
        } catch (\PDOException $e) {
            $code  = (int) $e->errorInfo[1];
            $error = Shop::DB()->escape($e->errorInfo[2]);

            if (!in_array($code, array(1062, 1060, 1267))) {
                Shop::DB()->rollback();

                $errorCountForLine = 1;
                $version           = $this->getVersion();

                if ((int) $version->nZeileBis === $currentLine) {
                    $errorCountForLine = $version->nFehler + 1;
                }

                Shop::DB()->executeQuery(
                    "UPDATE tversion SET
                     nZeileVon = 1, nZeileBis = {$currentLine}, nFehler = {$errorCountForLine},
                     nTyp = {$code}, cFehlerSQL = '{$error}', dAktualisiert = now()", 3
                );

                throw $e;
            }
        }

        $this->setVersion($targetVersion);

        return $targetVersion;
    }

    /**
     * @param int $currentVersion
     * @param int $targetVersion
     * @return mixed
     * @throws Exception
     */
    protected function updateByMigration($currentVersion, $targetVersion)
    {
        $pendingMigrations = $this->getPendingMigrations();
        $previousVersion   = $this->getPreviousVersion($currentVersion);

        $previousMigrations = isset($pendingMigrations[$previousVersion])
            ? $pendingMigrations[$previousVersion] : [];

        $currentMigrations = isset($pendingMigrations[$currentVersion])
            ? $pendingMigrations[$currentVersion] : [];

        $matchingMigrations = [
            $previousVersion => $previousMigrations,
            $currentVersion  => $currentMigrations,
        ];

        if (count($previousMigrations) === 0 && count($currentMigrations) === 0) {
            $this->setVersion($targetVersion);

            return $targetVersion;
        }

        foreach ($matchingMigrations as $version => $versionedMigrations) {
            $manager = new MigrationManager($version);
            foreach ($versionedMigrations as $migration) {
                $migration = $manager->getMigrationById($migration);
                $manager->executeMigration($migration, IMigration::UP);

                return $migration; // 1 migration per run
            }
        }

        return;
    }

    /**
     * @return array
     */
    public function getPendingMigrations()
    {
        $migrations    = [];
        $migrationDirs = array_filter($this->getUpdateDirs(), function ($v) {
            return (int) $v >= 402;
        });

        foreach ($migrationDirs as $version) {
            $migration = new MigrationManager((int) $version);
            $pending   = $migration->getPendingMigrations();
            if (count($pending) > 0) {
                $migrations[(int) $version] = $pending;
            }
        }

        return $migrations;
    }

    /**
     * @param int $version
     * @throws Exception
     */
    protected function executeMigrations($version)
    {
        $manager    = new MigrationManager($version);
        $migrations = $manager->migrate(null);

        foreach ($migrations as $migration) {
            if ($migration->error !== null) {
                throw new Exception($migration->error);
            }
        }
    }

    /**
     * @param int $targetVersion
     */
    protected function setVersion($targetVersion)
    {
        Shop::DB()->executeQuery(
            "UPDATE tversion SET 
            nVersion = {$targetVersion}, nZeileVon = 1, nZeileBis = 0, 
            nFehler = 0, nTyp = 1, cFehlerSQL = '', dAktualisiert = now()", 3
        );
    }

    /**
     * @return null|object
     * @throws Exception
     */
    public function error()
    {
        $version = $this->getVersion();
        if ((int)$version->nFehler > 0) {
            return (object) [
                'code'  => $version->nTyp,
                'error' => $version->cFehlerSQL,
                'sql'   => $version->nVersion < 402 ?
                    $this->getErrorSqlByFile() : null
            ];
        }

        return;
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function getErrorSqlByFile()
    {
        $version = $this->getVersion();
        $sqls    = $this->getSqlUpdates($version->nVersion);

        if ((int) $version->nFehler > 0) {
            if (array_key_exists($version->nZeileBis, $sqls)) {
                $errorSql = trim($sqls[$version->nZeileBis]);

                return $errorSql;
            }
        }

        return;
    }

    /**
     * @return array
     */
    public function getUpdateDirs()
    {
        $directories = [];
        $dir         = PFAD_ROOT . PFAD_UPDATE;
        foreach (scandir($dir) as $key => $value) {
            if (!in_array($value, array(".", "..")) && is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                if (is_numeric($value) && (int) $value > 300 && (int) $value < 500) {
                    $directories[] = $value;
                }
            }
        }

        return $directories;
    }
}

