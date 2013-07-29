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
 * @category contentinum library
 * @package html
 * @copyright Copyright (c) 2009-2011 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @version $Id: Factory.php 39 2012-09-01 14:24:27Z mike $
 * @since contentinum library release 0.1
 *        See also docs/LICENCE.txt for details
 */
// included from parent file ...
if (! defined('VALID_PAGE')) {
    die('Restricted access');
}

/**
 * Factory html table
 *
 * @use Contentinum_Html_Table_Interface
 *
 * @category contentinum library
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2005-2008 jochum-mediaservices, Katja Jochum
 *            (http://www.jochum-mediaservices.de)
 * @license http://www.contentinum-library.de/licenses BSD License
 */
class Contentinum_Html_Table_Factory implements Contentinum_Html_Table_Interface
{

    /**
     * create a html table with content
     *
     * @return Contentinum_Html_Table_View_Table
     */
    public function createTable ()
    {
        $table = new Contentinum_Html_Table_View_Table();
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
        $caption = new Contentinum_Html_Table_View_Caption($content, $attribute, $tag);
        return $caption;
    }

    /**
     * create a table row
     *
     * @return Contentinum_Html_Table_View_Row
     */
    public function createElement ($tag = false, $attribute = false)
    {
        $element = new Contentinum_Html_Table_View_Row($tag, $attribute);
        return $element;
    }

    /**
     * create a table header line (th)
     *
     * @return Contentinum_Html_Table_View_Header
     */
    public function createHeader ()
    {
        $header = new Contentinum_Html_Table_View_Header();
        return $header;
    }

    /**
     * create a table footer
     *
     * @return Contentinum_Html_Table_View_Footer
     */
    public function createFooter ()
    {
        $footer = new Contentinum_Html_Table_View_Footer();
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
        $listelement = new Contentinum_Html_Table_View_Cell($content, $attribute, $tag);
        return $listelement;
    }
}