<?php

namespace CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use CourseBundle\Entity\Type;

class TypeController extends Controller
{
    public function indexAction()
    {
    }
    /**
     * @Route("/admin/type/new",name="newType")
     * @Method("POST")
     */
    public function newTypeAction(Request $request){
        $em = $this->getDoctrine()->getEntityManager();
        $params = $this->get('request')->request->all();
        
        $type = new Type();
        $type->setName($request->get('name'));
        $type->setColor($request->get('color'));
        $type->setDescription($request->get('desc'));
        $type->setImgPath('N\A');

        $em->persist($type);
        $em->flush();
        return $this->redirect('/admin/cours');
    }
}