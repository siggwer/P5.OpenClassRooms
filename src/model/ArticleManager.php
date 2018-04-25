<?php 
namespace blog\src\model;
use blog\src\model\Manager;


class ArticleManager extends Manager{

	public function getArticles()
	{
		$db = $this->dbConnect();
		$req = $db->query('SELECT id, title, content,content_right,image, DATE_FORMAT(created_at, \'%d/%m/%Y à %Hh%imin%ss\') AS created_at_fr FROM posts ORDER BY created_at #DESC LIMIT 0, 5');
		$res = $req->fetchAll(\PDO::FETCH_ASSOC);
		foreach ($res as $key =>$element) {
			$element['image'] = urldecode($element['image']);
			$res[$key] = $element;
		}
		return $res;
	}

	public function getArticle($articleId)
	{
		$db = $this->dbConnect();
		$req = $db->prepare('SELECT id, title, content,content_right,image, created_at FROM posts WHERE id = ?');
		$req->execute(array($articleId));
		$post = $req->fetch();
		$post['image'] = urldecode($post['image']);
		return $post;
	}
	public function addArticle(ArticleHydrate $article)
	{
		$db = $this->dbConnect();
		$req = $db->prepare('INSERT INTO posts (title, image, content, content_right) VALUES (:articleTitle, :articleImageUrl, :articleContent, :articleContentRight)');
		$req->bindValue(':articleTitle',$article->getTitle(),\PDO::PARAM_STR);
		$req->bindValue(':articleImageUrl',urlencode($article->getImage()),\PDO::PARAM_STR);
		$req->bindValue(':articleContent',$article->getContent(),\PDO::PARAM_STR);
		$req->bindValue(':articleContentRight',$article->getContentRight(),\PDO::PARAM_STR);

		$req->execute();

		
	}

	public function updateArticle(ArticleHydrate $article)
	{
		$db = $this->dbConnect();
		if (empty($article->getImage())) {
			$req = $db->prepare('UPDATE posts SET title = :articleTitle, content = :articleContent, content_right = :articleContentRight WHERE id = :articleId');
		}else{
			$req = $db->prepare('UPDATE posts SET title = :articleTitle, image = :articleImageUrl, content = :articleContent, content_right = :articleContentRight WHERE id = :articleId');
			$req->bindValue(':articleImageUrl',urlencode($article->getImage()),\PDO::PARAM_STR);
		}
		$req->bindValue(':articleId',$article->getId(),\PDO::PARAM_INT);
		$req->bindValue(':articleTitle',$article->getTitle(),\PDO::PARAM_STR);
		$req->bindValue(':articleContent',$article->getContent(),\PDO::PARAM_STR);
		$req->bindValue(':articleContentRight',$article->getContentRight(),\PDO::PARAM_STR);
		$req->execute();

		
	}

	public function countTableRows($table)
	{
		$db = $this->dbConnect();
		$req = $db->query('SELECT  COUNT(*) as totalRows FROM '. $table .'' );
		$donnees = $req->fetch();
		$req->closeCursor();
		return $donnees['totalRows'];

	}
	public function getPaginateTable($table, $startLine, $nbResult, $orderBy)
	{
		$db = $this->dbConnect();
		$req = $db->query('SELECT * FROM '. $table .' ORDER BY '. $orderBy .' LIMIT ' . $startLine . ', '. $nbResult . '');
		$res = $req->fetchAll(\PDO::FETCH_ASSOC);
		foreach ($res as $key =>$element) {
			$element['image'] = urldecode($element['image']);
			$res[$key] = $element;
		}
		return $res;
		

		
	}
	
}