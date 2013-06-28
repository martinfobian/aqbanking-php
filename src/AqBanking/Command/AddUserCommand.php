<?php

namespace AqBanking\Command;

use AqBanking\Command\AddUserCommand\UserAlreadyExistsException;
use AqBanking\User;

class AddUserCommand extends AbstractCommand
{
    const RETURN_VAR_USER_ALREADY_EXISTS = 3;

    /**
     * @param User $user
     * @throws AddUserCommand\UserAlreadyExistsException
     */
    public function execute(User $user)
    {
        $shellCommand =
            $this->pathToAqHBCIToolBinary
            . ' adduser'
            . ' --username="' . escapeshellcmd($user->getUserName()) . '"'
            . ' --bank=' . escapeshellcmd($user->getBank()->getBankCode()->getString())
            . ' --user=' . escapeshellcmd($user->getUserId())
            . ' --tokentype=pintan'
            . ' --server=' . escapeshellcmd($user->getBank()->getHbciUrl());

        $result = $this->getShellCommandExecutor()->execute($shellCommand);

        if (self::RETURN_VAR_USER_ALREADY_EXISTS === $result->getReturnVar()) {
            throw new UserAlreadyExistsException(implode(PHP_EOL, $result->getErrors()));
        }
    }
}