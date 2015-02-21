<?php

namespace Masterclass\Controller;

use Aura\Web\Request;
use Aura\Web\Response;
use Masterclass\Model\Comment as CommentModel;

class Comment {

    /**
     * @var CommentModel
     */
    protected $commentModel;
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Response
     */
    protected $response;
    
    /**
     * @param array $config
     */
    public function __construct(
        CommentModel $comment,
        Request $request,
        Response $response
    ) {
        $this->commentModel = $comment;
        $this->request = $request;
        $this->response = $response;
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

        $username = $_SESSION['username'];
        $storyId = $this->request->post->get('story_id');
        $comment  = $this->request->post->get('comment');

        $this->commentModel->postNewComment($username, $storyId, $comment);
        $this->response->redirect->to('/story?id=' . (int)$storyId);
        return $this->response;
    }
    
}
