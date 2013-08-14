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
namespace ContentinumComponents\Html\Element;

use ContentinumComponents\Html\Element\InterfaceElement;
use ContentinumComponents\Html\Element\View\Content;
use ContentinumComponents\Html\Element\View\Elements;
use ContentinumComponents\Html\Element\View\Element;
/**
 * Factory html element
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
class FactoryElement implements InterfaceElement
{

    /**
     * create xhtml elements
     *
     * @return ContentinumComponents\Html\Element\View\Content
     */
    public function createContentElements ()
    {
        $div = new Content();
        return $div;
    }

    /**
     * create a xhtml element
     *
     * @return ContentinumComponents\Html\Element\View\Elements
     */
    public function createElement ()
    {
        $elements = new Elements();
        return $elements;
    }

    /**
     * create a xhtml element
     *
     * @param string $content
     * @param string $attribute tag attributes
     * @param string $tag html tag
     * @return ContentinumComponents\Html\Element\View\Element
     */
    public function createContentElement ($content, $attribute = false, $tag = false)
    {
        $element = new Element($content, $attribute, $tag);
        return $element;
    }
}