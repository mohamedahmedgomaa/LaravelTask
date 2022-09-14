<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * @var postService
     */
    protected $postService;

    /**
     * PostController Constructor
     *
     * @param PostService $postService
     *
     */
    public function __construct(PostService $postService)
    {
        $this->middleware('auth:api');
        $this->postService = $postService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $result = $this->postService->getAll();
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }

        return responseJson($result['status'], $result['message'], $result['data'] ?? null);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'user_id',
            'title',
            'description',
        ]);

        try {
            $result = $this->postService->savePostData($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }

        return responseJson($result['status'], $result['message'], $result['data'] ?? null);
    }

    /**
     * Display the specified resource.
     *
     * @param id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $result = $this->postService->getById($id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
        return responseJson($result['status'], $result['message'], $result['data'] ?? null);
    }

    /**
     * Update post.
     *
     * @param Request $request
     * @param id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $data = $request->only([
            'user_id',
            'title',
            'description'
        ]);

        try {
            $result = $this->postService->updatePost($data, $id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
        return responseJson($result['status'], $result['message'], $result['data'] ?? null);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $result = $this->postService->deleteById($id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
        return responseJson($result['status'], $result['message'], $result['data'] ?? null);
    }
}
