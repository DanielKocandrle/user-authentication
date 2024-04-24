<?php

namespace App\Model;

use Nette;

final class PostFacade
{
    public function __construct(
        private Nette\Database\Explorer $database,
    ) {
    }

    public function findPublishedArticles(int $limit, int $offset): Nette\Database\ResultSet
	{
		return $this->database->query('
			SELECT * FROM posts
			WHERE created_at < ?
			ORDER BY created_at DESC
			LIMIT ?
			OFFSET ?',
			new \DateTime, $limit, $offset,
		);
	}

	/**
	 * Vrací celkový počet publikovaných článků
	 */
	public function getPublishedArticlesCount(): int
	{
		return $this->database->fetchField('SELECT COUNT(*) FROM posts WHERE created_at < ?', new \DateTime);
	}

    public function getPublicArticles()
    {
        return $this->database
            ->table('posts')
            ->where('created_at < ', new \DateTime)
            ->order('created_at DESC');
    }

    public function getPostById(int $postId)
    {
        return $this->database
            ->table('posts')
            ->get($postId);
    }



    public function editPost(int $postId, array $data)
    {
        return $this->database
            ->table('posts')
            ->where('id', $postId)
            ->update($data);
    }

    public function insertPost(array $data)
    {
        $data['created_at'] = new \Datetime();
        $this->database
            ->table('posts')
            ->insert($data);
    }


}
