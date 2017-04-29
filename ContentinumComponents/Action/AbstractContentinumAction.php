<?php
namespace ContentinumComponents\Action;

abstract class AbstractContentinumAction
{

    /**
     * Worker
     * 
     * @var ContentinumComponents\Mapper\Worker $worker
     */
    protected $worker;

    /**
     * AbstractEntity
     * 
     * @var ContentinumComponents\Entity\AbstractEntity $entity
     */
    protected $entity;

    /**
     *
     * @return \ContentinumComponents\Mapper\Worker $worker
     */
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     *
     * @param \ContentinumComponents\Mapper\Worker $worker            
     */
    public function setWorker($worker)
    {
        $this->worker = $worker;
    }

    /**
     *
     * @return \ContentinumComponents\Entity\AbstractEntity $entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     *
     * @param \ContentinumComponents\Entity\AbstractEntity $entity            
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }
}