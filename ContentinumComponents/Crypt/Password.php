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
 * @package Entity
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 4.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Crypt;

/**
 * Password cryption SHAH
 *
 * @category contentinum library
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum
 *            (http://www.jochum-mediaservices.de)
 * @license http://www.contentinum-library.de/licenses BSD License
 */
class Password implements CryptInterface
{
    const SALTCHARS = './0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
    /**
     * Salt
     * @var string
     */
    private $salt;
    
    
    
    /**
     * @return the $salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

	/**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

	/**
     * Encode text string
     * @param string $var
     * @return string
     */
    public function encode($var, $salt = null)
    {
        if (null === $salt){
            $salt = $this->saltstring(16);
        }
        $this->setSalt($salt);
        return crypt($var,'$6$'. $this->getSalt() .'$');
    }
    
    /**
     * Add salt and encode string
     * @param int $length lenght salt
     * @return string
     */
    public function saltstring($length = 10)
    {
        return substr(str_shuffle(self::SALTCHARS), 0, min($length, strlen(self::SALTCHARS))); 
    }
}