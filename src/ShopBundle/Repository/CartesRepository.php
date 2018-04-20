<?php

namespace ShopBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CartesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CartesRepository extends EntityRepository
{

	public function CartesbyDispo($user_id){
		$qb = $this->createQueryBuilder('c')
		->where('c.userId = :userid')
		->andWhere('c.dispo = :dispo')
		->setParameter('userid', $user_id)
		->setParameter('dispo', 1)
		->orderBy('c.date', 'ASC');

		return $qb->getQuery()->getResult();
	}
}
