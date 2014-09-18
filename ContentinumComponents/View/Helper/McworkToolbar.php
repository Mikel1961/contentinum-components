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
namespace ContentinumComponents\View\Helper;

use Zend\View\Helper\AbstractHelper;
use ContentinumComponents\Html\HtmlAttribute;
use ContentinumComponents\Tools\ArrayMergeRecursiveDistinct;

/**
 * Toolbar
 * Created a link list with buttons or with a tag
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
class McworkToolbar extends AbstractHelper
{
    protected $attribute = array();
    protected $standards = array();
    protected $tag = 'a';

    /**
     * Created a list depending on the given array
     * 
     * @param array $links condition for the list            
     * @return string list in html
     */
    public function __invoke(array $links, $std = false, array $list = null)
    {
        $this->setStandards($list);
        $html = '<ul';
        $html .= HtmlAttribute::attributeArray($this->attribute);
        $html .= '>';
        foreach ($links as $key => $link) {
            $standard = array();
            if (is_array($link) && true === $std && ($standard = $this->getStandards($key))){
                $link = ArrayMergeRecursiveDistinct::merge($standard, $link);
            } else {
                $link = $this->getStandards($key);
            }
            $html .= '<li><'. $this->tag;
            if ('a' == $this->tag && false !==  $link['href']){
            	$html .= ' href="' . $link['href'] . '"';
            }
            if (isset($link['attribs']) && is_array($link['attribs'])) {
                $html .= HtmlAttribute::attributeArray($link['attribs']);
            }
            $html .= '>' . $this->view->translate( $link['label'] ) . '</'.$this->tag.'>';
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
    
    /**
     * Set standards
     * @param array $standards
     */
    protected function setStandards($standards)
    {
        if ( isset($standards['attribute']) ){
            $this->attribute = $standards['attribute'];
        }
        
        if ( isset($standards['standards']) ){
            $this->standards = $standards['standards'];
        }

        if (isset($standards['tag'])){
            $this->tag = $standards['tag'];
        }
    }
    
    /**
     * Get standard toolbar content
     * @param string $key
     * @return multitype:multitype:string multitype:string
     */
    protected function getStandards($key)
    {
        if ( isset($this->standards[$key]) ){
            return $this->standards[$key];
        }
        return false;
    }
}