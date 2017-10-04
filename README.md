Requirements
------------

This test is intended to run on a single cluster MariaDb Columnstore.
PHP 7.1 with PDO is required.

Installation
------------
```bash
wget -O mariadb-test.zip https://github.com/bpolaszek/mariadb-columnstore-test/archive/master.zip && unzip mariadb-test.zip && cd mariadb-columnstore-test-master
```

Instructions
------------

Run:
```bash
php composer.phar install
```

:point_right: When asked, give the installer your database credentials.


Then run:
```bash
php test.php &
php test.php &
```

Running 2 instances of `test.php` simultaneously will execute the following:

* `CREATE TABLE IF NOT EXISTS test_stats` 
* `DELETE` a random key, which may, or may not exist
* `INSERT` hundreds of random rows with `START TRANSACTION` and `COMMIT` each 100 rows.

Expectations
------------

The 2nd `test.php` process should fail with one of the following messages:

> SQLSTATE[HY000]: General error: 1815 Internal error: IDB-2009: Unable to perform the delete operation because DMLProc with PID 2922 is currently holding the table lock for session 185. 

> SQLSTATE[HY000]: General error: 1815 Internal error: CAL0001: Insert Failed:  IDB-2009: Unable to perform the insert operation because DMLProc with PID 2922 is currently holding the table lock for session 228.