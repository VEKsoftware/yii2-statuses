<?php

namespace statuses;

interface StatusesAccessInterface
{
    public function isAllowed($operation, $relation = null, $user = null);
}
