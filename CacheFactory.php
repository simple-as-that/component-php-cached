<?php

namespace SAT\Component\Cached;

use SAT\Component\Cached\Storage\StorageInterface;

/**
 * Description of CacheFactory
 *
 * @author markus
 */
class CacheFactory implements StorageInterface{
    
    /**
     *
     * @var StorageInterface 
     */
    private $cacheStorage;
    
    
    public function __construct( StorageInterface $storage ) {
        $this->cacheStorage = $storage;
    }

    public function init() {}
    
    public function set($id, $content, $timestamp = false) {
        return $this->cacheStorage->set($id, $content, $timestamp);
    }
    
    public function get($id) {
        return $this->cacheStorage->get($id);
    }

    public function delete($id) {
        return $this->cacheStorage->delete($id);
    }

    public function flush() {
        $this->cacheStorage->flush();
    }
}
