<?php
namespace App\Repositories;

use Shamaseen\Repository\Utility\AbstractRepository as AbstractRepository;
use App\Models\Post;

/**
 * Class PostRepository.
 *
 * @extends AbstractRepository<Post>
 */
class PostRepository extends AbstractRepository
{
    public array $with = ['comments'];

    /**
     * @return string
     */
    public function getModelClass(): string
    {
        return Post::class;
    }

    /**
     * Get all posts, paginated.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
//    public function allPosts()
//    {
//        return $this->model->orderBy('id', 'desc')->paginate(12);
//    }

    /**
     * Get posts by a specific user, paginated.
     *
     * @param int $user_id
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
//    public function ownPosts(int $user_id)
//    {
//        return $this->model->where('user_id', $user_id)->orderBy('id', 'desc')->paginate(12);
//    }

}
