<?php
namespace App\Common\Traits\Database;

/**
 * Trait DatabaseTrait
 * @package App\Common\Traits\Database
 *
 * usage :
 * -------
 * use DatabaseTrait;
 * $this->save($entity);
 */
trait DatabaseTrait
{
    /**
     * @param $entity
     */
    private function save($entity)
    {
        # insert into database
        $eManager = $this->getDoctrine()->getManager();
        $eManager->persist($entity);
        $eManager->flush();
    }
}

