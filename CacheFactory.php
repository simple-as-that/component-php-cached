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
    
    /**
     *
     * @var string
     */
    private $prefix;
    
    
    public function __construct( StorageInterface $storage ) {
        $this->cacheStorage = $storage;
    }

    public function init() {}
    
    public function set($id, $content, $timestamp = false) {
        return $this->cacheStorage->set($this->getId($id), $content, $timestamp);
    }
    
    public function get($id) {
        return $this->cacheStorage->get($this->getId($id));
    }

    public function delete($id) {
        return $this->cacheStorage->delete($this->getId($id));
    }

    public function flush() {
        #$this->cacheStorage->flush();
    }
    
    /**
     * 
     * @param StorageInterface $storage
     * @return \SAT\Component\Cached\CacheFactory
     */
    public function setCacheStorage( StorageInterface $storage ){
        $this->cacheStorage = $storage;
        return $this;
    }
    
    /**
     * 
     * @return StorageInterface
     */
    public function getCacheStorage(){
        return $this->cacheStorage;
    }
    
    public function getPrefix() {
        return $this->prefix;
    }

    public function setPrefix($prefix) {
        $this->prefix = $prefix;
        return $this;
    }
    
    /**
     * 
     * @param type $id
     */
    private function getId($id){
        return $this->prefix."_".$id;
    }


}
