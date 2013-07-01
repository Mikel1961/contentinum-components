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
namespace Contentinum\Filter\Url;

use Zend\Filter\FilterChain;
use Contentinum\Filter\ReplaceUmlaute;
use Zend\Filter\StringToLower;
use Zend\Filter\PregReplace;
use Contentinum\Filter\CleanScope;
use Contentinum\Filter\RemoveMultipleCharacters;


/**
 * Preparing a string to use in a url 
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
class Prepare
{
	public static function filter($value, $replace = '-')
	{
		if (! is_string($value) ){
			return $value;
		}
		$filter = new FilterChain();
		$filter->attach(new ReplaceUmlaute());
		$filter->attach(new StringToLower());
		$filter->attach(new PregReplace(array('pattern' => "/\\s+/", 'replacement' => $replace)));
        $filter->attach(new CleanScope());		
        $filter->attach(new RemoveMultipleCharacters());
		$value = $filter->filter($value);
		unset($filter);
		return $value;
	}
}