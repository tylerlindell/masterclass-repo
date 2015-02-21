<?php

namespace Masterclass\Model;

use Masterclass\Dbal\AbstractDb;

/**
* Story Model
*/
class Story
{
	/**
	 * @var AbstractDb
	 */
	protected $db;
	
	/**
	 * @param AbstractDb $db
	 */
	function __construct(AbstractDb $db)
	{
        $this->db = $db;
	}

	/**
	 * get list of all stories
	 * @return array
	 */
	public function getStoryList()
	{
		$sql = 'SELECT * FROM story ORDER BY created_on DESC';
        $stories = $this->db->fetchAll($sql, []);

        foreach ($stories as $key => $story) {
        	$comment_sql = 'SELECT COUNT(*) as `count` FROM comment WHERE story_id = ?';
            $count = $this->db->fetchOne($comment_sql, [$story['id']]);
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
        $story = $this->db->fetchOne($story_sql, [$id]);

        return $story;
	}

	/**
	 * @return int -the id of the created row
	 */
	public function create($headline, $url, $creator)
	{
		$sql = 'INSERT INTO story (headline, url, created_by, created_on) VALUES (?, ?, ?, NOW())';
        $this->db->execute($sql, array(
           $headline,
           $url,
           $creator,
        ));
        
        $id = $this->db->lastInsertId();

        return $id;
	}
}