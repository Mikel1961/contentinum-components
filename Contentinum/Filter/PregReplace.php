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
 * @package Filter
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 4.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace Contentinum\Filter;

/**
 * Wrapper class for preg_replace
 * 
 * @todo implement a match pattern validation
 * @author mike
 *
 */
class PregReplace implements FiltersInterface
{
	/**
	 * Replace with this
	 * @var string
	 */
	protected $_replace;
	
	/**
	 * Match pattern
	 * @var string
	 */
	protected $_match;
	
	/**
	 * Construct
	 * Provide options with keys match => preg replace pattern, replace => replace with this
	 * 
	 * @param array $options 
	 * @throws \Exception
	 */
	public function __construct(array $options)
	{
		if (isset($options['replace'])){
			$this->_replace = $options['replace'];
		}
		
		if ( isset($options['match']) ){
			$this->_match = $options['match'];
		}
		
		if (null != $this->_match || null != $this->_replace){
			throw new \Exception('You need to set a valid match patter and a replacement in %s', get_class($this));
		}
	}
	/**
	 * @see \Contentinum\Filter\FiltersInterface::filter()
	 */
	public function filter($value)
	{
		return preg_replace($this->_match, $this->_replace, $value);
	}
}