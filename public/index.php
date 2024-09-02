<?php

require_once '../config/config.php';
require_once '../utils/loadpage.php';
require_once '../classes/Database.php';
use classes\Database;
include_once '../includes/header.php';
include_once '../includes/navbar.php';

$connection = new Database();
$connection=$connection->connectToDatabase();

loadPage();

include_once '../includes/footer.php';






