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
        $trace = new CommandeWorkflowLog($id,$date->format('d/m/y H:i:s'),$status);
        $this->save($trace);
    }

    public function getLogs(string $id): array
    {
        $raw = file_get_contents($this->rootDir."/".$_ENV["CMD_WKF_LOG_FILE_PATH"]);
        $logs = [];
        foreach(explode("\n",$raw) as $line){
            if(strlen($line)>0){
                if (preg_match('/\{([^}]+)\}/', $line, $objectResult) && !empty($objectResult[0])) {
                    $resultObject = $objectResult[0];
                    $logs[] = CommandeWorkflowLog::buildFromLog(json_decode($resultObject,true));
                }
            }
        }

        return array_filter($logs,function(CommandeWorkflowLog $item) use ($id){
            return $item->getId() === intval($id);
        });
    }

    private function save(CommandeWorkflowLog $log): void
    {
        $this->workflowCommandeLogger->info(json_encode($log->toArray()));
    }
}