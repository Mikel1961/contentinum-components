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
namespace ContentinumComponents\Html\Table;

use ContentinumComponents\Html\Elements\AbstractElements;

/**
 * Abstract class html table
 *
 * @author Michael Jochum <michael.jochum@jochum-mediaservices.de>
 */
abstract class AbstractTable
{

    /**
     * table elements, rows
     *
     * @var array
     */
    protected $_elements = array();

    /**
     * tag attribute
     *
     * @var string
     */
    protected $_attribute = null;

    /**
     * table caption
     *
     * @var string
     */
    protected $_caption = null;

    /**
     * table header
     *
     * @var string
     */
    protected $_header = null;

    /**
     * table footer
     *
     * @var string
     */
    protected $_footer = null;

    /**
     * Instance of AbstractElements
     *
     * @param AbstractElements $element
     */
    public function setElement (AbstractElements $element)
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
     * set a possible table caption
     *
     * @param AbstractCaption $caption
     */
    public function setCaption (AbstractCaption $caption)
    {
        $this->_caption = $caption;
    }

    /**
     * set a possible table header
     *
     * @param AbstractHeader $header
     */
    public function setHeader (AbstractHeader $header)
    {
        $this->_header = $header;
        $this->_elements = array();
    }

    /**
     * set a possible table footer
     *
     * @param AsbtractFooter $footer
     */
    public function setFooter (AbstractFooter $footer)
    {
        $this->_footer = $footer;
        $this->_elements = array();
    }

    /**
     * display a html table
     */
    abstract public function display ();
}