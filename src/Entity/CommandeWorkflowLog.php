<?php

namespace App\Entity;

class CommandeWorkflowLog
{

    private ?int $id;

    private ?string $date;
    private ?string $status = null;


    /**
     * @param int|null $id
     * @param string|null $date
     * @param string|null $status
     */
    public function __construct(?int $id, ?string $date, ?string $status)
    {
        $this->id = $id;
        $this->date = $date;
        $this->status = $status;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public static function buildFromLog(array $array){
        return new self($array["id"],$array["date"],$array["status"]);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}