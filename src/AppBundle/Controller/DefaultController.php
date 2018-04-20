<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use UsersBundle\Entity\Mails;
use Symfony\Component\HttpFoundation\Session\Session;



class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ids = $em->getRepository('ShopBundle:Article')->getIds();
        $items = $em->getRepository('ShopBundle:Article')->getByArray($ids);
        $profs = $em->getRepository('UsersBundle:User')->findByroleLimit("ROLE_ENSEIGNANT", '4');
        return $this->render('default/index.html.twig', array('items' => $items,'profs' => $profs));
    }

    public function LoginViewAction(){
        return $this->view('FOSUser:Security:login.html.twig');
    }
    /**
     *
     * @Route("/me",name="showMine")
     * @Method("GET")
     */
    public function getUserInfoAction(){
        $em = $this->getDoctrine()->getManager();
        if (is_null($this->getUser())) {
            return $this->redirect('/login');
        }
        $OffCards = array();
        $OnCards = array();

        $user = $this->getUser();
        $cards = $this->getmyCards();
        foreach ($cards as $key => $value) {
            if ($value->getDispo() == 1) {
               $carte = $em->getRepository('ShopBundle:Article')->find($value->getArticleId());
               $carte->amount = $value->getMontantAvailable();
               $carte->date = $value->getDate();
               array_push($OnCards, $carte);
            }
            else{
                $carte = $em->getRepository('ShopBundle:Article')->find($value->getArticleId());
                $carte->amount = $value->getMontantAvailable();
                $carte->date = $value->getDate();
                array_push($OffCards, $carte);
            }
        }
        return $this->render('user/show.html.twig', array('user' => $user, 'OnCards' => $OnCards, 'OffCards' => $OffCards));
    }

    /**
     *
     * @Route("/me",name="editMine")
     * @Method("POST")
     */
    public function editUserInfoAction(Request $request){
        $session = new Session();
        if (is_null($this->getUser())) {
            return $this->redirect('/login');
        }
        $user = $this->getUser();
        if ($request->get('password') != $request->get('confirm')) {
            $session->getFlashBag()->set('pass_doesnt_match', "Les mots de passes ne correspondent pas.");
            return $this->redirect('/me');
        }

    }
    /**
     *
     * @Route("/contact",name="contact")
     * @Method("GET")
     */
    public function contact(){
        if ($this->getUser()) {
            return $this->render('default/contact.html.twig');
        }
        return $this->redirect('/login');
    }
    /**
     *
     * @Route("/contact",name="messagePost")
     * @Method("POST")
     */
    public function messagePost(Request $request){
        $em = $this->getDoctrine()->getManager();
        $session = new Session();

        $mail = new Mails();

        $mail->setTitle($request->get('title'));
        $mail->setContent($request->get('content'));
        $mail->setOrigin($this->getUser()->getEmailCanonical());
        $mail->setUsersMails(array('service-client@fly-yoga.ovh'));
        $mail->setAvailable(true);

        $em->persist($mail);
        $em->flush();

        $session->getFlashBag()->set('mess_posted', "Message envoyé au service client de Fly-Yoga.");

        return $this->render('default/contact.html.twig');
    }

    public function getmyCards(){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $cards = $em->getRepository('ShopBundle:Cartes')->findBy(array('userId' => $user->getId()));
        return $cards;
    }

}
