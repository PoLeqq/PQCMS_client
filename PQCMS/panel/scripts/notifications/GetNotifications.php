<?php
@session_start();
if(empty($_SESSION["pqcms"]["panel"]["notifications"]))
    die(json_encode([]));
echo json_encode($_SESSION["pqcms"]["panel"]["notifications"],JSON_UNESCAPED_UNICODE);