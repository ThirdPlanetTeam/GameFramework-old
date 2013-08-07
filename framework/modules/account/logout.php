<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

GFCommonAuth::unregisterToken();

session_destroy();
session_start();

header("Location: .");