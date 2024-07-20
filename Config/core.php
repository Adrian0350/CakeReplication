<?php

/*
 * Includes Core Datasource Database classes path.
 * This path is needed so as to extend the default MySQL Database class (DboDatasource adapter).
 * This class is needed for MySQLReplica class, said class can switch between Source database
 * and Replica database.
 */
App::build(['Model/Datasource/Database' => App::core('Datasource/Database')]);
