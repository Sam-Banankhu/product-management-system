<?php

require_once 'session.php';
session_destroy();
header('Location: admin_login.php');
exit();
