<?php

session_start();
session_destroy();

echo '../../public/auth/login.php?logout=true';