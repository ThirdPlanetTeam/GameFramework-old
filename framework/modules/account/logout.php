<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

GFCommonAuth::unregisterToken();

session_destroy();
session_start();

header("Location: .");