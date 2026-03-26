<?php
declare(strict_types=1);

final class Config
{
    private string $dbDriver;
    private string $dbHost;
    private string $dbName;
    private string $dbCharset;
    private string $dbUser;
    private string $dbPass;

    public function __construct(
        string $dbDriver,
        string $dbHost,
        string $dbName,
        string $dbCharset,
        string $dbUser,
        string $dbPass
    ) {
        $this->dbDriver = $dbDriver;
        $this->dbHost = $dbHost;
        $this->dbName = $dbName;
        $this->dbCharset = $dbCharset;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
    }

    public function getDbDriver(): string
    {
        return $this->dbDriver;
    }

    public function getDbHost(): string
    {
        return $this->dbHost;
    }

    public function getDbName(): string
    {
        return $this->dbName;
    }

    public function getDbCharset(): string
    {
        return $this->dbCharset;
    }

    public function getDbUser(): string
    {
        return $this->dbUser;
    }

    public function getDbPass(): string
    {
        return $this->dbPass;
    }
}
