<?php

namespace Janus\Component\ReadonlyEntities\Entities\Connection;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

use Janus\Component\ReadonlyEntities\Entities\Connection\Revision;

class RevisionRepository extends EntityRepository
{
    /**
     * Loads the latest revision of a Connection
     *
     * Note that this MUST be named 'findOneBy...' to satisfy the SensioLabs ParamConverter.
     *
     * @param int $connectionId
     * @return Revision|null
     */
    public function findOneByConnectionId($connectionId)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        return $queryBuilder
            ->select('CR')
            ->from('Janus\Component\ReadonlyEntities\Entity\Connection\Revision','CR')
            // Filter latest revision
            ->innerJoin(
                'CR.connection',
                'C',
                Expr\Join::WITH,
                'C.revisionNr = CR.revisionNr'
            )
            ->where('CR.connection = :connectionId')
            ->setParameter(':connectionId', $connectionId)
            ->getQuery()
            ->getSingleResult();
    }
}

