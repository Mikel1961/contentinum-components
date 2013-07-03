<?php
/**
 * contentinum - accessibility websites
 *
 * LICENSE
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category contentinum components
 * @package Storage
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 4.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace Contentinum\Storage;

/**
 * Abstract class storage manager(s)
 * 
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
abstract class AbstractManager
{

    /**
     * Storage manager object
     *
     * @var object
     */
    protected $_storage;

    /**
     * Entity class name
     *
     * @var string
     */
    protected $_entityName;

    /**
     *
     * @var object
     */
    protected $_entity;

    /**
     * Abtstract function to set entity name
     *
     * @param string $name
     *            entity name
     */
    abstract public function setEntityName($name);

    /**
     * Abtstract function to return $_entityName
     */
    abstract public function getEntityName();

    /**
     * Abtstract function to set entity name
     *
     * @param object $entity
     */
    abstract public function setEntity($entity);

    /**
     * Abtstract function to return $_entity
     */
    abstract public function getEntity();

    /**
     * Abstract function to set a storage object
     *
     * @param object $storage            
     * @param string $charset            
     */
    abstract public function setStorage($storage, $charset = 'UTF8');

    /**
     * Abstract function to get a storage object
     *
     * @param object $storage            
     * @param string $charset            
     */
    abstract public function getStorage($storage = null, $charset = 'UTF8');
}