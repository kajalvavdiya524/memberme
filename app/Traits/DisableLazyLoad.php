<?php


namespace App\Traits;


use BadMethodCallException;

trait DisableLazyLoad
{
    /**
     * This method overriding of Model class will not allow Eloquent to load Auto Lazy Loading of the models. - in process.
     * @param $method
     */
    public function getRelationshipFromMethod($method)
    {
        throw new BadMethodCallException('Relation lazy load has been disabled for performance reasons.');
    }
}