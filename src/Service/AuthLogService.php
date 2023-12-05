<?php

namespace App\Service;

use App\Entity\AuthLog;
use Psr\Log\LoggerInterface;

class AuthLogService
{
    public function __construct(
        private readonly string $rootDir,
        private readonly LoggerInterface $authenticationLogger
    )
    {
    }

    public function logSuccess(string $identifier,string $clientIp): void
    {
        $trace = new AuthLog($identifier,AuthLog::SUCCESS,"Authenticated successfully",$clientIp);
        $this->save($trace);
    }

    public function logFailure(string $identifier, string $message,string $clientIp): void
    {
        $trace = new AuthLog($identifier,AuthLog::FAIL,$message,$clientIp);
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

    private function save(AuthLog $log): void
    {
        $this->authenticationLogger->info(json_encode($log->toArray()));
    }
}