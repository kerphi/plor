<?php
// vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 fdm=marker encoding=utf8 :
/**
 * REST_Server
 *
 * Copyright (c) 2010, Nicolas Thouvenin
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the author nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE REGENTS AND CONTRIBUTORS ``AS IS'' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE REGENTS AND CONTRIBUTORS BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  REST
 * @package   REST_Client
 * @author    Nicolas Thouvenin <nthouvenin@gmail.com>
 * @copyright 2010 Nicolas Thouvenin
 * @license   http://opensource.org/licenses/bsd-license.php BSD Licence
 */

/**
 * A REST Parameter
 *
 * @category  REST
 * @package   REST_Server
 * @author    Nicolas Thouvenin <nthouvenin@gmail.com>
 * @copyright 2010 Nicolas Thouvenin
 * @license   http://opensource.org/licenses/bsd-license.php BSD Licence
 */
class REST_Parameters
{
    protected $sections;
    protected $parameters;
    /**
     * Constructor
     */
    function __construct(array $sections, array $parameters)
    {
        $this->sections = $sections;

        foreach($parameters as $p) {
            if (is_array($p)) {
                foreach($p as $q)
                    if (is_string($q)) 
                        $this->parameters[$q] = $p;
            }
            else {
                $this->parameters[$p] = array($p);
            }
        }
        $this->parameters['server_uri'] = array('server_uri');
        $this->parameters['server_path'] = array('server_path');
    }

    /**
     * getSection
     * @param Section 
     */
    public function section($i = null)
    {
        if (is_null($i)) $i = count($this->sections) - 1;
        settype($i, 'integer');
        if ($i < 0) $i = 0;

        return isset($this->sections[$i]) ?  $this->sections[$i] : null;
    }

    /**
     * __get
     * 
     * Magic function
     *
     * @param string
     */
    public function __get($name) 
    {
        if (isset($this->parameters[$name]))
            foreach($this->parameters[$name] as $p) 
                if (is_string($p) and isset($_REQUEST[$p])) return $_REQUEST[$p];
        return null;
    }

     /**
      * __set
      *
      * Magic function
      *
     * @param string
     * @param mixed
     */
    public function __set($name, $value) 
    {
        if (isset($this->parameters[$name])) {
            $_REQUEST[$this->parameters[$name][0]] = $value;
        }
        else {
            $this->parameters[$name] = array($name);
             $_REQUEST[$name] = $value;
        }
    }

     /**
      * __isset
      *
      * Magic function
      *
     * @param string
     * @return boolean
     */
    public function __isset($name) 
    {
        return isset($this->parameters[$name]);
    }

    /**
     * __isset
     *
     * Magic function
     *
     * @param string
     */
    public function __unset($name) 
    {
        unset($this->parameters[$name]);
    }

     /**
     * __call
     *
     * Magic function
     *
     * @param string
     */
     public function __call($name, $arguments) 
     {
         if (isset($this->parameters[$name]))
             foreach($this->parameters[$name] as $p) 
                 if (is_string($p) 
                     and isset($_REQUEST[$p]) 
                     and !is_null($_REQUEST[$p])
                     and $_REQUEST[$p] !== '') return $_REQUEST[$p];
         return current($arguments);
     }
}
