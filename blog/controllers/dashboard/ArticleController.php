<?php
namespace blog\controllers\dashboard;
use blog\exceptions\NotFoundHttpException;
use blog\models\Article;


/**
 * Description of PostController
 *
 * @author Sabri Hamda
 */
class ArticleController extends Controller{
    
    
    //List all articles
    public function index(){
        echo $this->render('articles/index.twig');
    }
    
    // view article by id
    public function view($id){
        $article = $this->getArticle($id);
        echo $this->render('articles/view.twig');
    }
    
    
    //create a new article
    public function create(){
        echo $this->render('articles/create.twig');
    }
    
    //update article
    public function update($id){
        echo $this->render('articles/update.twig');
    }




    //delete existing article
    public function delete($id){
        $article = $this->getArticle($id);
        echo 'article delete';
    }
    
    
    // load article from database 
    // throw 404 if article is not found;
    private function getArticle($id){
        $article = Article::find($id);
        if($article === null){
            throw new NotFoundHttpException('Article doesn\'t exist!');
        }
        return $article;
    }
}