<?php

namespace CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use CourseBundle\Entity\CourseSubs;
use CourseBundle\Entity\Creneaux;
use CourseBundle\Entity\Course;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/courses")
*/
class DefaultController extends Controller
{

    private $APIUSERNAME = "engine.serv.hosting_api1.gmail.com";
    private $APIPWD = "AVJVWZE8RSJ6E9BP";
    private $APISIGNATURE = "ASb87Huyn7qxcXQ9-Lj7Gfl0gnK.AAv6IVJlx37cJ-U-mRQcV1y6GnXt";
    /**
     * @Route("/")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $allCourses = $em->getRepository('CourseBundle:Course')->getVisibleCourses();
        $cours = $this->getWeekly($allCourses, 0);
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $cartes = $em->getRepository('ShopBundle:Cartes')->CartesbyDispo($this->getUser()->getId());
            return $this->render('cours/cours.html.twig', array('cours' => $cours, "cartes" => $cartes));
        }
        return $this->render('cours/cours.html.twig', array('cours' => $cours));
    }
    /**
     * @Route("/sub", name="courseSub")
     * @Method("POST")
     */
    public function courseSub(Request $request){
        $session = new Session();
        $em = $this->getDoctrine()->getEntityManager();
        $course = $em->getRepository('CourseBundle:Course')->find($request->get('id'));
        $user = $this->getUser();
        $cards = $em->getRepository('ShopBundle:Cartes')->CartesbyDispo($user->getId());
        $capital = 0;
        
        if(!empty($cards)){
            foreach ($cards as $key ) {
                $capital += $key->getMontantAvailable();
            }
            if ($capital >= $course->getTarif()) {
                foreach ($cards as $key) {
                    if (($key->getMontantAvailable() - $course->getTarif()) < 0) {
                        $rest = abs($key->getMontantAvailable() - $course->getTarif());
                        $key->setMontantAvailable(0);
                        $em->persist($key);
                        $em->flush();

                    }
                    elseif(($key->getMontantAvailable() - $course->getTarif()) == 0){
                        $key->setMontantAvailable(0);
                        $key->setDispo(0);
                        $em->persist($key);
                        $em->flush();
                        break;
                    }
                    else{
                        $key->setMontantAvailable($key->getMontantAvailable() - $course->getTarif());
                        $em->persist($key);
                        $em->flush();
                        break;
                    }
                }
                if ($this->getPlaceDispo($course) == true) {
                    $sub = new CourseSubs();
                    $sub->setUserId($this->getUser()->getId());
                    $sub->setCalendarDispoId($request->get('id'));
                    $sub->setDateReservation(new \Datetime());
                    $sub->setNbrPlace($course->getNbrPlace());/*
                    $sub->setConfirmationName($request->get('name'));
                    $sub->setConfirmationTel($request->get('tel'));*/
                    $course->setPlacesUtilisées($course->getPlacesUtilisées()+1);

                    $em->persist($sub);
                    $em->persist($course);
                    $em->flush();

                    return $this->redirect('/courses');
                }
                $session->getFlashBag()->set('no_place', "Il n'y a plus de places disponibles.");
                return $this->redirect('/courses');
            }
            else{
                $session->getFlashBag()->set('no_enough_money', "L'argent disponible sur votre/vos carte(s) n'est pas suffisant pour s'inscrire à ce cours.");
                return $this->redirect('/courses');
            }
        }
        else{
            $session->getFlashBag()->set('no_cards', "Vous ne disposez d'aucune carte actuellement.");
            return $this->redirect('/courses');
        }
    }


    public function getPlaceDispo($course){
        if ($course->getPlacesUtilisées() < $course->getNbrPlace()) {
            return true;
        }
        else{
            return false;
        }
    }


    public function getWeekly($creneaux, $week){
        $lasts = array();
        $now = strtotime('+'.$week.' week');
        $beginning_of_week = strtotime('last Monday', $now);
        $end_of_week = strtotime('next Sunday', $now) + 86400;
        foreach ($creneaux as $key => $value) {
            $date = substr($value->getDateDebut(),0 ,strlen($value->getDateDebut())-6);
            $find = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
            $replace = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
            $date = str_replace($find, $replace, strtolower($date));

            if(strtotime($date) > $beginning_of_week && strtotime($date) < $end_of_week){
                array_push($lasts, $value);
            }
        }
        return $lasts;
    }

    public function getEarlier($creneaux){
        $lasts = array();
        foreach ($creneaux as $key => $value) {
            if (substr($value->getDateDebut(),strlen($value->getDateDebut())-5,2) < 12) {
                array_push($lasts, $value);
            }
        }
        return $lasts;
    }
    /**
     * @Route("/mines", name="minesCourses")
     * @Method("GET")
     */
    public function getMines(){
        $em = $this->getDoctrine()->getManager();
        if (is_null($this->getUser())) {
            return $this->redirect('/login');
        }
        $user = $this->getUser()->getId();
        $courses = $em->getRepository('CourseBundle:CourseSubs')->findBy(array('userId' => 1));
        $lasts = array();
        foreach ($courses as $key => $value) {
            $entity = $em->getRepository('CourseBundle:Course')->find($value->getCalendarDispoId());
            if (substr($entity->getDateDebut(),strlen($entity->getDateDebut())-5,2) < 12) {
                array_push($lasts, $entity);
            }
        }
        return $this->render('cours/mines.html.twig', array('courses' => $lasts));
    }
    /**
     * @Route("/weekly", name="getCoursesEvents")
     * @Method("GET")
     */
    public function getCoursesEvents(){
        $em = $this->getDoctrine()->getManager();
        $array = array();
        $allCourses = $em->getRepository('CourseBundle:Course')->getVisibleCourses();
        foreach ($allCourses as $key => $value) {
            $array[$key]['id'] = $value->getId();
            $array[$key]['title'] = $value->getTitle();
            $array[$key]['date_debut'] = date("Y-m-d", strtotime($this->getEventDate($value))). substr($value->getDateDebut(), strlen($value->getDateDebut())-6, 6);
        }


        return new JsonResponse($array);
    }

    /**
     * @Route("/event", name="getEvent")
     * @Method("POST")
     */
    public function getEvent(Request $request){
        $em = $this->getDoctrine()->getManager();
        $array = array();

        $course = $em->getRepository('CourseBundle:Course')->find($request->get('id'));
        $prof = $em->getRepository('UsersBundle:User')->find($course->getProfId());

        $array['prof'] =$prof->getFirstName(). ' '. $prof->getLastname();
        $array['lieu'] =$course->getLieu();
        $array['nbr_place'] =$course->getNbrPlace();
        $array['couleur']= $course->getCouleur();
        $array['tarif']= $course->getTarif();
        $array['type'] =$course->getType();
        $array['duree'] =$course->getDuree();
        $array['placeUses']= $course->getPlacesUtilisées();

        return new JsonResponse($array);
    }

    public function getEventDate($course){
        $date = substr($course->getDateDebut(),0 ,strlen($course->getDateDebut())-6);
        $find = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
        $replace = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $date = str_replace($find, $replace, strtolower($date));
        return $date;
    }
    /**
     * @Route("/cards", name="getCards")
     * @Method("GET")
     */
    public function getCartesforCourse(){
        $em = $this->getDoctrine()->getManager();
        $array = array();
        $cartes = $em->getRepository('ShopBundle:Article')->findBy(array('typeId' => '1'));
        foreach ($cartes as $key => $value) {
            $array[$key]['id'] = $value->getId();
            $array[$key]['name'] = $value->getName();
            $array[$key]['prix'] = $value->getPrix();
            $array[$key]['description'] = $value->getDescription();

        }
        return new JsonResponse($array);
    }





// ============================================================================================================================================================
//                                                                                   PAYPAL
// ============================================================================================================================================================



    /**
     * @Route("/cards/checkout", name="fastBuyCards")
     * @Method("POST")
     */
    public function fastBuyAction(Request $request){
        $em = $this->getDoctrine()->getEntityManager();
        $article = $em->getRepository('ShopBundle:Article')->find($request->get('id'));
        if (is_null($article)) {
            return $this->redirect('/courses');
        }
        $opt = ['USER' => $this->APIUSERNAME,'PWD' =>$this->APIPWD,'SIGNATURE' =>$this->APISIGNATURE,'METHOD' => 'SetExpressCheckout','VERSION' => 95,'PAYMENTREQUEST_0_PAYMENTACTION' => 'AUTHORIZATION','PAYMENTREQUEST_0_AMT' => $article->getPrix(),'PAYMENTREQUEST_0_CURRENCYCODE' => 'EUR','PAYMENTREQUEST_0_CUSTOM' => $article->getId(),'L_BILLINGTYPE0' => 'MerchantInitiatedBilling','L_BILLINGAGREEMENTDESCRIPTION0' => 'Achat rapide de '.$article->getName(),'cancelUrl' => 'http://p1.armudev.engine-group.eu/courses','returnUrl' => 'http://p1.armudev.engine-group.eu/courses/cards/checkout/response'];
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api-3t.paypal.com/nvp',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($opt)
            ]);
        $resp = curl_exec($curl);
        $parsed = array();
        parse_str($resp, $parsed);

        $url = "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=".$parsed['TOKEN'];
        return $this->redirect($url);
    }
     /**
     * @Route("/cards/checkout/response", name="responseToFastbuy")
     * @Method("GET")
     */
     public function responseTofastBuyAction(Request $request){
        $pars = $this->getCheckoutDetails($request->get('token'));
        $opt = ['USER' =>$this->APIUSERNAME,'PWD' =>$this->APIPWD,'SIGNATURE' =>$this->APISIGNATURE,'METHOD' => 'DoExpressCheckoutPayment','VERSION' => 95, 'TOKEN' => $pars['TOKEN'] ,'PAYMENTREQUEST_0_PAYMENTACTION' => 'Authorization', 'PAYMENTREQUEST_0_AMT' => $pars['AMT'],
        'PAYMENTREQUEST_0_CURRENCYCODE' => 'EUR', 'PAYERID' => $pars['PAYERID']];
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api-3t.paypal.com/nvp',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($opt)
            ]);
        $resp = curl_exec($curl);
        $parsed = array();
        if (!isset($session)) {
            $session = new Session();
        }
        parse_str($resp, $parsed);
        if (isset($parsed['L_SHORTMESSAGE0'])) {
            $session->getFlashBag()->set('err_Paypal', $parsed['L_SHORTMESSAGE0']);
            return $this->redirect('/courses');
        }
        elseif($parsed['ACK'] == 'Success'){
            $payement = $this->registerPayement($parsed, $pars);
            if ($payement['PAYMENTSTATUS'] == 'Completed') {
                $item = $em->getRepository('ShopBundle:Article')->find($pars['CUSTOM']);
                $this->registerEntities($item);
                $this->sendCheckoutMail($item);
                return $this->redirect('/shop');
            }
        }
    }

        public function registerPayement($Agreement, $details){
        $opt = ['USER' =>$this->APIUSERNAME,'PWD' =>$this->APIPWD,'SIGNATURE' =>$this->APISIGNATURE,'METHOD' => 'DoCapture','VERSION' => 95, 'AMT' =>  $details['AMT'], 'CURRENCYCODE' => 'EUR' , 'AUTHORIZATIONID' => $Agreement['PAYMENTINFO_0_TRANSACTIONID'], 'COMPLETETYPE' => 'Complete'];
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api-3t.paypal.com/nvp',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($opt)
            ]);
        $resp = curl_exec($curl);
        $parsed = array();
        parse_str($resp, $parsed);
        return $parsed;
    }

    public function getCheckoutDetails($token){
        $opt = ['USER' =>$this->APIUSERNAME,'PWD' =>$this->APIPWD,'SIGNATURE' =>$this->APISIGNATURE,'METHOD' => 'GetExPressCheckoutDetails','VERSION' => 95, 'TOKEN' => $token];
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api-3t.paypal.com/nvp',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($opt)
            ]);
        $resp = curl_exec($curl);
        $parsed = array();
        parse_str($resp, $parsed);
        return $parsed;
    }
}