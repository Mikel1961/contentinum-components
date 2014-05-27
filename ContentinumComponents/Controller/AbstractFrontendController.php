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
 * @package Controller
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 5.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Controller;

use ContentinumComponents\Controller\AbstractContentinumController;

/**
 * Contentinum Frontend Abstract Controller
 * 
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *        
 */
abstract class AbstractFrontendController extends AbstractContentinumController 
{
    /**
     * Hostname
     * @var string
     */
    protected $host;
    
    /**
     * Get global website preferences
     * Can be overridden by individual sites
     * @return array
     */
    public function getPreferences()
    {
        $preferences = $this->getServiceLocator()->get('Contentinum\Preference');
    	$spezified['_default'] = $preferences['_default'];
    	$this->setHost($this->getRequest()->getUri()->getHost());
    	if (isset($preferences[$this->host])){
    		$spezified[$this->host] = $preferences[$this->host];
    	}
    	return $spezified;
    }
    
    /**
     * Get html structur xml file
     * @param string $key
     * @return Zend\Config
     */
    public function getHtmllayouts($key = null)
    {
        if (null == $key){
            $key = 'Contentinum\Htmllayouts';
        }
        return $this->getServiceLocator()->get($key);
    }
    
    /**
     * Get html widgets xml file
     * @param string $key
     * @return Zend\Config
     */
    public function getHtmlwidgets($key = null)
    {
    	if (null == $key){
    		$key = 'Contentinum\Htmlwidgets';
    	}
    	return $this->getServiceLocator()->get($key);
    }    
    
	/**
	 * @return the $host
	 */
	public function getHost() 
	{
		return $this->host;
	}

	/**
	 * @param string $host
	 */
	public function setHost($host) 
	{
		$this->host = $host;
	}

}