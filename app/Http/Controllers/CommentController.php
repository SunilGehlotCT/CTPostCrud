<?php

namespace App\Http\Controllers;

use Shamaseen\Repository\Utility\Controller as Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Policies\CommentPolicy;
use App\Repositories\CommentRepository;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use Auth;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Gate;

/**
 * Class CommentController.
 *
 * @property CommentRepository $repository
 */
class CommentController extends Controller
{

    public string $routeIndex = 'comments.index';

    public string $pageTitle = 'Comment';
    public string $createRoute = 'comments.create';

    public string $viewIndex = 'comments.index';
    public string $viewCreate = 'comments.create';
    public string $viewEdit = 'comments.edit';
    public string $viewShow = 'comments.show';
    
	public ?string $resourceClass = CommentResource::class;

	public ?string $collectionClass = CommentCollection::class;
 
	public ?string $policyClass = CommentPolicy::class;
 
	public string $requestClass = CommentRequest::class;


    /**
     * CommentController constructor.
     *
     * @param CommentRepository $repository
     */
    public function __construct(CommentRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse|RedirectResponse
    {

        $request    = $this->request;
        $data       = $request->input();

        $postId = $request->route('post');

        $user = Auth::user();
        $data['user_id'] = $user->id;
        $data['post_id'] = $postId;

        $this->repository->create($data);

        Session::flash('flash_message_success', 'Comment added successfully.');

        return back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse|RedirectResponse
    {

        $comment = $this->repository->findOrFail($id);

        Gate::authorize('allow', $comment);

        $comment->delete();

        Session::flash('flash_message_success', 'Comment deleted successfully.');

        return back();
    }

}
