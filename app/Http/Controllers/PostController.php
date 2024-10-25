<?php

namespace App\Http\Controllers;

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
            $time = time();
            $imgPath    = 'uploads/posts/';
            $thumbPath  = 'uploads/posts/thumb/';

            if ( $request->hasFile('image') ) {

                $path       = public_path($imgPath);
                $thumb      = public_path($thumbPath);

                $image      = $request->file('image');
                $filename   = time(). '.' . $image->getClientOriginalExtension();

                $image->move($path, $filename);

                $pathImg    = $path . $filename;
                $thumbImg   = $thumb . $filename;

                @$this->resize_crop_image(400, 300, $pathImg, $thumbImg);

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

        Gate::authorize('allow', $post);

        if ($post) {
            $time = time();
            $imgPath    = 'uploads/posts/';
            $thumbPath  = 'uploads/posts/thumb/';

            if ( $request->hasFile('image') ) {

                $path       = public_path($imgPath);
                $thumb      = public_path($thumbPath);

                // If the post already has an image, unlink (delete) the old one
                if ($post->image && file_exists(public_path($imgPath . $post->image))) {
                    unlink($path . $post->image); // Unlink original image
                    unlink($thumb . $post->image); // Unlink thumbnail image
                }

                $image      = $request->file('image');
                $filename   = time(). '.' . $image->getClientOriginalExtension();

                $image->move($path, $filename);

                $pathImg    = $path . $filename;
                $thumbImg   = $thumb . $filename;

                @$this->resize_crop_image(400, 300, $pathImg, $thumbImg);

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

    private function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80): bool
    {

        try {

            $imgsize = getimagesize($source_file);
            $width  = $imgsize[0];
            $height = $imgsize[1];
            $mime   = $imgsize['mime'];

            switch($mime){
                case 'image/gif':
                    $image_create = "imagecreatefromgif";
                    $image = "imagegif";
                    break;

                case 'image/png':
                    $image_create = "imagecreatefrompng";
                    $image = "imagepng";
                    $quality = 7;
                    break;

                case 'image/jpeg':
                    $image_create = "imagecreatefromjpeg";
                    $image = "imagejpeg";
                    $quality = 80;
                    break;

                default:
                    return false;
                    break;
            }

            $dst_img = imagecreatetruecolor($max_width, $max_height);
            $src_img = $image_create($source_file);

            $width_new = $height * $max_width / $max_height;
            $height_new = $width * $max_height / $max_width;
            // if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
            if($width_new > $width){
                // cut point by height
                $h_point = (($height - $height_new) / 2);
                // copy image
                imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
            } else {
                // cut point by width
                $w_point = (($width - $width_new) / 2);
                imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
            }

            $image($dst_img, $dst_dir, $quality);

            if($dst_img)imagedestroy($dst_img);
            if($src_img)imagedestroy($src_img);

            return true;

        } catch (\Exception $e) {
            // dd($e);
        }

        return false;

    }

}
