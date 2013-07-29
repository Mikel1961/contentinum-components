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

use Contentinum\Html\Table\InterfaceTable;
/**
 * create and display a html table with content
 * set summary, caption, header, footer
 * is various
 * Extends Contentinum_Html_Elements
 * 
 * @author Michael Jochum <michael.jochum@jochum-mediaservices.de>
 */
class HtmlTable extends HtmlElements
{

    /**
     * table caption
     *
     * @var string
     */
    protected $_tableCaption = null;

    /**
     * table headerline content
     *
     * @var array
     */
    protected $_headline = array();

    /**
     * table headline attributes
     *
     * @var array
     */
    protected $_attributesHeadline = array();

    /**
     * table footer content
     *
     * @var array
     */
    protected $_footer = array();

    /**
     * table footer attributes
     *
     * @var array
     */
    protected $_attributesFooter = array();

    /**
     * table body rows attributes
     *
     * @var array
     */
    protected $_attributesRow = array();

    /**
     * Constructor: initialize FactoryElement
     *
     * @param FactoryElement $contentFactory
     * @return void
     */
    public function __construct ( InterfaceTable  $tableFactory)
    {
        parent::__construct($tableFactory);
        $this->_encloseTag = 'table';
    }

    /**
     * set table caption content
     *
     * @param string $caption
     */
    public function setCaption ($caption)
    {
        $this->_tableCaption = $caption;
    }

    /**
     * set table headeline content
     *
     * @param array $headlines
     */
    public function setHeadline ($headlines)
    {
        $this->_headline = $headlines;
    }

    /**
     * set table headline attributes
     *
     * @param string $name
     * @param string $value
     * @param string $key
     */
    public function setHeadlineAttributtes ($name, $value, $key)
    {
        $this->_attributesHeadline[$key][$name] = $value;
    }

    /**
     * set table footer content
     *
     * @param array $footer
     */
    public function setFooter ($footer)
    {
        $this->_footer = $footer;
    }

    /**
     * set table footer attributes
     *
     * @param string $name
     * @param string $value
     * @param string $key
     */
    public function setFooterAttributtes ($name, $value, $key)
    {
        $this->_attributesFooter[$key][$name] = $value;
    }

    /**
     * set table row attributes
     *
     * @param string $name
     * @param string $value
     * @param string $key
     */
    public function setRowAttributtes ($name, $value, $key)
    {
        $this->_attributesRow[$key][$name] = $value;
    }

    /**
     * display the html table
     *
     * @return string
     */
    public function display ()
    {
        // table object
        $table = $this->_htmlFactory->createTable();
        if (! empty($this->_attributes)) {
            $attribute = $this->tableAttributes($this->_attributes);
            // set attribute in table tag
            $table->setAttribute(' ' . $attribute);
        }
        // is / create table caption
        if ($this->_tableCaption) {
            $caption = $this->_htmlFactory->createCaption($this->_tableCaption, false, 'caption');
            $table->setCaption($caption);
        }
        // set a table header
        if (! empty($this->_headline)) {
            $this->tableHeaderline($table);
        }
        // set a table footer
        if (! empty($this->_footer)) {
            $this->tableFooter($table);
        }
        // set data body run through the data array
        $rowI = $cellI = 0; // ... row and cell counter
        foreach ($this->_htmlContent as $row) {
            $attribute = false;
            if (isset($this->_attributesRow[$rowI]) && ! empty($this->_attributesRow[$rowI])) {
                $attribute = $this->tableAttributes($this->_attributesRow[$rowI]);
            }
            $tableElement = $this->_htmlFactory->createElement('tr', $attribute);
            $table->setElement($tableElement);
            foreach ($row as $values) {
                $attribute = false;
                if (isset($this->_tagAttributes[$cellI]) && ! empty($this->_tagAttributes[$cellI])) {
                    $attribute = $this->tableAttributes($this->_tagAttributes[$cellI]);
                }
                $buildElement = $this->_htmlFactory->createRowElement($values, $attribute, 'td');
                $tableElement->setTagElement($buildElement);
                $cellI ++;
            }
            $cellI = 0;
            $rowI ++;
        }
        return $table->display();
    }

    /**
     * set table footer
     *
     * @param Libary_Html_Table_Interface $table
     */
    private function tableFooter ($table)
    {
        // set table header
        $tableFooter = $this->_htmlFactory->createFooter();
        $table->setElement($tableFooter);
        // loop through array content
        $i = 0;
        foreach ($this->_footer as $values) {
            $attribute = false;
            if (isset($this->_attributesFooter[$i]) && ! empty($this->_attributesFooter[$i])) {
                $attribute = $this->tableAttributes($this->_attributesFooter[$i]);
            }
            $buildElement = $this->_htmlFactory->createRowElement($values, $attribute, 'td');
            $tableFooter->setTagElement($buildElement);
            $i ++;
        }
        $table->setFooter($tableFooter);
    }

    /**
     * table headerline (th)
     *
     * @param Libary_Html_Table_Interface $table
     */
    private function tableHeaderline ($table)
    {
        // set table header
        $tableHeader = $this->_htmlFactory->createHeader();
        $table->setElement($tableHeader);
        $i = 0;
        // loop through array content
        foreach ($this->_headline as $values) {
            $attribute = false;
            if (isset($this->_attributesHeadline[$i]) && ! empty($this->_attributesHeadline[$i])) {
                $attribute = $this->tableAttributes($this->_attributesHeadline[$i]);
            }
            $buildElement = $this->_htmlFactory->createRowElement($values, $attribute, 'th');
            $tableHeader->setTagElement($buildElement);
            $i ++;
        }
        $table->setHeader($tableHeader);
    }

    /**
     * set serval attributes
     *
     * @param array $attributes
     * @return string
     */
    private function tableAttributes ($attributes)
    {
        $attribute = '';
        foreach ($attributes as $k => $v) {
            $attribute = $attribute . ' ' . HtmlAttribute::attributeString($k, $v);
        }
        return $attribute;
    }
}