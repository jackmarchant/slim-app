<?php

namespace App\Services;

use Slim\Container;
use Illuminate\Database\Eloquent\Collection;

use App\Model\Post as PostModel;

class Post {

    /**
     * @param Container $c
     */
    public function __construct(Container $c)
    {
        $this->db = $c->get('db');
        $this->logger = $c->get('logger');
        $this->mailer = $c->get('mailer');
    }

    /**
     * Creates a new post
     * 
     * @param array $params
     */
    public function create($params): PostModel
    {
        return PostModel::create($params);
    }

    /**
     * Find all posts
     */
    public function findAll(): Collection
    {
        return PostModel::all()
            ->sortByDesc('created_at')
            ->load('user');
    }
}