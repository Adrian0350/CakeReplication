<?php
/**
 * DATABASE CONFIG.
 */
class DATABASE_CONFIG
{
	/**
	 * CakePHP's Database Config Default Schema.
	 *
	 * @var array
	 */
	protected $base_config = [
			'datasource' => 'Database/Mysql',
			'persistent' => false,
			'host'       => null,
			'login'      => null,
			'password'   => null,
			'port'       => null,
			'database'   => null,
			'encoding'   => null,
			'prefix'     => null,
			'ssl_ca'     => null,
	];

	/**
	 * Default DataSource.
	 *
	 * @var string
	 */
	protected $default_datasource = 'Database/Mysql';

	/**
	 * Replication DataSource.
	 *
	 * @var string
	 */
	protected $replica_datasource = 'Database/MysqlReplica';

	/**
	 * Datasource in use.
	 *
	 * @var string
	 */
	protected $datasource = 'Database/Mysql';

	/**
	 * Use of Replication flag.
	 *
	 * @var string
	 */
	protected $replication = false;

	/**
	 * Source database.
	 *
	 * @var array
	 */
	public $source = null;

	/**
	 * Replica databases.
	 *
	 * @var array
	 */
	public $replicas = null;

	/**
	 * Sets the database configuration for MySQL Replication.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->replication = false;
		$this->datasource  = $this->default_datasource;
		$this->base_config = array_merge($this->base_config, ['datasource' => $this->datasource]);

		/*
		 * Assuming your database configuration is an assoc array
		 * having 'source' as an array and 'replicas' as an array of arrays
		 * with $this->base_config structure.
		 */
		$database = Configure::read('database');

		// Source will Read and Write when not in replication mode.
		$this->setSource($database['source']);
		$this->default = $this->source;

		// If there are replicas we can then enable replication mode.
		if ((array) $database['replicas'])
		{
			$this->replication = true;

			/*
			 * For Source-Replica configuration, set datasource to MysqlReplica
			 * so as to make it possible to switch between Source and Replica servers.
			 */
			$this->datasource = $this->replica_datasource;
			$this->base_config = array_merge($this->base_config, ['datasource' => $this->datasource]);

			$this->setSource($database['source']);
			$this->setReplicas($database['replicas']);

			// Assign a random replica for the Read transactions, overrides previous setting.
			if ($this->replicas)
			{
				$this->default = $this->replicas[rand(0, count($this->replicas) - 1)];
			}
		}
	}

	/**
	 * Set source database configuration.
	 *
	 * @param  array $source Source database configuration.
	 * @return void
	 */
	private function setSource($source)
	{
		// Source can Read and Write.
		$this->source = array_merge($this->base_config, (array) $source);
	}

	/**
	 * Set replicas database configuration.
	 *
	 * @param  array $replicas An array of replica database configurations.
	 * @return void
	 */
	private function setReplicas($replicas)
	{
		// Replicas can only Read.
		$this->replicas = [];
		foreach ((array) $replicas as $replica)
		{
			$this->replicas[] = array_merge($this->base_config, $replica);
		}
	}
}
