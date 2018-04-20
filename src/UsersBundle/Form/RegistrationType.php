<?php

namespace UsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class RegistrationType extends AbstractType{

	public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options){
		$builder->add('firstname')->add('lastname');
	}

	public function getParent(){
		return 'fos_user_registration';
	}

	public function getName(){
		return 'fly_user_registration';
	}
}