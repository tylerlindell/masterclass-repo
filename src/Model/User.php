<?php

namespace Masterclass\Model;

use Masterclass\Dbal\AbstractDb;

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
	 * @param PDO $pdo
	 */
	function __construct(AbstractDb $db)
	{
        $this->db = $db;
	}

	/**
	 * check db for username already used
	 * @param  string $username
	 * @return bool
	 */
	public function check($username)
	{
		$check_sql = 'SELECT * FROM user WHERE username = ?';
        $this->db->execute($check_sql, [$username]);
        if($this->db->rowCount() > 0)
        	return true;
	}

	/**
	 * @param array $params -contains username, email, and password
	 */
	public function addNewUser($params)
	{
		$sql = 'INSERT INTO user (username, email, password) VALUES (?, ?, ?)';
        $execute = $this->db->execute($sql, [$params]);

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
        $execute = $this->db->execute($sql, array(
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
        $details = $this->db->fetchOne($dsql, [$username]);

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
        $user = $this->db->execute($sql, [$username, $password]);
        if(count($user) > 0) {
           return $this->db->fetchOne($sql, [$username, $password]); 
        }
	}
}