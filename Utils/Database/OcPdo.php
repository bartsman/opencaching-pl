<?php

namespace Utils\Database;

use lib\Objects\OcConfig\OcConfig;
use Utils\Email\EmailSender;
use PDO;
use PDOException;
use Utils\Email\OcSpamDomain;

class OcPdo extends PDO
{

    protected $debug; //bool, if set enabled debug messages

    /**
     *
     * @param string $debug
     */
    public function __construct($debug = false)
    {
        if ($debug === true) {
            $this->debug = true;
        }

        $conf = OcConfig::instance();

        $dsnarr = array(
            'host' => $conf->getDbHost(),
            'dbname' => $conf->getDbName(),
            'charset' => 'utf8'
        );

        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_LOCAL_INFILE => true,
            PDO::ATTR_EMULATE_PREPARES => true /* TODO: we should consider disabling the emulation!
            But this means that placeholders can't be reuse in one query (case: multiVariableQuery) */
        );

        /*
         * Older PHP versions do not support the 'charset' DSN option.
         * This should be removed in future
         */
        if ($dsnarr['charset'] and version_compare(PHP_VERSION, '5.3.6', '<')) {
            $options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES ' . $dsnarr['charset'];
        }

        $dsnpairs = array();
        foreach ($dsnarr as $k => $v) {
            if ($v === null) {
                continue;
            }
            $dsnpairs[] = $k . "=" . $v;
        }

        $dsn = 'mysql:' . implode(';', $dsnpairs);
        try {
            parent::__construct(
                $dsn, $conf->getDbUser(), $conf->getDbPass(), $options
            );
        } catch (PDOException $e) {
            $this->error("OcPdo object creation failed!", $e);
        }
    }

    /**
     * Process error/exception occurence around DB operations
     *
     * @param string $message - description of the error
     * @param PDOException|null $e - exception object
     */
    protected function error(/*PHP7:string*/ $message, PDOException $e = null)
    {
        if ($e === null) {
            throw new PDOException($message);
        }
        if ($message != '') {
            throw new PDOException($message . "\n" . $e->getMessage());
        }
        throw $e;
    }

    /**
     * Print debug messages around DB operations
     *
     * @param string $text
     */
    protected function debugOut(/*PHP7:string*/ $text)
    {
        if( ! $this->debug ){
            return;
        }
        d($text);
    }

    /**
     * This the ONLY way on which instance of this class
     * should be accessed
     *
     * Returns instance of itself.
     *
     * @return OcDb object
     */
    protected static function instance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new static(false);
        }
        return $instance;
    }

    /**
     * Turn on debug messages around DB operations
     * @param unknown $debug
     */
    public function setDebug(/*PHP7:bool*/ $debug)
    {
        $this->debug = $debug;
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone() {}

}
