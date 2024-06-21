<?php

session_start();
session_destroy();

echo '<script>window.location.replace("../../public/auth/login.php?logout=true") </script>';