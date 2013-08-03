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
 * @package View
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 5.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace Contentinum\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Contentinum\Html\HtmlAttribute;

/**
 * Build and create button if a abrotation link available
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *
 */
class AbortationButton extends AbstractHelper
{

	/**
	 * Build and create button if a abrotation link available
	 * @param string $label button label
	 * @param string $decorator
	 * @return string
	 */
	public function __invoke($label, $decorator = null)
	{
		$html = '';
		if ($this->view->abortation){
			$html = $this->renderButton($label,$decorator);
		}
		return $html;

	}
	
	/**
	 * Build and create a button
	 * @param string $label button label
	 * @param array $decorator button attributtes and row tag
	 * @return string
	 */
	private function renderButton($label, $decorator = null)
	{
		$attributtes = '';
		$html = '';
		$endTag = '';
		if ( $decorator ){
			if (isset($decorator['attributes']) && is_array($decorator['attributes'])){
				$attributtes = HtmlAttribute::attributeArray($decorator['attributes']);
			}
			
			if (isset($decorator['tag'])){
				$html .= '<'. $decorator['tag'];
			}			
			
			if (isset($decorator['tag-attribs']) && is_array($decorator['tag-attribs'])){
				$html .= HtmlAttribute::attributeArray($decorator['tag-attribs']);
			}
			
			if (isset($decorator['tag'])){
				$html .= '>';
				$endTag = '</'. $decorator['tag'] . '>';
			}			
		}
		$html .= '<a' . $attributtes;
		$html .= ' href="' . $this->view->url($this->view->abortation) . '">';
		$html .= $label . '</a>' . $endTag;
		return $html;
	}
}