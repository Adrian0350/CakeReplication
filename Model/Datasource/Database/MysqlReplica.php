<?php

App::uses('Mysql', 'Model/Datasource/Database');

/**
 * This class serves as modified extension of Cake's Mysql.php Datasource class.
 * The intention of this class is to intervene in any Write-type and Read-type queries
 * and switch between Source and Replica databases, adding load-balancing functionality
 * to the system;
 * All Write queries go to Source database and all Read queries go to
 * a Replica database, which in turn was randomly assigned in database configuration (see database.php).
 */
class MysqlReplica extends Mysql
{
	/**
	 * Datasource description
	 *
	 * @var string
	 */
	public $description = "MySQL DBO Driver compatible with MySQL Replication";

	/**
	 * Override _execute to use source or slave connection.
	 * This method is declared in DboSource class which extends Mysql class.
	 *
	 * @param  string $sql            The SQL Query being executed.
	 * @param  array  $options        Options.
	 * @param  array  $prepareOptions Prepare query options.
	 * @return resource
	 */
	protected function _execute($sql, $options = array(), $prepareOptions = array())
	{
		$write_transactions = [
			'CREATE',
			'DELETE',
			'DROP',
			'INSERT',
			'UPDATE',
			'TRUNCATE',
			'REPLACE',
			'START TRANSACTION',
			'COMMIT',
			'ROLLBACK'
		];

		$trimmed_sql = trim($sql);

		if (preg_match('/^(SET NAMES)/i', $trimmed_sql))
		{
			/*
			 * Not needed to set a connection as 'set names' is invoked in the connection constructor wheather
			 * 'encoding' is specified on the connection beware though: explicitly setting a connection here
			 * results in connection constructor being called again (and again and again…).
			 */
		}
		else
		{
			$datasource = 'default';
			if (preg_match('/^(' . implode('|', $write_transactions) . ')/i', $trimmed_sql))
			{
				$datasource = 'source';
			}

			$this->setConnection($datasource);
		}

		return parent::_execute($sql, $options, $prepareOptions);
	}

	/**
	 * Switch the datasource to 'source' when beginning a transaction.
	 *
	 * @return mixed
	 */
	public function begin()
	{
		$this->setConnection('source');

		return parent::begin();
	}

	/**
	 * Switch the connection based on name.
	 * Accepted names are 'source' and 'default' (a slave)
	 * If in the middle of a transaction the 'source' connection will always be used.
	 *
	 * @param  string $name Datasource name.
	 * @return void
	 */
	protected function setConnection($name = 'default')
	{
		if ($this->_transactionStarted)
		{
			$name = 'source';
		}

		$datasource = ConnectionManager::getDataSource($name);

		if (!$datasource->isConnected())
		{
			$datasource->connect();
		}

		$this->_connection = $datasource->_connection;
	}
}
