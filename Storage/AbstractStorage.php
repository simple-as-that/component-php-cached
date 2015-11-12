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
 * Description of AbstractStorage
 *
 * @author Markus Sommerfeld <markus@simpleasthat.de>
 */
class AbstractStorage {
    
    const ERROR_INVALID_OPTIONS = 'options must be an array';
    
    protected $cachetime = 3600;
    
    protected $prefix;
    
    /**
     * 
     * @param array $options The options to configure the used storage
     */
    public function __construct( $options = array() ) {
        
        if(!is_array($options)){
            $this->throwError(self::ERROR_INVALID_OPTIONS, 500, 'InvalidArgumentException');
        }
        
        foreach($options as $name => $value){
            if(property_exists($this, $name)){
                $this->{$name} = $value;
            }
        }
        
        $this->init();
    }
    
    /**
     * 
     * should be overwritten by the storage
     */
    public function init(){
    }
    
    /**
     * returns the cachetime
     * @return int
     */
    public function getCachetime() {
        return $this->cachetime;
    }

    /**
     * set the cachtime
     * @param int $cachetime
     * @return \SAT\Component\Cached\Storage\AbstractStorage
     */
    public function setCachetime($cachetime) {
        $this->cachetime = $cachetime;
        return $this;
    }
    
    /**
     * returns the defined cache prefix
     * @return string
     */
    public function getPrefix() {
        return $this->prefix;
    }

    /**
     * set the cache item prefix
     * @param string $prefix
     * @return \SAT\Component\Cached\Storage\AbstractStorage
     */
    public function setPrefix($prefix) {
        $this->prefix = $prefix;
        return $this;
    }

    

    /**
     * 
     * @param string $message
     * @param int $code
     * @param type $type
     * @throws \Exception
     */    
    protected function throwError($message, $code=500, $type='Exception' ){
        $exception = "\\".$type;
        throw new $exception($message, $code);
    }    
}
