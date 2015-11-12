<?php

/*
 * The MIT License
 *
 * Copyright 2015 Markus Sommerfeld <markus@simpleasthat.de>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace SAT\Component\Cached\Storage;

/**
 * Description of MemcacheStorage
 *
 * @author Markus Sommerfeld <markus@simpleasthat.de>
 */
class MemcacheStorage extends AbstractStorage implements StorageInterface{

    const ERROR_EXTENSION_NOT_FOUND = 'Memcached extention not found';
    const ERROR_COULD_NOT_CONNECT   = 'Could not connect to memcached server';
    const ERROR_INVALID_HOST        = 'Memcached Host not valid';
    const ERROR_PORT_MUST_INT       = 'Memcached Port should be an integer';
    
    
    /**
     * The memcache host 
     * @var string 
     */
    protected $host = "localhost";

    /**
     * the port to connect to
     * @var int 
     */
    protected $port   = 11211;
    
    /**
     * holds the memcached instance
     * @var \Memcached 
     */
    private $memcached;
 
    /**
     * Init the Memcached instance
     */
    public function init() {
        
        if(!class_exists('Memcached')){
            $this->throwError(self::ERROR_EXTENSION_NOT_FOUND, 500);
        }
        
        // port & host are set
        if(empty($this->host) || !is_string($this->host)){
            $this->throwError(self::ERROR_INVALID_HOST, null, 'InvalidArgumentException');
        }
        
        if(!is_integer($this->port)){
            $this->throwError(self::ERROR_PORT_MUST_INT, null, 'InvalidArgumentException');
        }
        
        $this->memcached = new \Memcached();
        $this->memcached->addServer($this->host, $this->port);
        $status = $this->memcached->getStats();
        if( !isset($status[$this->host.":".$this->port]['version']) || empty($status[$this->host.":".$this->port]['version']) ){
            $this->throwError(self::ERROR_COULD_NOT_CONNECT);
        }
    }

    /**
     * 
     * @param mixed $id
     * @return boolean
     */
    public function get($id) {
        return $this->memcached->get($id);
    }

    /**
     * 
     * @param mixed $id
     * @param mixed $content
     * @param integer $timestamp
     * @return boolean
     */
    public function set($id, $content, $timestamp = false) {
        // no cachetime set
        if($timestamp === false){
            $timestamp = time()+$this->getCachetime();
        }
        // cachetime is not 0
        elseif($timestamp!==0){
            $timestamp = time()+$timestamp;
        }
        return $this->memcached->set($id, $content, $timestamp);
    }
    
    /**
     * 
     * @param mixed $id
     * @return boolean
     */
    public function delete($id) {
        return $this->memcached->delete($id);
    }

    /**
     * Invalidate all items in the cache
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function flush() {
        return $this->memcached->flush();
    }
    
    /**
     * returns the Memcache Host
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * Returns the Memcache Port
     * @return integer
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * Returns the memcached instance
     * @return \Memcached
     */
    public function getMemcached() {
        return $this->memcached;
    }

    /**
     * 
     * @param string $host
     * @return \SAT\Component\Cached\Storage\MemcacheStorage
     */
    public function setHost($host) {
        $this->host = $host;
        return $this;
    }

    /**
     * 
     * @param integer $port
     * @return \SAT\Component\Cached\Storage\MemcacheStorage
     */
    public function setPort($port) {
        $this->port = $port;
        return $this;
    }

    /**
     * 
     * @param \Memcached $memcached
     * @return \SAT\Component\Cached\Storage\MemcacheStorage
     */
    public function setMemcached(\Memcached $memcached) {
        $this->memcached = $memcached;
        return $this;
    }
}