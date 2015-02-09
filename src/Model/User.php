<?php

namespace Masterclass\Model;

use PDO;

/**
* User Model
*/
class User
{
	
	/**
	 * @var PDO
	 */
	protected $db;

	/**
	 * @param array $config
	 */
	function __construct($config)
	{
		$this->config = $config;
        $dbconfig = $config['database'];
        $dsn = 'mysql:host=' . $dbconfig['host'] . ';dbname=' . $dbconfig['name'];
        $this->db = new PDO($dsn, $dbconfig['user'], $dbconfig['pass']);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	/**
	 * check db for username already used
	 * @param  string $username
	 * @return bool
	 */
	public function check($username)
	{
		$check_sql = 'SELECT * FROM user WHERE username = ?';
        $check_stmt = $this->db->prepare($check_sql);
        $check_stmt->execute(array($username));
        if($check_stmt->rowCount() > 0)
        	return true;
	}

	/**
	 * @param array $params -contains username, email, and password
	 */
	public function addNewUser($params)
	{
		$sql = 'INSERT INTO user (username, email, password) VALUES (?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        $execute = $stmt->execute($params);

        if($execute)
	        return true;
	}

	/**
	 * update user account password
	 * @param  string $username
	 * @param  string $password
	 * @return bool
	 */
	public function updatepw($username, $password)
	{
		$sql = 'UPDATE user SET password = ? WHERE username = ?';
        $stmt = $this->db->prepare($sql);
        $execute = $stmt->execute(array(
           md5($username . $password), // THIS IS NOT SECURE. 
           $username,
        ));

        if($execute)
	        return true;
	}

	/**
	 * Get account details for user
	 * @param  string $username
	 * @return array
	 */
	public function getUserDetails($username)
	{
		$dsql = 'SELECT * FROM user WHERE username = ?';
        $stmt = $this->db->prepare($dsql);
        $stmt->execute(array($username));
        $details = $stmt->fetch(PDO::FETCH_ASSOC);

        return $details;
	}

	/**
	 * verify login credentials with db
	 * @param  string $username
	 * @param  string $password
	 * @return array
	 */
	public function login($username, $password)
	{
        $password = md5($username . $password); // THIS IS NOT SECURE. DO NOT USE IN PRODUCTION.
        $sql = 'SELECT * FROM user WHERE username = ? AND password = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($username, $password));
        if($stmt->rowCount() > 0) {
           return $stmt->fetch(PDO::FETCH_ASSOC); 
        }
	}
}