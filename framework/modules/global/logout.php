<?php

GFCommonAuth::unregisterUser();

session_destroy();
session_start();

header("Location: .");