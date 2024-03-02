<?php

require_once(dirname(__DIR__)."/TabPerms.php");

class UserPerms extends TabPerms
{
    public function __construct($username)
    {
        parent::__construct([
            "pqcms.hr.user.edit.nickname.$username",
            "pqcms.hr.user.edit.email.$username"
        ]);
    }
}