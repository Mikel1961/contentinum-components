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
namespace Contentinum\Html\Elements;

/**
 * Abstract class html tag element
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
abstract class AbstractElements
{

    /**
     * array of tag elements
     *
     * @var array
     */
    protected $_tagElements = array();

    /**
     * xhtml tag
     *
     * @var string
     */
    protected $_tag = null;

    /**
     * tag attributes
     *
     * @var string
     */
    protected $_attribute = null;

    /**
     * Content enclose from tag
     *
     * @var string
     */
    protected $_content = null;

    /**
     * Label for form fields
     *
     * @var string
     */
    protected $_formLabel = null;

    /**
     * Construct
     *
     * @param string $tag xhtml tag
     * @param string $attribute tag attributes
     * @param string $content content enclose from tag
     * @param string $formLabel label form fields
     */
    public function __construct ($tag = false, $attribute = false, $content = false, $formLabel = false)
    {
        $this->_tag = $tag;
        $this->_attribute = $attribute;
        $this->_content = $content;
        $this->_formLabel = $formLabel;
    }

    /**
     * Instance of Contentinum_Html_Elements_Tag
     *
     * @param Contentinum_Html_Elements_Tag $tagElement Contentinum_Html_Elements_Tag object
     */
    public function setTagElement (Contentinum_Html_Elements_Tag $tagElement)
    {
        $this->_tagElements[] = $tagElement;
    }

    /**
     * display xhtml tag with content
     */
    abstract public function display ();
}