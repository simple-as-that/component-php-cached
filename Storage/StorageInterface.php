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
 *
 * @author Markus Sommerfeld <markus@simpleasthat.de>
 */
interface StorageInterface {
  
    
    /**
     * called on store initialisation,
     * this method is used to prepare the storage, eg.:
     * - conntect to memcache server
     * - create a cache direcory
     */
    public function init();

    /**
     * Method to write the cache
     * 
     * @param string $id
     * @param mixed $content
     * @param int $timestamp Expiration time of the item. If it's equal to zero, the item will never expire
     */
    public function set($id, $content, $timestamp = false);
    
    /**
     * returns a cached item
     * @param string $id
     */
    public function get($id);
    
    /**
     * removes an cache item
     * @param string $id
     */
    public function delete($id);
    
    /**
     * removes all existing items at the server
     */
    public function flush();
}
