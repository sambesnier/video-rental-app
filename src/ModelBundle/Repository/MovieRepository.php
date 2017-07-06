<?php

namespace ModelBundle\Repository;

use ModelBundle\Entity\Movie;

/**
 * MovieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MovieRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllMoviesForArtistId(Movie $movie){

        $actorList = $this->getActorsIdFromMovie($movie);


        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->innerJoin('m.actors', 'a')
            ->where('m.id != :movieId')
            ->andWhere( 'a.id IN (:actorList)');

        $query = $qb->getQuery();

        $query->setParameter('actorList', $actorList, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY);
        $query->setParameter('movieId', $movie->getId());

        return $query->getResult();
    }

    /**
     * @param Movie $movie
     * @return array
     */
    private function getActorsIdFromMovie(Movie $movie)
    {
        $actorsList = array_map(
            function ($item) {
                return $item->getId();
            },
            $movie->getActors()->toArray()
        );

        return $actorsList;
    }

    public function getMoviesByDirector(Movie $movie){
        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->innerJoin('m.actors','a')
            ->where('m.id != :movieId')
            ->andWhere('m.director = :movieDirector');


        $query = $qb->getQuery()
            ->setParameter('movieId', $movie->getId())
            ->setParameter('movieDirector', $movie->getDirector());

        return $query->getResult();
    }
}
