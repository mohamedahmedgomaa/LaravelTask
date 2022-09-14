<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\PostRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class PostService
{
    /**
     * @var $postRepository
     */
    protected $postRepository;

    /**
     * PostService constructor.
     *
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Delete post by id.
     *
     * @param $id
     * @return array|String
     */
    public function deleteById($id)
    {
        try {
            $post = $this->postRepository->delete($id);

        } catch (Exception $e) {
            return [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
        return [
            'status' => 200,
            'message' => 'success delete',
            'data' => $post,
        ];
    }

    /**
     * Get all post.
     *
     * @return array
     */
    public function getAll()
    {
        return [
            'status' => 200,
            'message' => 'success',
            'data' => $this->postRepository->getAll(),
        ];
    }

    /**
     * Get post by id.
     *
     * @param $id
     * @return array
     */
    public function getById($id)
    {
        return [
            'status' => 200,
            'message' => 'success',
            'data' => $this->postRepository->getById($id),
        ];
    }

    /**
     * Update post data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return Post|array
     */
    public function updatePost($data, $id)
    {
        $validator = Validator::make($data, [
            'user_id' => 'bail|exists:users,id',
            'title' => 'bail|min:2',
            'description' => 'bail|max:255'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => $validator->errors(),
            ];
        }

        try {
            $post = $this->postRepository->update($data, $id);

        } catch (Exception $e) {
            return [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }

        return [
            'status' => 200,
            'message' => 'success',
            'data' => $post,
        ];

    }

    /**
     * Validate post data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return array|String
     */
    public function savePostData($data)
    {
        $validator = Validator::make($data, [
            'user_id' => 'required|exists:users,id',
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => $validator->errors(),
            ];
        }

        $result = $this->postRepository->save($data);

        return [
            'status' => 200,
            'message' => 'success',
            'data' => $result,
        ];
    }

}
