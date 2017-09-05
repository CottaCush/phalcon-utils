<?php

namespace PhalconUtils\Model;

use PDOException;
use Phalcon\Di;
use PhalconUtils\Constants\Services;
use PhalconUtils\Exceptions\BatchInsertException;

/**
 * Class Batch
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Model
 */
class Batch
{
    /** @var string */
    public $table = null;

    /** @var array */
    public $rows = [];

    /** @var array */
    public $values = [];

    public function __construct($table = false)
    {
        if ($table) {
            $this->table = (string)$table;
        }

        $di = Di::getDefault();
        $this->db = $di->get('db');

        return $this;
    }

    /**
     * Set the Rows
     *
     * @param array $rows
     *
     * @return object Batch
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
        $this->rowsString = sprintf('`%s`', implode('`,`', $this->rows));

        return $this;
    }

    /**
     * Set the values
     *
     * @param $values array
     * @return object Batch
     * @throws \Exception
     */
    public function setValues($values)
    {
        if (!$this->rows) {
            throw new \Exception('You must setRows() before setValues');
        }
        $this->values = $values;

        $valueCount = count($values);
        $fieldCount = count($this->rows);

        // Build the Placeholder String
        $placeholders = [];
        for ($i = 0; $i < $valueCount; $i++) {
            $placeholders[] = '(' . rtrim(str_repeat('?,', $fieldCount), ',') . ')';
        }
        $this->bindString = implode(',', $placeholders);

        // Build the Flat Value Array
        $valueList = [];
        foreach ($values as $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $valueList[] = $v;
                }
            } else {
                $valueList[] = $values;
            }
        }
        $this->valuesFlattened = $valueList;
        unset($valueList);

        return $this;
    }

    /**
     * Insert into the Database
     *
     * @param boolean $ignore Use an INSERT IGNORE (Default: false)
     * @return bool|int
     * @throws BatchInsertException
     */
    public function insert($ignore = false)
    {
        $this->validate();

        // Optional ignore string
        if ($ignore) {
            $insertString = "INSERT IGNORE INTO `%s` (%s) VALUES %s";
        } else {
            $insertString = "INSERT INTO `%s` (%s) VALUES %s";
        }

        $query = sprintf(
            $insertString,
            $this->table,
            $this->rowsString,
            $this->bindString
        );

        try {
            $this->db->execute($query, $this->valuesFlattened);
            return $this->db->affectedRows() > 0;
        } catch (PDOException $ex) {
            Di::getDefault()->get(Services::LOGGER)->error(
                'Could not perform bulk insert ' . $ex->getMessage() . '    TRACE: ' . $ex->getTraceAsString()
            );
            throw new BatchInsertException($ex->getMessage());
        }
    }

    /**
     * Validates the data before calling SQL
     * @return void
     * @throws \Exception
     */
    private function validate()
    {
        if (!$this->table) {
            throw new BatchInsertException('Batch Table must be defined');
        }

        $requiredCount = count($this->rows);

        if ($requiredCount == 0) {
            throw new BatchInsertException('Batch Rows cannot be empty');
        }

        foreach ($this->values as $value) {
            if (count($value) !== $requiredCount) {
                throw new BatchInsertException('Batch Values must match the same column count of ' . $requiredCount);
            }
        }
    }
}
