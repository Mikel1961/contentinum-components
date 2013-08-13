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
namespace ContentinumComponents\Html\Element\View;

use ContentinumComponents\Html\Element\AbstractElement;
/**
 * Set content in html elements
 * If avaibale set html attributes
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
class Content extends AbstractElement
{

    /**
     * format the display from a html body content elements
     * retun a array or a string with content
     * depending on the settings in Libary_Html_Elements_Abstract
     *
     * @return array or string
     */
    public function display ()
    {
        $out = '';
        if ($this->_tag) {
            switch ($this->_output) {
                case 'array':
                    $out[] = "\n<{$this->_tag}{$this->_attribute}>";
                    break;
                default:
                    $out = $out . "\n<{$this->_tag}{$this->_attribute}>";
                    break;
            }
        }
        foreach ($this->_elements as $element) {
            switch ($this->_output) {
                case 'array':
                    $out[] = $element->display();
                    break;
                default:
                    $out = $out . $element->display();
                    break;
            }
        }
        if ($this->_tag) {
            switch ($this->_output) {
                case 'array':
                    $out[] = "\n</{$this->_tag}>";
                    break;
                default:
                    $out = $out . "\n</{$this->_tag}>";
                    break;
            }
        }
        return $out;
    }
}