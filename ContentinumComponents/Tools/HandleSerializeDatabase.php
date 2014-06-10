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
 * @package Tools
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 4.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Tools;

use ContentinumComponents\Entity\Exeption\InvalidValueToolsException;

/**
 * Prepare serrialize datas before save in database or get from them
 * 
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
class HandleSerializeDatabase
{

    const METHOD_BASE64ENCODE = 'base64_encode';

    /**
     * Stored allowed methods to prepare serialize
     * data before save in database
     * 
     * @var array
     */
    protected $allowedMethods = array(
        'base64_encode' => self::METHOD_BASE64ENCODE
    );

    /**
     * Method used
     * 
     * @var string
     */
    protected $method;

    /**
     * Construct
     * 
     * @param string $method
     */
    public function __construct($method = null)
    {
        if (null !== $method && array_key_exists($method, $this->allowedMethods)) {
            $this->setMethod($method);
        }
    }

    /**
     * Get the current active method
     * 
     * @return the $method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set method to prepare serlize data
     * 
     * @param string $method
     * @throws InvalidValueToolsException
     */
    public function setMethod($method)
    {
        if (! array_key_exists($method, $this->allowedMethods)) {
            throw new InvalidValueToolsException('Unknown method to prepare serialize data befor save in database');
        }
        $this->method = $method;
    }

    /**
     * Serialize data
     * 
     * @param array $datas
     * @param string $method
     * @throws InvalidValueToolsException
     * @return string prepared data for storage in database
     */
    public function execSerialize(array $datas, $method = null)
    {
        if (null !== $method) {
            $this->setMethod($method);
        }
        if (null == $this->method) {
            throw new InvalidValueToolsException('No method defined to prepare serialize data');
        }
        $excute = $this->getMethod();
        return $excute(serialize($datas));
    }

    /**
     * Unserialize data
     * 
     * @param string $value
     * @param string $method
     * @throws InvalidValueToolsException
     * @return array data from database
     */
    public function execUnserialize($value, $method = null)
    {
        if (null !== $method) {
            $this->setMethod($method);
        }
        if (null == $this->method) {
            throw new InvalidValueToolsException('No method defined to prepare serialize data');
        }
        $excute = $this->getMethod();
        return unserialize($excute($datas));
    }
}