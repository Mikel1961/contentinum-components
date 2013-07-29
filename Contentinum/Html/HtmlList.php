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
 * @package Html
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 1.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace Contentinum\Html;

use Contentinum\Html\Lists\FactoryList;
/**
 * create and display a html unorderd list
 * set attribute
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
class HtmlList extends HtmlElements
{

    /**
     * Constructor: initialize FactoryList
     *
     * @param FactoryList $contentFactory
     * @return void
     */
    public function __construct ( FactoryList  $listFactory)
    {
        parent::__construct($listFactory);
    }

    /**
     * Set list tag
     *
     * @param string $tag
     */
    public function setListTag ($tag)
    {
        $this->_encloseTag = $tag;
    }

    /**
     * Display
     *
     * @return string
     */
    public function display ()
    {
        $list = $this->_htmlFactory->createList();
        if ($this->_encloseTag) {
            $list->setList($this->_encloseTag);
        }
        if (! empty($this->_attributes)) {
            $attribute = '';
            foreach ($this->_attributes as $k => $v) {
                $attribute = $attribute . ' ' . HtmlAttribute::attributeString($k, $v);
            }
            $list->setAttribute(' ' . $attribute);
        }
        $element = $this->_htmlFactory->createElement();
        $list->setElement($element);
        $i = 0;
        foreach ($this->_htmlContent as $values) {
            $attribute = '';
            if (isset($this->_tagAttributes[$i]) && ! empty($this->_tagAttributes[$i])) {
                foreach ($this->_tagAttributes[$i] as $k => $v) {
                    $attribute = $attribute . ' ' . HtmlAttribute::attributeString($k, $v);
                }
            }
            $buildElement = $this->_htmlFactory->createListElement($values, $attribute, $this->_contentTag[$i]);
            $element->setTagElement($buildElement);
            $i ++;
        }
        $this->_tagAttributes = false;
        $this->_htmlContent = false;
        return $list->display();
    }
}