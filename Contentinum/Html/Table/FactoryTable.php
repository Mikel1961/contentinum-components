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
namespace Contentinum\Html\Table;

use Contentinum\Html\Table\InterfaceTable;
use Contentinum\Html\Table\View\Table;
use Contentinum\Html\Table\View\TableCaption;
use Contentinum\Html\Table\View\TableRow;
use Contentinum\Html\Table\View\TableHeader;
use Contentinum\Html\Table\View\TableFooter;
use Contentinum\Html\Table\View\TableCell;

/**
 * Factory html table
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
class FactoryTable implements InterfaceTable
{

    /**
     * create a html table with content
     *
     * @return Contentinum_Html_Table_View_Table
     */
    public function createTable ()
    {
        $table = new Table();
        return $table;
    }

    /**
     * create a table caption
     *
     * @param string $caption content
     * @param string $attribute possible tag attributes
     * @param string $tag html tag
     * @return Contentinum_Html_Table_View_Caption
     */
    public function createCaption ($content, $attribute = false, $tag = false)
    {
        $caption = new TableCaption($content, $attribute, $tag);
        return $caption;
    }

    /**
     * create a table row
     *
     * @return Contentinum_Html_Table_View_Row
     */
    public function createElement ($tag = false, $attribute = false)
    {
        $element = new TableRow($tag, $attribute);
        return $element;
    }

    /**
     * create a table header line (th)
     *
     * @return Contentinum_Html_Table_View_Header
     */
    public function createHeader ()
    {
        $header = new TableHeader();
        return $header;
    }

    /**
     * create a table footer
     *
     * @return Contentinum_Html_Table_View_Footer
     */
    public function createFooter ()
    {
        $footer = new TableFooter();
        return $footer;
    }

    /**
     * create a table cell element
     *
     * @param string $caption content
     * @param string $attribute possible tag attributes
     * @param string $tag html tag
     * @return Contentinum_Html_Table_View_CellView
     */
    public function createRowElement ($content, $attribute = false, $tag = false)
    {
        $listelement = new TableCell($content, $attribute, $tag);
        return $listelement;
    }
}