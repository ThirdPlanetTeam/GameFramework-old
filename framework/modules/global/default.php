<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

//include FRAMEWORK_ROOT . '/modules/global/menu.php';

include FRAMEWORK_ROOT . '/view/index.php';

Widgets::generateAutoMenu();

Widgets::printFullMenu(Widgets::$auto_full_menu);