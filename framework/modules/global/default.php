<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

//include FRAMEWORK_ROOT . '/modules/global/menu.php';

include FRAMEWORK_ROOT . '/view/index.php';

UI::generateAutoMenu();

UI::printFullMenu(UI::$auto_full_menu);