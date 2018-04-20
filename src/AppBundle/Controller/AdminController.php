<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use CourseBundle\Entity\Course;
use CourseBundle\Entity\Creneaux;
use ShopBundle\Entity\Article;
use ShopBundle\Entity\Type;
use ShopBundle\Entity\Entities;
use UsersBundle\Entity\Repository;
use UsersBundle\Entity\Mails;

/**
 * Admin controller.
 *
 * @Route("/admin")
 */
class AdminController extends Controller
{

  /**
  * @Route("/", name="admin_homepage")
  */
    public function AdminCheck(){
    if(is_null($this->getUser())){
      return $this->redirect('/login');
    }
    elseif($this->getUser()->hasRole('ROLE_ADMIN')){
      $logs = $this->logsAction();
      return $this->render('admin/admin_homepage.html.twig', array('logs' => $logs));
    }
    return $this->redirect('/');
  }


  public function logsAction(){
    $em = $this->getDoctrine()->getManager();
    $logs = array();
    $infos_courseSubs = array();
    $infos_entities = array();

    $users = $em->getRepository('UsersBundle:User')->findBy(array(), array('registered_at' => "DESC"), 10);
    $courses_subs = $em->getRepository('CourseBundle:CourseSubs')->findBy(array(), array('dateReservation' => 'DESC'), 10);
    foreach ($courses_subs as $key => $value) {
      $new = array();
      $userByCoursesub = $em->getRepository('UsersBundle:User')->find($value->getUserId());
      $courseByCoursesub = $em->getRepository('CourseBundle:Course')->find($value->getCalendarDispoId());
      $new[] = $userByCoursesub;
      $new[] = $courseByCoursesub;
      $infos_courseSubs[] = $new;
    }
    $entities = $em->getRepository('ShopBundle:Entities')->findBy(array(), array('commandeAt' => 'DESC'), 10);

    foreach ($entities as $key => $value) {
      $new = array();
      $userByEntity = $em->getRepository('UsersBundle:User')->find($value->getUserId());
      $articleByEntity = $em->getRepository('ShopBundle:Article')->find($value->getArticleId());

      $new[] = $userByEntity;
      $new[] = $articleByEntity;
      $infos_entities[] = $new;
    }
    array_push($logs, $users);
    array_push($logs, $infos_courseSubs);
    array_push($logs, $infos_entities);
    return $logs;
  }

    // ============================================= USERS ACTIONS =============================================

    /**
     * @Route("/users",name="getUsers")
     */
    public function getUsersAction(){
      $em = $this->getDoctrine()->getManager();
      $users = $em->getRepository('UsersBundle:User')->findAll();

      return $this->render('admin/users/Userslist.html.twig', array(
        'users' => $users
        )
      );
    }
    /**
     * @Route("/prof",name="getProf")
     */
    public function getProfAction(){
      $em = $this->getDoctrine()->getManager();
      $profs = $em->getRepository('UsersBundle:User')->findByrole('ROLE_ENSEIGNANT');

      return $this->render('admin/users/Profslist.html.twig', array(
        'profs' => $profs
        )
      );
    }

     /**
     * @Route("/users/del",name="deleteUser")
     * @Method("POST")
     */
     public function deleteUserAction(Request $request){
      $em = $this->getDoctrine()->getManager();

      $user = $em->getRepository('UsersBundle:User')->find($request->get('id'));
      $em->remove($user);
      $em->flush();

      return $this->redirect('/admin/prof');
    }
     /**
     * @Route("/users/promote",name="promoteUser")
     * @Method("POST")
     */

     public function promoteUserAction(Request $request){
      $em = $this->getDoctrine()->getManager();

      $user = $em->getRepository('UsersBundle:User')->find($request->get('id'));
      $user->addRole('ROLE_ENSEIGNANT');

      $em->persist($user);
      $em->flush();

      return $this->redirect('/admin/users');
    }
    /**
     * @Route("/prof/demote",name="demoteProf")
     * @Method("POST")
     */

    public function demoteProfrAction(Request $request){
      $em = $this->getDoctrine()->getManager();

      $user = $em->getRepository('UsersBundle:User')->find($request->get('id'));
      $user->removeRole('ROLE_ENSEIGNANT');

      $em->persist($user);
      $em->flush();

      return $this->redirect('/admin/prof');
    }
    /**
     * @Route("/list",name="showAdmins")
     * @Method("GET")
     */
    public function listAdminAction(){
      $em = $this->getDoctrine()->getManager();

      $admins = $em->getRepository('UsersBundle:User')->findByrole('ROLE_ADMIN');

      return $this->render('admin/users/adminlist.html.twig', array(
       'admins' => $admins
       )
      );
    }
    /**
     * @Route("/demote/{id}",name="demoteAdmin")
     * @Method("GET")
     */
    public function demoteAdminAction($id){
      $em = $this->getDoctrine()->getManager();

      $admin = $em->getRepository('UsersBundle:User')->find($id);
      $admin->removeRole('ROLE_ADMIN');

      $em->persist($admin);
      $em->flush();
      

      return $this->redirect('/admin/list');
    }

    /**
     * @Route("/promote/{id}",name="promoteAdmin")
     * @Method("GET")
     */
    public function promoteAdminAction($id){
      $em = $this->getDoctrine()->getManager();

      $user = $em->getRepository('UsersBundle:User')->find($id);
      $user->addRole('ROLE_ADMIN');

      $em->persist($user);
      $em->flush();
      

      return $this->redirect('/admin/list');
    }


    // ============================================= PARTNERS ACTIONS =============================================

     /**
     * @Route("/partners",name="getPartners")
     */
     public function getPartnersAction(){
       $partners = $this->getDoctrine()->getManager()->getRepository('PartnerBundle:Partner')->findAll();
       return $this->render('admin/users/Partnerlist.html.twig', array(
        'partners' => $partners
        )
       );
     }


     /**
     * @Route("/partners/del/{id}",name="deletePart")
     * @Method("GET")
     */
     public function deletePartnerAction($id){
      $em = $this->getDoctrine()->getManager();

      $Partner = $em->getRepository('PartnerBundle:Partner')->find(57);
      $em->remove($Partner);
      $em->flush();
    }


// ============================================= COURSES ACTIONS =============================================
     /**
     * @Route("/cours",name="getCourses")
     */
     public function getCoursesAction(){
      $courses = $this->getDoctrine()->getManager()->getRepository('CourseBundle:Course')->findAll();
      $profs = $this->getDoctrine()->getManager()->getRepository('UsersBundle:User')->findByrole('ROLE_ENSEIGNANT');
      $types = $this->getDoctrine()->getManager()->getRepository('CourseBundle:Type')->findAll();
      return $this->render('admin/Courseslist.html.twig', array(
        'courses' => $courses,
        'profs' => $profs,
        'types' => $types,
        )
      );
    }


    /**
    * @Route("/cours/new",name="newCourse")
    * @Method("POST")
    */
    public function newCourseAction(Request $request){
      $em = $this->getDoctrine()->getEntityManager();

      $cours = new Course();
      $cours->setTitle($request->get('title'));
      $cours->setProfId($request->get('prof'));
      $cours->setLieu($request->get('place'));
      $cours->setDateDebut($request->get('date').' '.$request->get('time'));
      $cours->setTarif($request->get('price'));
      $cours->setType($request->get('type'));
      $cours->setPlacesUtilisées(0);

      $em->persist($cours);
      $em->flush($cours);

      return $this->redirect('/admin/cours');
    }

        /**
    * @Route("/cours/del",name="delCourse")
    * @Method("POST")
    */
        public function delCourseAction(Request $request){
          $em = $this->getDoctrine()->getEntityManager();
          $params = $this->get('request')->request->all();
          $inputs = array();
          foreach ($params as $key => $value) {
            array_push($inputs, $key);
          }

          foreach ($inputs as $value) {
            $course = $em->getRepository('CourseBundle:Course')->find($value);

            $em->remove($course);
            $em->flush();
          }
          return $this->redirect('/admin/cours');
        }

    /**
    * @Route("/cours/edit",name="editCourse")
    * @Method("POST")
    */
    public function editCourseAction(Request $request){
      $em = $this->getDoctrine()->getEntityManager();

      $course = $em->getRepository('CourseBundle:Course')->find($request->get('id'));
      $type = $em->getRepository('CourseBundle:Type')->findAll();
      $profs = $em->getRepository('UsersBundle:User')->findByrole('ROLE_ENSEIGNANT');
      return $this->render('admin/courseEdit.html.twig', array(
        'course' => $course,
        'types' => $type,
        'profs' => $profs,
        ));

    }


    /**
    * @Route("/cours/edited",name="editedCourse")
    * @Method("POST")
    */
    public function editedCourseAction(Request $request){
      $em = $this->getDoctrine()->getEntityManager();

      $course = $em->getRepository('CourseBundle:Course')->find($request->get('id'));
      $course->setTitle($request->get('title'));
      $course->setProfId($request->get('duree'));
      $course->setLieu($request->get('lieu'));
      $course->setNbrPlace($request->get('place'));
      $course->setDateDebut($request->get('date').' '.$request->get('hour'));
      $course->setTarif($request->get('tarif'));
      $course->setProfId($request->get('prof'));
      $course->setType($request->get('type'));

      $em->persist($course);
      $em->flush();

      return $this->redirect('/admin/cours');
    }



    /**
    * @Route("/cours/visibility",name="setVisible")
    * @Method("POST")
    */
    public function setVisible(Request $request){
      $em = $this->getDoctrine()->getEntityManager();
      $params = $this->get('request')->request->all();
      $inputs = array();

      foreach ($params as $key => $value) {
        array_push($inputs, $key);
      }
      foreach ($inputs as $value) {
        $course = $em->getRepository('CourseBundle:Course')->find($value);
        $course->setVisibility(true);
        $em->persist($course);
        $em->flush();
      }

      return $this->redirect('/admin/cours');
    }

    /**
    * @Route("/cours/invisibility",name="setInvisible")
    * @Method("POST")
    */
    public function setInvisible(Request $request){
      $em = $this->getDoctrine()->getEntityManager();
      $params = $this->get('request')->request->all();
      $inputs = array();

      foreach ($params as $key => $value) {
        array_push($inputs, $key);
      }
      foreach ($inputs as $value) {
        $course = $em->getRepository('CourseBundle:Course')->find($value);
        $course->setVisibility(false);
        $em->persist($course);
        $em->flush();
      }
      return $this->redirect('/admin/cours');
    }

    /**
    * @Route("/cours/provide",name="provideCourse")
    * @Method("POST")
    */
    public function provideCourseAction(Request $request){
      $em = $this->getDoctrine()->getEntityManager();
      $count = $request->get('count');
      $course = $em->getRepository('CourseBundle:Course')->find($request->get('id'));

      for ($i=1; $i <= $count; $i++) { 
        $date = $this->getRightDate($course, $i);

        $cours = new Creneaux();
        $cours->setTitle($course->getTitle());
        $cours->setProfId($course->getProfId());
        $cours->setDateDebut($date.' '.substr($course->getDateDebut(), strlen($course->getDateDebut())-5));
        $cours->setLieu($course->getLieu());
        $cours->setTarif($course->getTarif());
        $cours->setPlaceUsUses($course->getPlacesUtilisées());
        $cours->setVisibility(true);
        $cours->setType($course->getType());
        $cours->setCourseId($course->getId());
        $cours->setNbrPlace($course->getNbrPlace());

        $em->persist($cours);
        $em->flush();
      }
      return $this->redirect('/admin/cours');

    }

    public function getRightDate($course, $i){
      $date = substr($course->getDateDebut(),0 ,strlen($course->getDateDebut())-6);
      $find = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
      $replace = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
      $date = str_replace($find, $replace, strtolower($date));
      $date = date('d m Y', strtotime($date . "+ ".$i." week"));
      $month = ucfirst($find[str_replace('0', '', substr($date, 3,2))-1]);
      $true = str_replace(substr($date, 3, 2), $month, $date);

      return $true;
    }


// ============================================= PRODUCTS ACTIONS =============================================



    /**
    * @Route("/article",name="listArticle")
    * @Method("GET")
    */
    public function listArticle(){
      $em = $this->getDoctrine()->getEntityManager();

      $types = $em->getRepository('ShopBundle:Type')->findAll();
      $articles = $em->getRepository('ShopBundle:Article')->findBy(array(),array("createdAt" => "DESC"));
      return $this->render('admin/shop/index.html.twig', array('types' => $types,'articles' => $articles));
    }


    /**
    * @Route("/article/add",name="addArticle")
    * @Method("GET")
    */
    public function addArticle(){
      $em = $this->getDoctrine()->getEntityManager();

      $types = $em->getRepository('ShopBundle:Type')->findAll();
      return $this->render('admin/shop/new.html.twig', array('types' => $types));
    }

    /**
    * @Route("/article/added",name="addedArticle")
    * @Method("POST")
    */
    public function addedArticle(Request $request){
      $em = $this->getDoctrine()->getEntityManager();

      $article = new Article();

      $article->setName($request->get('name'));
      $article->setPrix($request->get('prix'));
      $article->setTypeId($request->get('type'));
      $article->setDescription($request->get('desc'));
      $article->setCreatedAt(new \Datetime());

      $em->persist($article);
      $em->flush();

      $img = $request->files->get('img')->move(__DIR__.'/../../../web/img/shop/' , $article->getId().'.jpg');

      return $this->redirect('/admin');
    }

    /**
    * @Route("/article/type/added",name="newTypeArticle")
    * @Method("POST")
    */
    public function addedTypeArticle(Request $request){
      $em = $this->getDoctrine()->getEntityManager();

      $type = new Type();

      $type->setName($request->get('name'));


      $em->persist($type);
      $em->flush();

      return $this->redirect('/admin');
    }
    /**
    * @Route("/article/edit/{id}",name="editProduct")
    * @Method("GET")
    */
    public function editProduct($id){
      $em = $this->getDoctrine()->getEntityManager();

      $article = $em->getRepository('ShopBundle:Article')->find($id);
      $type = $em->getRepository('ShopBundle:Type')->find($article->getTypeId());
      $types = $em->getRepository('ShopBundle:Type')->findAll();

      return $this->render('admin/shop/edit.html.twig', array('article' => $article,'type' => $type, 'types' => $types));
    }

    /**
    * @Route("/article/edited/{id}",name="editedProduct")
    * @Method("POST")
    */
    public function editedProduct(Request $request, $id){
      $em = $this->getDoctrine()->getEntityManager();

      $article = $em->getRepository('ShopBundle:Article')->find($id);
      $article->setName($request->get('name'));
      $article->setDescription($request->get('desc'));
      $article->setPrix($request->get('prix'));
      $article->setTypeId($request->get('type'));

      $em->persist($article);
      $em->flush();

      return $this->redirect('/admin/article');
    }

	    /**
    * @Route("/article/delete",name="deleteProduct")
    * @Method("POST")
    */
      public function deleteProduct(Request $request){
        $em = $this->getDoctrine()->getEntityManager();
        $article = $em->getRepository('ShopBundle:Article')->find($request->get('id'));

        $em->remove($article);
        $em->flush();

        return $this->redirect('/admin/article');
      }





// ============================================= NEWSLETTER ACTIONS =============================================

    /**
    * @Route("/mails",name="mails")
    * @Method("GET")
    */
    public function mailIndexAction(){
      $em = $this->getDoctrine()->getManager();
      $repo = $em->getRepository('UsersBundle:Repository')->findBy(array('userId' => $this->getUser()->getId()));
      $mails = $em->getRepository('UsersBundle:Mails')->getMailsAdmin();


      return $this->render('admin/mails/index.html.twig', array('repo' => $repo, 'mails' => $mails));
    }

    /**
    * @Route("/mails/sended",name="getSendedMails")
    * @Method("GET")
    */
    public function getSendedmails(){
      $em = $this->getDoctrine()->getManager();
      $repo = $em->getRepository('UsersBundle:Repository')->findBy(array('userId' => $this->getUser()->getId()));
      $mails = $em->getRepository('UsersBundle:Mails')->getSendedMails();

      return $this->render('admin/mails/sended.html.twig', array('repo' => $repo, 'mails' => $mails));
    }

    /**
    * @Route("/mails/deleted",name="getDeletedMails")
    * @Method("GET")
    */
    public function getDeletedMails(){
      $em = $this->getDoctrine()->getManager();
      $repo = $em->getRepository('UsersBundle:Repository')->findBy(array('userId' => $this->getUser()->getId()));
      $mails = $em->getRepository('UsersBundle:Mails')->getDeletedMails();

      return $this->render('admin/mails/sended.html.twig', array('repo' => $repo, 'mails' => $mails));
    }

    /**
    * @Route("/mails/write",name="mailWriting")
    * @Method("GET")
    */
    public function writeAction(){
      $em = $this->getDoctrine()->getManager();
      $repo = $em->getRepository('UsersBundle:Repository')->findBy(array('userId' => $this->getUser()->getId()));

      return $this->render('admin/mails/write.html.twig', array('repo' => $repo));
    }


    /**
    * @Route("/mails/posted",name="mailPosted")
    * @Method("POST")
    */
    public function postAction(Request $request){
      $params = $this->get('request')->request->all();
      $recipientsRoles = array();
      $recipients = array();

      foreach ($params as $key => $value) {
        if (substr($key, 0, 8) == "checkbox") {
          array_push($recipientsRoles, $value);
        }
      }
      $emailsRoles = $this->getRecipientsbyRoles($recipientsRoles);


      $canonicals = str_replace(' ', '', $request->get('emails'));
      $annotates = explode(";", $canonicals);
      $result = array_merge($emailsRoles, $annotates);

      $this->sendMailsTo($result, $request);

      return $this->redirect('/admin/mails');

    }

    /**
    * @Route("/mails/create",name="mailRepoCreate")
    * @Method("POST")
    */
    public function createRepoAction(Request $request){
      $em = $this->getDoctrine()->getManager();

      $newRepo = new Repository();
      $newRepo->setName($request->get('reponame'));
      $newRepo->setUserId($this->getUser()->getId());
      $newRepo->setPersistent(false);

      $em->persist($newRepo);
      $em->flush();

      return $this->redirect('/admin/mails');
    }
    /**
    * @Route("/mails/{id}",name="showMail")
    * @Method("GET")
    */
    public function showMailAction($id){
      $em = $this->getDoctrine()->getManager();

      $mail = $em->getRepository('UsersBundle:Mails')->find($id);
      $repo = $em->getRepository('UsersBundle:Repository')->findBy(array('userId' => $this->getUser()->getId()));

      if (is_null($mail)) {
        return $this->redirect('/admin/mails');
      }

      return $this->render('admin/mails/show.html.twig', array('mail' => $mail, 'repo' => $repo));
    }




    public function getRecipientsbyRoles($roles){
      $em = $this->getDoctrine()->getManager();
      $recipients = array();

      foreach ($roles as $key => $value) {
        if ($value == "users") {
          $users = $em->getRepository('UsersBundle:User')->findByrole('ROLE_USER');
          foreach ($users as $key => $value) {  
            array_push($recipients, $value->getEmailCanonical());
          }
        }
        if ($value == "trainers") {
          $trainers = $em->getRepository('UsersBundle:User')->findByrole('ROLE_ENSEIGNANT');
          foreach ($trainers as $key => $value) {
            array_push($recipients, $value->getEmailCanonical());
          }
        }
        if ($value == "partners") {
        }
        if ($value == "admins") {
          $admins = $em->getRepository('UsersBundle:User')->findByrole('ROLE_ADMIN');
          foreach ($admins as $key => $value) {
            array_push($recipients, $value->getEmailCanonical());
          }
        }
      }
      return $recipients;
    }

    public function sendMailsTo($recipients, $request){
      $em = $this->getDoctrine()->getManager();

      foreach ($recipients as $key => $value) {
        $message = \Swift_Message::newInstance($request->get('title'))
        ->setFrom(array('service-client@fly-yoga.ovh' => 'Service Client Fly-Yoga'))
        ->setTo(array($value => 'Bonjour'))
        ->setBody($request->get('content'));
        $this->get('mailer')->send($message);
      }

      $mail = new Mails();

      $mail->setTitle($request->get('title'));
      $mail->setContent(nl2br($request->get("content")));
      $mail->setUsersMails($recipients);
      $mail->setAvailable(true);
      $mail->setOrigin('service-client@fly-yoga.ovh');

      $em->persist($mail);
      $em->flush();
    }

// ============================================= CALENDAR ACTIONS =============================================

  }