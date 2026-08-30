<?php
require_once __DIR__ . '/_auth.php';
session_destroy();
redirect('/admin/login.php');
