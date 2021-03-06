<?php

namespace PartnerBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PartnerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PartnerRepository extends EntityRepository
{
	public function searchPartner($string){
		$qb = $this->createQueryBuilder('p')
		->where('p.name LIKE :name')
		->setParameter('name', "%". $string ."%");

		return $qb->getQuery()->getResult();
	}
	public function getLastPartners(){
		$qb = $this->createQueryBuilder('p')
		->where('p.enabled = 1')
		->setParameter('name', "%". $string ."%");

		return $qb->getQuery()->getResult();
	}
}
