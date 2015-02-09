<?php

namespace Masterclass\Controller;

use PDO;
use Masterclass\Model\Comment as CommentModel;

class Comment {

    /**
     * @var CommentModel
     */
    protected $commentModel;
    
    /**
     * @param array $config
     */
    public function __construct($config) {
        $this->commentModel = new CommentModel($config);
    }
    
    /**
     * Create a new Comment
     * @return void
     */
    public function create() {
        if(!isset($_SESSION['AUTHENTICATED'])) {
            header("Location: /");
            exit;
        }

        $storyId = $this->commentModel->postNewComment($_SESSION['username'], $_POST['story_id'], $_POST['comment']);
        header("Location: /story/?id=" . $_POST['story_id']);
    }
    
}
