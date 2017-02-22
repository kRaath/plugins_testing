<?php

define('JTLSEARCH_URL_EXPORTFILE_DIR', Shop::getURL() . '/' . PFAD_EXPORT);
define('JTLSEARCH_PFAD_EXPORTFILE_DIR', PFAD_ROOT . PFAD_EXPORT);

define('JTLSEARCH_URL_EXPORTFILE_ZIP', JTLSEARCH_URL_EXPORTFILE_DIR . 'jtlsearch.zip');
define('JTLSEARCH_PFAD_EXPORTFILE_ZIP', JTLSEARCH_PFAD_EXPORTFILE_DIR . 'jtlsearch.zip');

define('JTLSEARCH_URL_DELTA_EXPORTFILE_ZIP', JTLSEARCH_URL_EXPORTFILE_DIR . 'delta_jtlsearch' . time() . '.zip');
define('JTLSEARCH_PFAD_DELTA_EXPORTFILE_ZIP', JTLSEARCH_PFAD_EXPORTFILE_DIR . 'delta_jtlsearch' . time() . '.zip');

// Max Anzahl an Datens�tzen pro Datei
define('JTLSEARCH_FILE_LIMIT', 5000);
define('JTLSEARCH_FILE_NAME', 'export_');
define('JTLSEARCH_FILE_NAME_SUFFIX', '.jtl');

define('JTLSEARCH_PRODUCT_EXCLUDE_ATTR', 'nosearch');
