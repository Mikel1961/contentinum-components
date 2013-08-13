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
namespace ContentinumComponents\Html\Table\View;

use ContentinumComponents\Html\Table\AbstractTable;
/**
 * Display a html table
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
class Table extends AbstractTable
{

    /**
     * display a complete html table
     *
     * @return string
     */
    public function display ()
    {
        $html = "\n<table{$this->_attribute}>\n";
        if ($this->_caption) {
            $html = $html . $this->_caption->display();
        }
        if ($this->_header) {
            $html = $html . $this->_header->display();
        }
        if ($this->_footer) {
            $html = $html . $this->_footer->display();
        }
        $html = $html . "\n  <tbody>";
        foreach ($this->_elements as $element) {
            $html = $html . $element->display();
        }
        $html = $html . "\n  </tbody>";
        $html = $html . "\n</table>";
        return $html;
    }
}