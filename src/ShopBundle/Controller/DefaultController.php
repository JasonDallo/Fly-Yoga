<?php

namespace ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ShopBundle\Entity\Entities;
use ShopBundle\Entity\Cartes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;


    /**
     * @Route("/shop")
     */
    class DefaultController extends Controller
    {
        private $APIUSERNAME = "engine.serv.hosting_api1.gmail.com";
        private $APIPWD = "XXX";
        private $APISIGNATURE = "XXX";

    public function __construct(){
        if (!isset($session)) {
            $session = new Session();
        }
        if (!$session->has('panier')) {
            $session->set('panier', array());
        }
    }
    
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $items = $em->getRepository('ShopBundle:Article')->findArticles();
        $panier = $this->getCart();
        return $this->render('shop/index.html.twig', array('items' => $items, 'panier' => $panier));
    }
    /**
     * @Route("/cards", name="getonlyCards")
     * @Method("GET")
     */
    public function getOnlyCards(){
        $em = $this->getDoctrine()->getEntityManager();
        $array = array();
        $cards = $em->getRepository('ShopBundle:Article')->findBy(array('typeId' => '1'));

        foreach ($cards as $key => $value) {
            $array[$key]['id'] = $value->getId();
            $array[$key]['name'] = $value->getName();
            $array[$key]['prix'] = $value->getPrix();
            $array[$key]['desc'] = $value->getDescription();
        }

        return new JsonResponse($array);
    }
    /**
     * @Route("/cards/connected", name="getUserConnected")
     * @Method("GET")
     */
    public function getUserConnected(){
        $securityContext = $this->container->get('security.authorization_checker');
        $array = array();
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $array[] = 'true';
            return new JsonResponse($array);
        }
        return new JsonResponse($array);
    }
    /**
     * @Route("/buy", name="buyItem")
     * @Method("POST")
     */
    public function buyArticleAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $id = $request->get('id');
        $item = $em->getRepository('ShopBundle:Article')->find($id);
        $user = $this->getUser();
        if (!isset($session)) {
            $session = new Session();
        }
        $this->addToCart($session, $item);
        $session->getFlashBag()->set('info_buy', "Vous avez bien ajouté l'article ".$item->getName()." au panier.");

        return $this->redirect('/shop');
    }

    /**
     * @Route("/order", name="order")
     * @Method("POST")
     */
    public function filterArticle(Request $request){
        $em = $this->getDoctrine()->getEntityManager();
        $filter = $request->get('filter');

        if ($filter == 'type') {
            $items = $em->getRepository('ShopBundle:Article')->findBy(array(), array("typeId" => "ASC"));
        }
        elseif($filter == 'prix'){
            $items = $em->getRepository('ShopBundle:Article')->findBy(array(), array("prix" => "ASC"));
        }
        elseif($filter == 'older'){
            $items = $em->getRepository('ShopBundle:Article')->findBy(array(), array("createdAt" => "ASC"));
        }
        elseif($filter == 'earlier'){
            $items = $em->getRepository('ShopBundle:Article')->findBy(array(), array("createdAt" => "DESC"));
        }

        $panier = $this->getCart();
        return $this->render('shop/index.html.twig', array('items' => $items, 'panier' => $panier));
    }



    /**
     * @Route("/search", name="searchItem")
     * @Method("POST")
     */
    public function searchAction(Request $request){
        $em = $this->getDoctrine()->getEntityManager();

        $items = $em->getRepository('ShopBundle:Article')->SearchBy($request->get('keyword'));

        return $this->render('shop/index.html.twig', array('items' => $items, 'keyword' => $request->get('keyword')));
    }

    public function registerEntities($items){
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->getUser();
        if (!isset($session)) {
            $session = new Session();
        }
        foreach ($items as $item) {
            for ($i= 1; $i <= $item->quantity ; $i++) { 
                $entities = new Entities();
                $entities->setUserId($user->getId());
                $entities->setArticleId($item->getId());
                $em->persist($entities);
                $em->flush();

                if ($item->getTypeId() == 1) {
                    preg_match_all('!\d+!', $item->getName(), $matches);
                    $newCard = new Cartes();
                    $newCard->setUserId($user->getId());
                    $newCard->setArticleId($item->getId());
                    $newCard->setDate(new \Datetime());
                    $newCard->setType($item->getTypeId());
                    $newCard->setMontantAvailable(current(current($matches)));
                    $em->persist($newCard);
                    $em->flush();
                }
            }
        }
    }



    // ====================================================PANIER====================================================

    public function addToCart($session,$item){
        $id = $item->getId();
        $panier = $session->get('panier');

        if (!array_key_exists($id, $panier)) {
            $panier[$id] = 1;
        }
        else {
            $panier[$id]++;
        }
        $session->set('panier', $panier);
    }
    /**
     * @Route("/cart", name="showCart")
     * @Method("GET")
     */
    public function showPanierAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $items = array();
        $total = 0;
        if (!isset($session)) {
            $session = new Session();
        }
        foreach ($session->get('panier') as $key => $value) {
            $item = $em->getRepository('ShopBundle:Article')->find($key);
            $item->quantity = $value;
            $total+= $item->getPrix()*$value;
            array_push($items, $item);
        }
        return $this->render('shop/cart.html.twig', array('items' => $items, 'total' => $total));
    }



    /**
     * @Route("/cart/del", name="delFromCart")
     * @Method("POST")
     */
    public function delPanierAction(Request $request){
        $em = $this->getDoctrine()->getEntityManager();
        $id = $request->get('id');

        if (!isset($session)) {
            $session = new Session();
        }
        $panier = $session->get('panier');

        if ($panier[$id] > 1) {
            $session->set($id, $panier[$id]);
        }
        else{
            unset($panier[$id]);
            $session->set('panier', $panier);
        }

        return $this->redirect('/shop/cart');
    }

    public function getCart(){
        $em = $this->getDoctrine()->getEntityManager();
        $items = array();

        if (!isset($session)) {
            $session = new Session();
        }

        foreach ($session->get('panier') as $key => $value) {
            $item = $em->getRepository('ShopBundle:Article')->find($key);
            $item->quantity = $value;
            array_push($items, $item);

        }

        return $items;
    }

    public function clearCart(){
        if (!isset($session)) {
            $session = new Session();
        }
        $session->set('panier', []);
    }
    // ====================================================MAILS====================================================

    public function sendCheckoutMail($items){
        $str = '';
        foreach ($items as $key => $value) {
            $str += '- '.$value->quantity .'x'. $value->getPrix() .' '. $value->getName() .' <br>';
        }
        $message = \Swift_Message::newInstance('Nous vous remercions pour votre achat !')
        ->setFrom(array('service-client@fly-yoga.ovh' => 'Service Client Fly-Yoga'))
        ->setTo(array($this->getUser()->getEmailCanonical() => 'Bonjour'))
        ->setBody('Bonjour '.$this->getUser()->getFirstname().' '.$this->getUser()->getLastname().', nous vous remercions de la confiance que vous apportez à Fly-Yoga ©.<br>Se trouve ci-joint votre facture pour vos achats:'.$str.' Cordialement,<br> Service Client Fly-Yoga<br>www.fly-yoga.fr');
        $this->get('mailer')->send($message);
    }


    // ====================================================PAYPAL====================================================


    /**
     * @Route("/cart/checkout", name="cartCheckout")
     * @Method("GET")
     */
    public function paypalPostAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $items = array();
        $total = 0;
        if (!isset($session)) {
            $session = new Session();
        }
        foreach ($session->get('panier') as $key => $value) {
            $item = $em->getRepository('ShopBundle:Article')->find($key);
            $item->quantity = $value;
            $total+= $item->getPrix()*$value;
            array_push($items, $item);
        }
        $opt = ['USER' =>$this->APIUSERNAME,'PWD' =>$this->APIPWD,'SIGNATURE' =>$this->APISIGNATURE,'METHOD' => 'SetExpressCheckout','VERSION' => 95,'PAYMENTREQUEST_0_PAYMENTACTION' => 'AUTHORIZATION','PAYMENTREQUEST_0_AMT' => $total,'PAYMENTREQUEST_0_CURRENCYCODE' => 'EUR','PAYMENTREQUEST_0_CUSTOM' => $items,'L_BILLINGTYPE0' => 'MerchantInitiatedBilling','L_BILLINGAGREEMENTDESCRIPTION0' => 'Amount of your cart','cancelUrl' => '','returnUrl' => ''];
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
     * @Route("/cart/checkout/response", name="cartCheckoutResponse")
     * @Method("GET")
     */
    public function getPaypalResponseAction(Request $request){
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
            return $this->redirect('/shop');
        }
        elseif($parsed['ACK'] == 'Success'){
            $payement = $this->registerPayement($parsed, $pars);
            if ($payement['PAYMENTSTATUS'] == 'Completed') {
                $items = $this->getCart();
                $this->registerEntities($items);
                $this->sendCheckoutMail($items);
                $this->clearCart();
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
