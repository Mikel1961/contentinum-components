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
namespace Contentinum\Html\Element;

/**
 * Abstract class html element
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
abstract class AbstractElement
{

    /**
     * xhtml tag
     *
     * @var string
     */
    protected $_tag = null;

    /**
     * Instance of Contentinum_Html_Elements_Abstract
     *
     * @var Contentinum_Html_Elements_Abstract array
     *      Contentinum_Html_Elements_Abstract objects
     */
    protected $_elements = array();

    /**
     * tag attributes
     *
     * @var string
     */
    protected $_attribute = null;

    /**
     * display output as string or array
     *
     * @var string
     */
    protected $_output = null;

    /**
     * Instance of Contentinum_Html_Elements_Abstract
     *
     * @param Contentinum_Html_Elements_Abstract $element Contentinum_Html_Elements_Abstract objects
     */
    public function setElement (Contentinum_Html_Elements_Abstract $element)
    {
        $this->_elements[] = $element;
    }

    /**
     * set a possible tag attributte
     *
     * @param string $attribute tag attributte
     */
    public function setAttribute ($attribute)
    {
        $this->_attribute = $attribute;
    }

    /**
     * set a div
     *
     * @param string $attribute
     */
    public function setTag ($tag)
    {
        $this->_tag = $tag;
    }

    /**
     * set kind of output array or string (html)
     *
     * @param string $value
     */
    public function setOutput ($value)
    {
        $this->_output = $value;
    }

    /**
     * display xhtml tag with content
     */
    abstract public function display ();
}