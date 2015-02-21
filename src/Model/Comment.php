<?php

namespace Masterclass\Model;

use Masterclass\Dbal\AbstractDb;

/**
*Model for Comments
*/
class Comment
{
	/**
	 * @var Abstractdb
	 */
	protected $db;
	
	/**
	 * @param Abstractdb $db
	 */
	function __construct(Abstractdb $db)
	{ 
        $this->db = $db;
	}

	/**
	 * retrieve all comments for a given story
	 * @param  int $storyId
	 * @return array
	 */
	public function getCommentsForStory($storyId)
	{
        $comment_sql = 'SELECT * FROM comment WHERE story_id = ?';
        $comments = $this->db->fetchAll($comment_sql, [$storyId]);

        return $comments;
	}

	/**
	 * add comment to db
	 * @param  string $username
	 * @param  int $story_id
	 * @param  string $comment
	 * @return void
	 */
	public function postNewComment($username, $story_id, $comment)
	{
		$sql = 'INSERT INTO comment (created_by, created_on, story_id, comment) VALUES (?, NOW(), ?, ?)';
        $this->db->execute($sql, array(
            $username,
            $story_id,
            filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        ));
	}
}