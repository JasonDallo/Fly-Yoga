<?php

namespace PartnerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use PartnerBundle\Entity\Partner;
use PartnerBundle\Entity\Comments;



class DefaultController extends Controller
{
     /**
     * @Route("/partenaires", name="part")
     */
     public function PartenaireAction()
     {
        $em = $this->getDoctrine()->getManager();
        $partners = $em->getRepository('PartnerBundle:Partner')->findAll();
        $partenaires = $this->getImageParts($partners);

        if($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')){
            $user = $this->getUser();
            $myParts = $em->getRepository('PartnerBundle:Partner')->findBy(array('userId' => $user->getId()));
            $mines = $this->getImageParts($myParts);
            return $this->render('partner/partenaires.html.twig', array('partners'=> $partenaires, 'myParts' => $mines));
        }
        return $this->render('partner/partenaires.html.twig', array('partners'=> $partenaires));
    }

    /**
     * Creates a new Partner entity.
     *
     * @Route("/partenaires/new",name="Partnernew")
     * @Method("POST")
     */
    public function PartenairespostAction(Request $request){
    	$em = $this->getDoctrine()->getManager();

        $newPart = new Partner();
        $newPart->setName($request->get('name'));
        $newPart->setPlace($request->get('place'));
        $newPart->setDescription($request->get('desc'));
        $newPart->setUserId($this->getUser()->getId());

        $em->persist($newPart);
        $em->flush();

        mkdir(__DIR__.'/../../../web/img/partenaires/'.$newPart->getId());

        return $this->redirect('/partenaires');
    }

    /**
     *
     * @Route("/partenaires/{id}",name="showPartner")
     * @Method("GET")
     */
    public function getPartnertAction($id){
        $em = $this->getDoctrine()->getManager();
        $part = $em->getRepository('PartnerBundle:Partner')->find($id);
        if (is_null($part)) {
            $referer = $this->getRequest()->headers->get('referer');
            return $this->redirect($referer);
        }
        $comments = $em->getRepository('PartnerBundle:Comments')->findBy(array('partnerId' => $id));
        $C = $this->getUserbyComments($comments);
        $partenaires = $this->getImageParts($part);

        return $this->render('partner/show.html.twig', array('part' =>  $partenaires, 'comments' => $C));

    }

    /**
     *
     * @Route("/partenaires/search",name="searchPartner")
     * @Method("POST")
     */
    public function searchPartnertAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $parts = $em->getRepository('PartnerBundle:Partner')->searchPartner($request->get('search'));
        
        $partenaires = $this->getImageParts($parts);
        if($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')){
            $user = $this->getUser();
            $myParts = $em->getRepository('PartnerBundle:Partner')->findBy(array('userId' => $user->getId()));
            return $this->render('partner/search.html.twig', array('parts'=> $parts, 'myParts' => $myParts, 'keyword' => $request->get('search')));
        }
        
        return $this->render('partner/search.html.twig', array('parts' =>  $partenaires, 'keyword' => $request->get('search')));

    }

    /**
     * @Route("/partenaires/postComment{id}",name="addCommentasPartner")
     * @Method("POST")
     */
    public function addCommentasPartnerAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('PartnerBundle:Partner')->find($id);
        if (is_null($article)) {
            return $this->redirect('/partenaires');
        }

        $comment = new Comments();
        $comment->setContent($request->get('content'));
        $comment->setUserId($this->getUser()->getId());
        $comment->setPartnerId($article->getId());

        $em->persist($comment);
        $em->flush();

        return $this->redirect('/partenaires/'.$id);
    }
    /**
     *
     * @Route("/partenaires/postImages/{id}",name="postImages")
     * @Method("POST")
     */
    public function addImagetoPartner(Request $request){
        $em =$this->getDoctrine()->getManager();

        \Doctrine\Common\Util\Debug::dump($this->getUploadRootDir());
        exit();

    }


    public function getImageParts($parts){
        $em = $this->getDoctrine()->getManager();
        $imgs = array();

        if (!is_array($parts)) {
            $files = scandir(__DIR__.'/../../../web/img/partenaires/'.$parts->getId());
            foreach ($files as $clé => $valeur) {
                if (strlen($valeur) > 3) {
                    array_push($imgs, $valeur);
                }
            }
            $parts->images = $imgs;
        }
        else{
            foreach ($parts as $key => $value) {
                $files = scandir(__DIR__.'/../../../web/img/partenaires/'.$value->getId());
                foreach ($files as $clé => $valeur) {
                    if (strlen($valeur) > 3) {
                        array_push($imgs, $valeur);
                    }
                }
                $value->images = $imgs;
                unset($imgs);
                $imgs = array();
            }
        }
        return $parts;
    }

    public function getUserbyComments($comments){
        $em = $this->getDoctrine()->getManager();

        foreach ($comments as $key => $value) {
            $user = $em->getRepository('UsersBundle:User')->find($value->getUserId());
            $value->user = $user;
        }
        return $comments;
    }
}
