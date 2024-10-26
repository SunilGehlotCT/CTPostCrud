<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Shamaseen\Repository\Utility\Controller as Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Policies\PostPolicy;
use App\Repositories\PostRepository;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Gate;

/**
 * Class PostController.
 *
 * @property PostRepository $repository
 */
class PostController extends Controller
{

    public string $routeIndex = 'posts.index';

    public string $pageTitle = 'Post';
    public string $createRoute = 'posts.create';

    public string $viewHome = 'home';

    public string $viewIndex = 'posts.index';
    public string $viewCreate = 'posts.create';
    public string $viewEdit = 'posts.edit';
    public string $viewShow = 'posts.show';

	public ?string $resourceClass = PostResource::class;

	public ?string $collectionClass = PostCollection::class;

	public ?string $policyClass = PostPolicy::class;

	public string $requestClass = PostRequest::class;


    /**
     * PostController constructor.
     *
     * @param PostRepository $repository
     */
    public function __construct(PostRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Display a listing of all posts.
     */
    public function home(): View
    {
        $search = $this->request->input('search', '');
        $criteria = [
            'order' => 'created_at',
            'direction' => 'desc',
            'search'    => $search
        ];

        $posts = $this->repository->paginate(12, $criteria);
        return view($this->viewHome, compact('posts'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();

         $criteria = [
             'order' => 'created_at',
             'direction' => 'desc',
             'user_id' => $user->id, // Filtering by user ID
         ];

         $posts = $this->repository->paginate(12, $criteria);

        return view($this->viewIndex, compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view($this->viewCreate);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse|RedirectResponse
    {

        $request    = $this->request;
        $data       = $request->input();

        $user = Auth::user();
        $data['user_id'] = $user->id;

        $post = $this->repository->create($data);
        if ($post) {

            $filename   = $post->id.'-'.uniqid() . '.jpg';

            if ( $request->hasFile('image') ) {

                $manager =  new ImageManager(new Driver());
                $image = $manager->read($request->file('image'));
                $imgData = $image->cover(400, 300)->toJpeg(); // ->save()

                Storage::put('public/posts/' . $filename, $imgData);

                $this->repository->update($post->id, ['image' => $filename]);

            }

            Session::flash('flash_message_success', 'Post created successfully.');
            return redirect()->route($this->routeIndex);
        }

        Session::flash('flash_message_error', 'Oops, something went wrong!');
        return redirect()->route($this->createRoute);

    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {

        $post = $this->repository->findOrFail($id);

        // Gate::authorize('allow', $post);

        $comments = $post->comments()->orderBy('id', 'desc')->paginate(5);

        return view($this->viewShow, compact('post', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $post = $this->repository->findOrFail($id);

        Gate::authorize('allow', $post);

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id): JsonResponse|RedirectResponse
    {

        $request    = $this->request;
        $data       = $request->input();

        $post = $this->repository->findOrFail($id);
        $filename = $post->getRawOriginal('image');

        Gate::authorize('allow', $post);

        if ($post) {

            if ( $request->hasFile('image') ) {

                // Remove the existing image file if it exists
                if ($filename && Storage::exists('public/posts/' . $filename)) {
                    Storage::delete('public/posts/' . $filename);
                }

                $manager =  new ImageManager(new Driver());
                $image = $manager->read($request->file('image'));
                $imgData = $image->cover(400, 300)->toJpeg(); // ->save()

                Storage::put('public/posts/' . $filename, $imgData);

                $data['image'] = $filename;

            }

            $post->update($data);

            Session::flash('flash_message_success', 'Post updated successfully.');

        } else {
            Session::flash('flash_message_error', 'Oops, something went wrong!');
        }

        return redirect()->route($this->routeIndex);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse|RedirectResponse
    {

        $post = $this->repository->findOrFail($id);

        Gate::authorize('allow', $post);

        $post->delete();

        Session::flash('flash_message_success', 'Post deleted successfully.');

        return redirect()->route($this->routeIndex);
    }

}
