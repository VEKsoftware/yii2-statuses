<?php

namespace statuses;

interface StatusesAccessInterface
{
    /**
     * Реализуя данный метод, необходимо в вашем приложении определить правила доступа для операций
     * модуля Statuses, их список вы можете получить в переменной $allScenarios класса \statuses\Statuses
     *
     * @param string $operation Операция, к которой необходимо проверить доступ.
     * @return bool
     */
    public function isAllowed($operation);
}
