<?php

session_start();
sessiom_unset();
session_destroy();
header("Location: login.html");
exit();
?>