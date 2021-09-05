<?php declare(strict_types=1);

class ApplicationAdministrationReport_crud extends Base_abstract_crud
{
    protected static string $table   = 'report';
    protected static string $tableId = 'crudId';

    /**
     * @var int|null
     * @database type int;11
     * @database isPrimary
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT
     */
    protected ?int $crudId = null;
    /**
     * @var int
     * @database type int;11
     * @database isIndex
     */
    protected int $crudUserId = 0;
    /**
     * @var string
     * @database isIndex
     * @database type varchar;250
     */
    protected string $crudModul = '';

    /**
     * @var string
     * @database type text
     */
    protected string $crudModulId = '';

    /**
     * @var string
     * @database type text
     */
    protected string $crudContent = '';

    /**
     * @var ?integer
     * @database type int;11
     * @database isNull
     */
    protected ?int $crudType = null;

    /**
     * @var string
     * @database type text
     */
    protected string $crudStatus = '';

    /**
     * @var string
     * @database type text
     */
    protected string $crudReply = '';

    /**
     * @return int
     */
    public function getCrudId(): ?int
    {
        return $this->crudId;
    }

    /**
     * @param int|null $crudId
     */
    public function setCrudId(?int $crudId): void
    {
        $this->crudId = $crudId;
    }

    /**
     * @return int
     */
    public function getCrudUserId(): int
    {
        return $this->crudUserId;
    }

    /**
     * @param int $crudUserId
     */
    public function setCrudUserId(int $crudUserId): void
    {
        $this->crudUserId = $crudUserId;
    }

    /**
     * @return string
     */
    public function getCrudModul(): string
    {
        return $this->crudModul;
    }

    /**
     * @param string $crudModul
     */
    public function setCrudModul(string $crudModul): void
    {
        $this->crudModul = $crudModul;
    }

    /**
     * @return string
     */
    public function getCrudModulId(): string
    {
        return $this->crudModulId;
    }

    /**
     * @param string $crudModulId
     */
    public function setCrudModulId(string $crudModulId): void
    {
        $this->crudModulId = $crudModulId;
    }

    /**
     * @return string
     */
    public function getCrudStatus(): string
    {
        return $this->crudStatus;
    }

    /**
     * @param string $crudStatus
     */
    public function setCrudStatus(string $crudStatus): void
    {
        $this->crudStatus = $crudStatus;
    }

    /**
     * @return string
     */
    public function getCrudReply(): string
    {
        return $this->crudReply;
    }

    /**
     * @param string $crudReply
     */
    public function setCrudReply(string $crudReply): void
    {
        $this->crudReply = $crudReply;
    }

    /**
     * @return string
     */
    public function getCrudContent(): string
    {
        return $this->crudContent;
    }

    /**
     * @param string $crudContent
     */
    public function setCrudContent(string $crudContent): void
    {
        $this->crudContent = $crudContent;
    }

    /**
     * @return int|null
     */
    public function getCrudType(): ?int
    {
        return $this->crudType;
    }

    /**
     * @param int|null $crudType
     */
    public function setCrudType(?int $crudType): void
    {
        $this->crudType = $crudType;
    }

    protected function modifyFindQuery(ContainerFactoryDatabaseQuery $query): ContainerFactoryDatabaseQuery
    {
        $query->join('report_type',
                     ['crudAbbreviation','crudContent'],
                     'report_type.crudId = ' . self::$table . '.crudType');

        return $query;
    }

}
