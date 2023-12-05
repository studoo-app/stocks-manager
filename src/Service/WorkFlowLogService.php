<?php

namespace App\Service;

use App\Entity\AuthLog;
use App\Entity\CommandeWorkflowLog;
use Psr\Log\LoggerInterface;

class WorkFlowLogService
{
    public function __construct(
        private readonly string $rootDir,
        private readonly LoggerInterface $workflowCommandeLogger
    )
    {
    }

    public function log(string $id,\DateTimeImmutable $date,string $status): void
    {
        $trace = new CommandeWorkflowLog($id,$date,$status);
        $this->save($trace);
    }

    public function getLogs(): array
    {
        $raw = file_get_contents($this->rootDir."/".$_ENV["AUTH_LOG_FILE_PATH"]);
        $logs = [];
        foreach(explode("\n",$raw) as $line){
            $logEntry = [];
            if(strlen($line)>0){
                if (preg_match('/\[([^]]+)\]/', $line, $dateResult) && !empty($dateResult[1])) {
                    $resultDate = $dateResult[1];
                    $logEntry["date"] = new \DateTimeImmutable($resultDate);
                }

                if (preg_match('/\{([^}]+)\}/', $line, $objectResult) && !empty($objectResult[0])) {
                    $resultObject = $objectResult[0];
                    $logEntry["details"] = AuthLog::buildFromLog(json_decode($resultObject,true));
                }

                $logs[] = $logEntry;
            }
        }

        return $logs;
    }

    private function save(CommandeWorkflowLog $log): void
    {
        $this->workflowCommandeLogger->info(json_encode($log->toArray()));
    }
}