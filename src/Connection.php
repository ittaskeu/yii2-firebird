<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace edgardmessias\db\firebird;

/**
 *
 * @author Edgard Lorraine Messias <edgardmessias@gmail.com>
 * @since 2.0
 */
class Connection extends \yii\db\Connection
{

    /**
     * @inheritdoc
     */
    public $schemaMap = [
        'firebird' => 'edgardmessias\db\firebird\Schema', // Firebird
    ];

    /**
     * @inheritdoc
     */
    public $pdoClass = 'edgardmessias\db\firebird\PdoAdapter';

    /**
     * @inheritdoc
     */
    public $commandClass = 'edgardmessias\db\firebird\Command';
    /**
     * @var Transaction the currently active transaction
     */
    private $_transaction;

    /**
     * Returns the currently active transaction.
     * @return Transaction the currently active transaction. Null if no active transaction.
     */
    public function getTransaction()
    {
        return $this->_transaction && $this->_transaction->getIsActive() ? $this->_transaction : null;
    }

    /**
     * Starts a transaction.
     * @param string|null $isolationLevel The isolation level to use for this transaction.
     * See [[Transaction::begin()]] for details.
     * @return Transaction the transaction initiated
     */
    public function beginTransaction($isolationLevel = null)
    {
        $this->open();

        if (($transaction = $this->getTransaction()) === null) {
            $transaction = $this->_transaction = new Transaction(['db' => $this]);
        }
        $transaction->begin($isolationLevel);

        return $transaction;
    }

    public function close()
    {
        if ($this->pdo !== null) {
            $this->_transaction = null;
        }
        parent::close();
    }
}
