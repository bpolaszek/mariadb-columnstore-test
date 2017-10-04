<?php

namespace MariaDbTest;

use BenTools\ETL\Context\ContextElementInterface;
use BenTools\ETL\Loader\FlushableLoaderInterface;
use BenTools\SimpleDBAL\Contract\AdapterInterface;

class SimpleDBALLoader implements FlushableLoaderInterface
{
    /**
     * @var AdapterInterface
     */
    private $db;

    /**
     * @var int
     */
    private $flushEvery;

    /**
     * @var int
     */
    private $counter = 0;

    /**
     * SimpleDBALLoader constructor.
     * @param AdapterInterface $db
     * @param int              $flushEvery
     */
    public function __construct(AdapterInterface $db, int $flushEvery = 100)
    {
        $this->db = $db;
        $this->flushEvery = $flushEvery;
    }


    /**
     * @inheritDoc
     */
    public function shouldFlushAfterLoad(): bool
    {
        return $this->counter >= $this->flushEvery;
    }

    /**
     * @inheritDoc
     */
    public function flush(): void
    {
        $this->db->execute("COMMIT;");
        $this->counter = 0;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ContextElementInterface $element): void
    {
        $stmt = $element->getData();
        if (0 === $this->counter) {
            $this->db->execute("START TRANSACTION;");
        }
        $this->db->execute($stmt);
        $this->counter++;
    }
}