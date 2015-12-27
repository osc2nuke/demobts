<?php
    require('includes/application_top.php');

    tep_db_query("TRUNCATE TABLE " . TABLE_SESSIONS . "");
    tep_db_query("TRUNCATE TABLE " . TABLE_BTS_CSS_SELECTORS . "");
    tep_db_query("TRUNCATE TABLE " . TABLE_HEADERS . "");
    tep_db_query("TRUNCATE TABLE " . TABLE_HEADERS_DESCRIPTION . "");
    tep_db_query("TRUNCATE TABLE " . TABLE_TEMPLATES_TO_CUSTOMERS . "");

  require(DIR_WS_INCLUDES . 'application_bottom.php');

?>