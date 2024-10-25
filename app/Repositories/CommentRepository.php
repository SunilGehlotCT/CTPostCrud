<?php
namespace App\Repositories;

use Shamaseen\Repository\Utility\AbstractRepository as AbstractRepository;
use App\Models\Comment;

/**
 * Class CommentRepository.
 *
 * @extends AbstractRepository<Comment>
 */
class CommentRepository extends AbstractRepository
{
    public array $with = [];

    /**
     * @return string
     */
    public function getModelClass(): string
    {
        return Comment::class;
    }
}
