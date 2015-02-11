<?php

namespace Masterclass\Model;

use PDO;

/**
*Model for Comments
*/
class Comment
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
	 * retrieve all comments for a given story
	 * @param  int $storyId
	 * @return array
	 */
	public function getCommentsForStory($storyId)
	{
        $comment_sql = 'SELECT * FROM comment WHERE story_id = ?';
        $comment_stmt = $this->db->prepare($comment_sql);
        $comment_stmt->execute(array($storyId));
        $comment_count = $comment_stmt->rowCount();
        $comments = $comment_stmt->fetchAll(PDO::FETCH_ASSOC);

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
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $username,
            $story_id,
            filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        ));
	}
}