<?php
@session_start();
echo json_encode($_SESSION["pqcms"]["panel"]["notifications"],JSON_UNESCAPED_UNICODE);