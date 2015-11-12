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

use SAT\Component\Cached\Storage\MemcacheStorage;

/**
 * Description of MemcacheStorageTest
 *
 * @author Markus Sommerfeld <markus@simpleasthat.de>
 */
class MemcacheStorageTest extends PHPUnit_Framework_TestCase{
    
    
    
   
    public function testInit(){
        
        // is instance of StorageInterface
        $this->assertInstanceOf('SAT\Component\Cached\Storage\StorageInterface', new MemcacheStorage());
    }
    
    /**
     * @expectedException Exception
     * @expectedExceptionCode 500
     * @expectedExceptionMessageRegExp #Could not connect.*#
     */ 
    public function testInitConnection(){
        // string as options
        new MemcacheStorage(array(
            'host' => 'domain.dnl'
        ));
    }      

    /**
     * @expectedException InvalidArgumentException
     */ 
    public function testInitNoStringAsOption(){
        // string as options
        new MemcacheStorage('123', true);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */     
    public function testInitNoObjectAsOption(){
        
        $opt = new \stdClass();
        $opt->host = "host";
        // string as options
        new MemcacheStorage($opt);
    }   
    
    
    public function testInitArrayAsOption(){
        $option = array(
            'host'  => '127.0.0.1',
            #'port'  => 123,
        );        
        // string as options
        $i = new MemcacheStorage($option);
        
        $this->assertEquals('127.0.0.1', $i->getHost());
    }     
    
    
    public function testInitOnlySupportedOptions(){
        // test only property setter
        $notsupportedOption = array(
            'host'  => 'localhost',
            'port'  => 11211,
            'notsupported' => 'demo',
            'test2' => 123
        );
        $instance = new MemcacheStorage($notsupportedOption);
        
        // has attributes 
        $this->assertObjectHasAttribute('host', $instance);
        $this->assertObjectHasAttribute('port', $instance);
        
        $this->assertEquals('localhost', $instance->getHost());
        $this->assertEquals('11211', $instance->getPort());
        
        $this->assertObjectNotHasAttribute('notsupported', $instance);
        $this->assertObjectNotHasAttribute('test2', $instance);
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Memcached Host not valid
     */
    public function testInitValidHost(){
        // test only property setter
        $option = array(
            'host'  => 123,
        );
        new MemcacheStorage($option);
    }  
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Memcached Port should be an integer
     */
    public function testInitValidPort(){
        // test only property setter
        $option = array(
            'port'  => '123',
        );
        new MemcacheStorage($option);     
    }   


    public function testMemcacheSetAndGet(){
        $options = array('host' => '127.0.0.1', 'port' => 11211);
        $i = new MemcacheStorage($options);
        
        $i->set('TEST', 'THE TEST CONTENT');
        $content = $i->get('TEST');
        $this->assertEquals('THE TEST CONTENT', $content);
        
    }
    
    public function testMemcacheDelete(){
        $options = array('host' => '127.0.0.1', 'port' => 11211);
        $i = new MemcacheStorage($options);
        $this->assertEquals('THE TEST CONTENT', $i->get('TEST'));
        $i->delete('TEST');
        $this->assertFalse($i->get('TEST'));
    }    
    
    
    public function testMemcacheFlush(){
        $options = array('host' => '127.0.0.1', 'port' => 11211);
        $i = new MemcacheStorage($options);
        $i->set(1, 'Hallo Test');
        
        $stats = $i->getMemcached()->getStats();
        $this->assertArrayHasKey('127.0.0.1:11211', $stats);
        $this->assertTrue($i->flush(), 'Flush should return true');
        $this->assertFalse($i->get(1), 'Entry should not exists after flush');
    }
    
    public function testCacheTime(){
        
        $options = array('host' => '127.0.0.1', 'port' => 11211);
        $i = new MemcacheStorage($options);
        $i->flush();
        // should be valid for 2 seconds
        $i->set('VALID-2-sec', 'Hallo Test', 2);
        // do nothing for 3 seconds
        sleep(3);
        
        $this->assertFalse($i->get('VALID-2-sec'));
    }
}
