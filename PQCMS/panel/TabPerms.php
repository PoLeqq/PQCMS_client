<?php

abstract class TabPerms
{
    private array $perms;

    public function __construct(?array $perms)
    {
        if(is_null($perms))
            $perms = [];
        $this->perms = $perms;
    }

    public function getPerms(): array
    {
        return $this->perms;
    }
}