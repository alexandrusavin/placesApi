<?php

require "./config.php";

define('CLASS_DIR', 'libs/');

set_include_path(get_include_path() . PATH_SEPARATOR . CLASS_DIR);

spl_autoload_extensions('.class.php');

spl_autoload_register();
