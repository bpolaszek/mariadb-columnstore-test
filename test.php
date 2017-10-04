<?php

use function BenTools\CartesianProduct\cartesian_product;
use BenTools\ETL\Context\ContextElementInterface;
use BenTools\ETL\Extractor\KeyValueExtractor;
use BenTools\ETL\Runner\ETLRunner;
use BenTools\SimpleDBAL\Model\Credentials;
use BenTools\SimpleDBAL\Model\SimpleDBAL;
use MariaDbTest\SimpleDBALLoader;
use Symfony\Component\Yaml\Yaml;
use function MariaDbTest\uniqid;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/SimpleDBALLoader.php';
$dataset = require(__DIR__ . '/dataset.php');

$parameters = Yaml::parse(file_get_contents(__DIR__ . '/parameters.yml'))['parameters'];
$credentials = new Credentials(
    $parameters['database_host'],
    $parameters['database_user'],
    $parameters['database_password'],
    $parameters['database_name'],
    'mysql',
    $parameters['database_port']
);

$db = SimpleDBAL::factory($credentials);

# Create table
$db->execute(<<<SQL
    CREATE TABLE IF NOT EXISTS test_stats (
      key1 VARCHAR(22) NOT NULL,
      key2 VARCHAR(22) NOT NULL,
      key3 VARCHAR(22) NOT NULL,
      key4 VARCHAR(22) NOT NULL,
      key5 VARCHAR(22) NOT NULL,
      key6 VARCHAR(22) NOT NULL,
      key7 VARCHAR(22) NOT NULL,
      key8 VARCHAR(22) NOT NULL,
      key9 VARCHAR(22) NOT NULL,
      key10 VARCHAR(22) NOT NULL
    ) ENGINE=ColumnStore;
SQL
);

# Delete something randomly
$db->execute("DELETE FROM test_stats WHERE key1 = ?", [uniqid()]);

# Load bulk insertions
$run = new ETLRunner();
$extract = new KeyValueExtractor();
$transform = function (ContextElementInterface $element) use ($db) {
    $data = $element->getData();
    $stmt = $db->prepare(sprintf("INSERT INTO test_stats VALUES (%s);", join(',', array_fill(0, count($data), '?'))), array_values($data));
    $element->setData($stmt);
};
$load = new SimpleDBALLoader($db, 100); // COMMIT every 100 INSERTs

$run(
    cartesian_product($dataset),
    $extract,
    $transform,
    $load
);