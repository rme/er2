<?php
/**
 * class.er2.php
 *  
 */

  class er2Class extends PMPlugin {
    function __construct() {
      set_include_path(
        PATH_PLUGINS . 'er2' . PATH_SEPARATOR .
        get_include_path()
      );
    }

    function setup()
    {
    }

    function getFieldsForPageSetup()
    {
    }

    function updateFieldsForPageSetup()
    {
    }

  }
?>