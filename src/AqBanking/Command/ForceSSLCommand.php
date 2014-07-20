<?php

namespace AqBanking\Command;

use AqBanking\Command\ShellCommandExecutor\DefectiveResultException;
use AqBanking\Command\ShellCommandExecutor\ResultAnalyzer;
use AqBanking\PinFile\PinFileInterface as PinFile;
use AqBanking\User;

class ForceSSLCommand extends AbstractCommand
{
    /**
     * @param User $user
     * @throws ShellCommandExecutor\DefectiveResultException
     */
    public function execute(User $user)
    {
        $shellCommand =
            $this->pathToAqHBCIToolBinary
            . ' adduserflags'
            . ' -f forceSsl3'
            . ' --bank=' . escapeshellcmd($user->getBank()->getBankCode()->getString())
            . ' --user=' . escapeshellcmd($user->getUserId())
        ;

        echo $shellCommand . PHP_EOL;

        $result = $this->getShellCommandExecutor()->execute($shellCommand);


        $resultAnalyzer = new ResultAnalyzer();
        if ($resultAnalyzer->isDefectiveResult($result)) {
            throw new DefectiveResultException(
                'Unexpected output on forcing SSL',
                0,
                null,
                $result,
                $shellCommand
            );
        }
    }
}
