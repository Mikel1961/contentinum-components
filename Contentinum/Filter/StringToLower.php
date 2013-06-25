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
 * 
 * @author mike
 *
 */
class StringToLower implements FiltersInterface
{
	/**
	 * Parameter encoding
	 * @var unknown
	 */
	protected $parameters = array('encoding' => null);
	
	/**
	 * Construct and set base paramters
	 * @param string $encoding
	 */
	public function __construct($encoding = null)
	{
		if (null !== $encoding){
			$this->setEncoding($encoding);
		}
	}
	
	/**
	 * Set and validate encoding if avaibale 
	 * @param string $encoding
	 * @throws \Exception
	 * @return \Contentinum\Filter\StringToLower
	 */
	public function setEncoding($encoding = null)
	{
		if (null !== $encoding){
			
			if ( !function_exists('mb_strtolower')  ){
				throw new \Exception('To set a character encoding is required mbstring');
			}
			
			$encoding = strtolower($encoding);
			$endcodings = array_map('strtolower', mb_list_encodings());
			if ( ! in_array($encoding, $endcodings) ){
				throw new \Exception('The character set %s is not supported', $encoding);
			}
			
			$this->parameters['encoding'] = $encoding;
			return $this;
		}
	}
	
	/**
	 * @see \Contentinum\Filter\FiltersInterface::filter()
	 */
	public function filter($value)
	{
		if ( $this->parameters['encoding'] !== null ){
			return mb_strtolower((string) $value,$this->parameters['encoding']);
		} else {
			return strtolower((string) $value);
		}
	}
}