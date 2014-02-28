<?php

/*
 * Copyright (C) 2013 Michael Hegenbarth (carschrotter) <mnh@mn-hegenbarth.de>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301  USA
 */

namespace mnhcc\ml\classes {

	/**
	 * Description of Database
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package MinimalusLayoutilus
	 * @copyright (c) 2013, Michael Hegenbarth
	 */
	class Database extends MNHcC {

		protected static $dbConf = array('default' => array('db' => 'test',
				'db_host' => 'localhost',
				'db_user' => 'root',
				'db_pw' => ''));
		protected $active = false;

		/**
		 *
		 * @var \PDO 
		 */
		protected $dbHandle = null;

		/**
		 *
		 * @var int 
		 */
		protected $lastInsertId = false;

		/**
		 *
		 * @var int 
		 */
		protected $rowCount = false;

		/**
		 *
		 * @var int 
		 */
		protected $queryCounter = 0;

		/**
		 *
		 * @var \mnhcc\ml\classes\Database
		 */
		protected static $instances = array();

		/**
		 * @param string $active = "default"
		 * @return Database
		 * @throws Exception
		 */
		public static function get($active = 'default') {
			if (!isset(self::$dbConf[$active]))
				throw new Exception('Unexisting db-config ' . $active);

			if (!isset(self::$instances[$active]))
				self::$instances[$active] = new Database($active);

			return self::$instances[$active];
		}

		public static function setConnectionScheme($db, $db_host, $db_user, $db_pw) {
			self::$dbConf[$db] = array('db' => $db,
				'db_host' => $db_host,
				'db_user' => $db_user,
				'db_pw' => $db_pw);
		}

		private function __clone() {
			
		}

		protected function __construct($active) {
			if (!isset(self::$dbConf[$active]))
				throw new \PDOException('No supported connection scheme');

			$dbConf = self::$dbConf[$active];

			try {
				//Connect
				$db = new \PDO('mysql:host=' . $dbConf['db_host'] . ';dbname=' . $dbConf['db'], $dbConf['db_user'], $dbConf['db_pw']);
				//error behaviour
				$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				$db->query('set character set utf8');
				$db->query('set names utf8');

				$this->dbHandle = $db;
				$this->active = $active; //mark as active
			} catch (\PDOException $ex) {
				throw new \PDOException('"Connection Exception: " ' . $ex->getMessage());
			}
		}

		public function disconnect() {
			$this->dbHandle = null;
			unset(self::$instances[$this->active]);
		}

		public function getHandle() {
			return $this->dbHandle;
		}

		public function lastInsertId() {
			return $this->lastInsertId;
		}

		public function rowCount() {
			return $this->rowCount;
		}

		protected function _query($qry, array $params, $type) {
			if (in_array($type, array("insert", "select", "update", "delete")) === false)
				throw new Exception("Unsupported Query Type");

			$this->lastInsertId = false;
			$this->rowCount = false;

			$stmnt = $this->dbHandle->prepare($qry);

			try {
				$success = (count($params) !== 0) ? $stmnt->execute($params) : $stmnt->execute();
				$this->queryCounter++;

				if (!$success)
					return false;

				if ($type === "insert")
					$this->lastInsertId = $this->dbHandle->lastInsertId();
				$this->rowCount = $stmnt->rowCount();

				return ($type === "select") ? $stmnt : true;
			} catch (\PDOException $ex) {
				throw new \PDOException("PDO-Exception: " . $ex->getMessage());
			}
		}

		protected function getQueryType($qry) {
			list($type, ) = explode(" ", strtolower($qry), 2);
			return $type;
		}

		public function delete($qry, array $params = array()) {
			if (($type = $this->getQueryType($qry)) !== "delete")
				throw new Exception("Incorrect Delete Query");

			return $this->_query($qry, $params, $type);
		}

		public function update($qry, array $params = array()) {
			if (($type = $this->getQueryType($qry)) !== "update")
				throw new Exception("Incorrect Update Query");
			return $this->_query($qry, $params, $type);
		}

		/**
		 * 
		 * @param type $qry
		 * @param array $params
		 * @return type
		 * @throws Exception
		 */
		public function insert($qry, array $params = array()) {
			if (($type = $this->getQueryType($qry)) !== "insert")
				throw new Exception("Incorrect Insert Query");
			return $this->_query($qry, $params, $type);
		}

		/**
		 * 
		 * @param type $qry
		 * @param array $params
		 * @param type $class
		 * @return \Record|boolean
		 * @throws Exception
		 */
		public function select($qry, array $params = array(), $class = 'Record') {
			if (($type = $this->getQueryType($qry)) !== "select")
				throw new Exception("Incorrect Select Query");
			if (($stmnt = $this->_query($qry, $params, $type))) {
				$class = __NAMESPACE__ . '\\' . $class;
				return new $class($stmnt->fetchAll(\PDO::FETCH_ASSOC));
			} else {
				return false;
			}
		}

		public function selectSingle($qry, array $params = array(), $field = false) {
			if (($type = $this->getQueryType($qry)) !== "select")
				throw new Exception("Incorrect Select Query");

			if (($stmnt = $this->_query($qry, $params, $type))) {
				$res = $stmnt->fetch(PDO::FETCH_ASSOC);
				return ($field === false) ? $res : $res[$field];
			} else {
				return false;
			}
		}

		public function query($qry) {
			$this->lastInsertId = false;
			$this->rowCount = false;
			$this->rowCount = $this->dbHandle->exec($qry);
			$this->queryCounter++;
		}

		public function getQueryCounter() {
			return $this->queryCounter;
		}

		public function quote($str) {
			return $this->dbHandle->quote($str);
		}

	}

}