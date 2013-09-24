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
namespace ContentinumComponents\Html;

use ContentinumComponents\Html\Element\FactoryElement;
use ContentinumComponents\Html\HtmlAttribute;
/**
 * create htmls elements for view html content
 * set attribute
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
class HtmlElements
{

    /**
     * FactoryElement
     *
     * @var FactoryElement
     */
    protected $_htmlFactory = null;

    /**
     * Enclose tag for this elements
     *
     * @var string
     */
    protected $_encloseTag = null;

    /**
     * Display output (string or array)
     *
     * @var string
     */
    protected $_output = null;

    /**
     * Content tag(s)
     *
     * @var array
     */
    protected $_contentTag = array();

    /**
     * Content enclose tag attributes
     *
     * @var array
     */
    protected $_attributes = array();

    /**
     * tag attributes
     *
     * @var array
     */
    protected $_tagAttributes = array();

    /**
     * Tag content
     *
     * @var array
     */
    protected $_htmlContent = array();

    /**
     * Construct
     *
     * @param object $htmlFactory
     */
    public function __construct ($htmlFactory)
    {
        $this->_htmlFactory = $htmlFactory;
    }

    /**
     * set kind of output
     * array or string
     * standard is string
     *
     * @param string $value
     */
    public function setOutput ($value)
    {
        $this->_output = $value;
    }

    /**
     * Set enclose tag
     *
     * @param string $tag
     */
    public function setEncloseTag ($tag)
    {
        $this->_encloseTag = $tag;
    }

    /**
     * Set content tag
     *
     * @param string $tag
     */
    public function setContentTag ($tag)
    {
        $this->_contentTag[] = $tag;
    }

    /**
     * Set enclose tag attributes
     *
     * @param string $tag attribute
     * @param string $value attribute value
     */
    public function setAttributes ($name, $value)
    {
        $this->_attributes[$name] = $value;
    }

    /**
     * Set tag attributes
     *
     * @param int $key loop counter num
     * @param string $tag attribute
     * @param string $value attribute value
     */
    public function setTagAttributtes ($name, $value, $key)
    {
        $this->_tagAttributes[$key][$name] = $value;
    }

    /**
     * Content
     *
     * @param string $content
     */
    public function setHtmlContent ($content)
    {
        $this->_htmlContent[] = $content;
    }

    /**
     * Display
     *
     * @return string
     */
    public function display ()
    {
        $content = $this->_htmlFactory->createContentElements();
        // check if set the output, if not array output will be a string
        if ($this->_output) {
            $content->setOutput($this->_output);
        }
        if ($this->_encloseTag) {
            $content->setTag($this->_encloseTag);
        }
        if (! empty($this->_attributes)) {
            $attribute = '';
            foreach ($this->_attributes as $k => $v) {
                $attribute = $attribute . ' ' . HtmlAttribute::attributeString($k, $v);
            }
            $content->setAttribute($attribute);
        }
        $element = $this->_htmlFactory->createElement();
        $content->setElement($element);
        $i = 0;
        foreach ($this->_htmlContent as $values) {
            $attribute = '';
            if (isset($this->_tagAttributes[$i]) && ! empty($this->_tagAttributes[$i])) {
                foreach ($this->_tagAttributes[$i] as $k => $v) {
                    $attribute = $attribute . ' ' . HtmlAttribute::attributeString($k, $v);
                }
            }
            $buildElement = $this->_htmlFactory->createContentElement($values, $attribute, $this->_contentTag[$i]);
            $element->setTagElement($buildElement);
            $i ++;
        }
        $this->_tagAttributes = false;
        $this->_htmlContent = false;
        return $content->display();
    }
}