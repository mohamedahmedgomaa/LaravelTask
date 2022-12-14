<?php

namespace App\Repositories;

use App\Models\Post;
use App\Interfaces\PostRepositoryInterface;

class PostRepository implements PostRepositoryInterface
{
    /**
     * @var Post
     */
    protected $post;

    /**
     * PostRepository constructor.
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get all posts.
     *
     * @return Post $post
     */
    public function getAll()
    {
        return $this->post->paginate(25);
    }

    /**
     * Get post by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->post->where('id', $id)->first();
    }

    /**
     * Save Post
     *
     * @param $data
     * @return Post
     */
    public function save($data): Post
    {
        $post = new $this->post;

        $post->user_id = $data['user_id'];
        $post->title = $data['title'];
        $post->description = $data['description'];

        $post->save();

        return $post->fresh();
    }

    /**
     * @param $data
     * @param $id
     * @return mixed
     */
    public function update($data, $id): mixed
    {
        $post = $this->post->find($id);
        $post->user_id = $data['user_id'];
        $post->title = $data['title'];
        $post->description = $data['description'];

        $post->update();

        return $post;
    }

    /**
     * Update Post
     *
     * @param $data
     * @return Post
     */
    public function delete($id)
    {

        $post = $this->post->find($id);
        $post->delete();

        return $post;
    }

}
