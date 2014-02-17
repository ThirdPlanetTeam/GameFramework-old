<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

//include FRAMEWORK_ROOT . '/modules/global/menu.php';

include FRAMEWORK_ROOT . '/view/index.php';

UI::generateAutoMenu();

UI::printFullMenu(UI::$auto_full_menu);