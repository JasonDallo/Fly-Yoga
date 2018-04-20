<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use BlogBundle\Entity\Article;
use BlogBundle\Entity\Comments;
use BlogBundle\Entity\Subscribers;

class DefaultController extends Controller
{

  //===========================================================ADMIN===========================================================

	/**
    * @Route("/admin/blog",name="listBlog")
    * @Method("GET")
    */
	public function AdminblogListAction(){
		$em = $this->getDoctrine()->getManager();

		$articles = $em->getRepository('BlogBundle:Article')->findBy(array(), array("createdAt" => "DESC"), 10);
		foreach ($articles as $key => $value) {
			$user = $em->getRepository('UsersBundle:User')->findBy(array('id' => $value->getUserId()));
			$value->user = $user;
			$comments = $em->getRepository('BlogBundle:Comments')->findBy(array('articleId' => $value->getId()));
			$value->comments = $comments;
		}
		$lastArticles = $this->getLastArticles();


		return $this->render('admin/blog/list.html.twig', array('articles' => $articles, "lastArticles" => $lastArticles));
	}
	/**
    * @Route("/admin/blog/add",name="addBlog")
    * @Method("GET")
    */
	public function AdminblogAddAction(){

		return $this->render('admin/blog/add.html.twig');
	}
	/**
    * @Route("/admin/blog/added",name="addedBlog")
    * @Method("POST")
    */
	public function AdminblogAddedAction(Request $request){
		$em = $this->getDoctrine()->getManager();
		$array = explode(';', $request->get('tags'));

		$article = new Article();

		$article->setTitle($request->get('title'));
		$article->setContent($request->get('content'));
		$article->setUserId($this->getUser()->getId());
		$article->setVideoUrl($request->get('url'));
		$article->setTags($array);

		$em->persist($article);
		$em->flush();

		mkdir(__DIR__.'/../../../web/img/blog/'.$article->getId(), 0777);
		if (!is_null($request->files->get('img'))) {
			$request->files->get('img')->move(__DIR__.'/../../../web/img/blog/'.$article->getId() , '1.jpg');
		}

		return $this->redirect('/admin/blog');
	}
	/**
    * @Route("/admin/blog/{id}",name="showAdminBlog")
    * @Method("GET")
    */
	public function showAdminBlog($id){
		$em = $this->getDoctrine()->getManager();
		$article = $em->getRepository('BlogBundle:Article')->find($id);
		$user = $em->getRepository('UsersBundle:User')->find(array('id' => $article->getUserId()));

		return $this->render('admin/blog/edit.html.twig', array('article' => $article, "user" => $user));

	}


	/**
    * @Route("/admin/blog/{id}/edit",name="adminBlogEdit")
    * @Method("POST")
    */
	public function editAdminBlog(Request $request ,$id){
		$em = $this->getDoctrine()->getManager();
		$article = $em->getRepository('BlogBundle:Article')->find($id);
		if (is_null($article)) {
        	$session->getFlashBag()->set('err_editBlog', "Il y a eu une errreur dans l'édition de votre article.");
			return $this->redirect('/admin/blog');
		}
			\Doctrine\Common\Util\Debug::dump($article);
	}


  //===========================================================BLOG===========================================================

	/**
    * @Route("/blog",name="Blog")
    * @Method("GET")
    */
	public function blogListAction(){
		$em = $this->getDoctrine()->getManager();

		$articles = $em->getRepository('BlogBundle:Article')->findBy(array(), array("createdAt" => "DESC"));
		foreach ($articles as $key => $value) {
			$user = $em->getRepository('UsersBundle:User')->findBy(array('id' => $value->getUserId()));
			$value->user = $user;
			$comments = $em->getRepository('BlogBundle:Comments')->findBy(array('articleId' => $value->getId()));
			$value->comments = $comments;

			$imgs = $this->getFiles($value);
			$value->imgs = $imgs;
		}
		$lastArticles = $this->getLastArticles();


		return $this->render('blog/list.html.twig', array('articles' => $articles, 'lastArticles' => $lastArticles));
	}
	/**
    * @Route("/blog?id={id}",name="showBlogArticle")
    * @Method("GET")
    */
	public function getArticleAction($id){
		$em = $this->getDoctrine()->getManager();
		$article = $em->getRepository('BlogBundle:Article')->find(array('id' => $id));
		$user = $em->getRepository('UsersBundle:User')->find(array('id' => $article->getUserId()));
		$comments = $em->getRepository('BlogBundle:Comments')->findBy(array('articleId' => $article->getId()), array('createdAt' => 'DESC'));

		foreach ($comments as $key => $value) {
			$value = $this->getUserbyComment($value);
		}
		if (empty($article)) {
			$this->return404();
		}
		$lastArticles = $this->getLastArticles();
		return $this->render('blog/show.html.twig', array('article' => $article, 'user' => $user, 'comments' => $comments, 'lastArticles' => $lastArticles));

	}

	/**
    * @Route("/blog/search",name="searchBlog")
    * @Method("POST")
    */
	public function getBySearchAction(Request $request){
		$em = $this->getDoctrine()->getManager();
		$comments = array();

		$article = $em->getRepository('BlogBundle:Article')->findByTitle($request->get('search'));
		foreach ($article as $key => $value) {
			$comments = $em->getRepository('BlogBundle:Comments')->findBy(array('articleId' => $value->getId()));
			$value->comments = $comments;

			$user = $em->getRepository('UsersBundle:User')->findBy(array('id' => $value->getUserId()));
			$value->user = $user;
		}
		$lastArticles = $this->getLastArticles();


		return $this->render('blog/search.html.twig', array('articles' => $article,'comments' => $comments, 'keyword' => $request->get('search'), 'lastArticles' => $lastArticles));
	}



  //===========================================================UTILITIES===========================================================

	public function getFiles($article){
		$imgs = array();
		$files = scandir(__DIR__.'/../../../web/img/blog/'.$article->getId());
		foreach ($files as $key => $value) {
			if (strlen($value) > 3) {
				array_push($imgs, $value);
			}
		}
		return $imgs;
	}

	public function getUserbyComment($comment){
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('UsersBundle:User')->find(array('id' => $comment->getUserId()));
		$comment->user = $user;
		return $comment;
	}


	public function getLastArticles(){
		$em = $this->getDoctrine()->getManager();
		$articles = $em->getRepository('BlogBundle:Article')->findBy(array(), array("createdAt" => "DESC"), 3);

		return $articles;
	}


  //===========================================================COMMENTS===========================================================


	/**
    * @Route("/blog?id={id}",name="addComment")
    * @Method("POST")
    */
	public function postComment(Request $request, $id){
		$em = $this->getDoctrine()->getManager();
		$comment = new Comments();
		$comment->setContent(htmlentities($request->get('content')));
		$comment->setUserId($this->getUser()->getId());
		$comment->setArticleId($id);

		$em->persist($comment);
		$em->flush();

		return $this->redirect('/blog?id='.$id);
	}
	/**
    * @Route("/blog?id={id}?comment={comment}",name="deleteComment")
    * @Method("GET")
    */
	public function deleteComment($id, $comment){
		$em = $this->getDoctrine()->getManager();
		if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
			$comment = $em->getRepository('BlogBundle:Comments')->find(array('id' => $comment));
			\Doctrine\Common\Util\Debug::dump($comment);
		}
	}
  //===========================================================SUBS===========================================================

	/**
    * @Route("/email/sub",name="subRegister")
    * @Method("POST")
    */
	public function subRegisterAction(Request $request){
		$em = $this->getDoctrine()->getManager();
        $session = new Session();


		$sub = new Subscribers();
		$sub->setEmail($request->get('email'));

		$em->persist($sub);
		$em->flush();

        $session->getFlashBag()->set('sub_register', "Vous vous êtes bien abonné à notre newsletter.");

        return $this->redirect('/');
	
	}



  //===========================================================OTHERS===========================================================


	public function return404(){
		return $this->render('errors/404.html');
	}
}
