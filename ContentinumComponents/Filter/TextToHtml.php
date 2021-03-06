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
 * @since contentinum version 2.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Filter;

use Zend\Filter\FilterInterface;

/**
 * Parse text string into html
 * Remove return lines in <br /> set the text string in pargraphs
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *
 */
class TextToHtml implements FilterInterface
{
	
	/**
	 * PCRE is compiled with UTF-8 and Unicode support
	 *
	 * @var mixed
	 *
	 */
	protected static $_unicodeEnabled;
	
	/**
	 * Construct and set default parameters
	 *
	 * @return void
	 */
	public function __construct() 
	{
		if (null === self::$_unicodeEnabled) {
			self::$_unicodeEnabled = (@preg_match ( '/\pL/u', 'a' )) ? true : false;
		}
	}

    /**
     * (non-PHPdoc)
     * @see \Zend\Filter\FilterInterface::filter()
     */
	public function filter($value, $tag = 'p') 
	{
		$grafs = explode("\n\n", $value );
		for ($i = 0, $j = count($grafs); $i < $j; $i ++) {
			$grafs[$i] = preg_replace('/((ht|f)tp:\/\/[^\s&]+)/', '<a href="$1">$1</a>', $grafs[$i]);
			$grafs[$i] = preg_replace('/[^@\s]+@([-a-z0-9]+\.)+[a-z]{2,}/i', '<a href="mailto:$1">$1</a>', $grafs[$i]);
			$grafs[$i] = preg_replace('/€/', '&euro;', $grafs[$i]);
			$grafs[$i] = preg_replace('/\r/', '<br />', $grafs[$i]);
			$grafs[$i] = '<'.$tag.'>' . $grafs[$i] . '</'.$tag.'>';
		}
		return join("\n\n", $grafs);		
	}
}