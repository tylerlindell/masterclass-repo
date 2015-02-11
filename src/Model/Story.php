<?php

namespace Masterclass\Model;

use PDO;

/**
* Story Model
*/
class Story
{
	/**
	 * @var PDO
	 */
	protected $db;
	
	/**
	 * @param PDO $pdo
	 */
	function __construct(PDO $pdo)
	{
        $this->db = $pdo;
	}

	/**
	 * get list of all stories
	 * @return array
	 */
	public function getStoryList()
	{
		$sql = 'SELECT * FROM story ORDER BY created_on DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $stories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($stories as $key => $story) {
        	$comment_sql = 'SELECT COUNT(*) as `count` FROM comment WHERE story_id = ?';
            $comment_stmt = $this->db->prepare($comment_sql);
            $comment_stmt->execute(array($story['id']));
            $count = $comment_stmt->fetch(PDO::FETCH_ASSOC);
            $stories[$key]['count'] = $count['count'];
        }

        return $stories;
	}

	/**
	 * get a single story
	 * @param  array $storyId
	 * @return array|bool
	 */
	public function getStory($id)
	{
		$story_sql = 'SELECT * FROM story WHERE id = ?';
        $story_stmt = $this->db->prepare($story_sql);
        $story_stmt->execute(array($id));
        
        
        $story = $story_stmt->fetch(PDO::FETCH_ASSOC);

        return $story;
	}

	/**
	 * @return int -the id of the created row
	 */
	public function create($headline, $url, $creator)
	{
		$sql = 'INSERT INTO story (headline, url, created_by, created_on) VALUES (?, ?, ?, NOW())';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
           $headline,
           $url,
           $creator,
        ));
        
        $id = $this->db->lastInsertId();

        return $id;
	}
}