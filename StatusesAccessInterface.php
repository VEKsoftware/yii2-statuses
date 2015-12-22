<?php
namespace statuses;

interface StatusesAccessInterface
{
    public function isAllowed($operation, $relation = NULL, $user = NULL);
}
