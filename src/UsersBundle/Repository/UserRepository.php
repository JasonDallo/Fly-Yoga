<?php

namespace UsersBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UsersRepo
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{

	public function findByrole($role){
		$qb = $this->createQueryBuilder('p')
		->where("p.roles LIKE :role")
		->setParameter('role', "%".$role."%");
		return $qb->getQuery()->getResult();
	}

		public function findByroleLimit($role, $limit){
		$qb = $this->createQueryBuilder('p')
		->where("p.roles LIKE :role")
		->setParameter('role', "%".$role."%")
		->setMaxResults($limit);
		return $qb->getQuery()->getResult();
	}
}
?>