<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

GFCommonAuth::unregisterUser();

session_destroy();
session_start();

header("Location: .");