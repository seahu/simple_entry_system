<?php
namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;


class PostPresenter extends BasePresenter
{
    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function renderShow($postId)
    {
        //$this->template->post = $this->database->table('posts')->get($postId);
        $post = $this->database->table('posts')->get($postId);
        if (!$post) {
        		$this->error('Post not found');
        }
        $this->template->post = $post;
    }
}