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
namespace Contentinum\Html\Lists;

use Contentinum\Html\Lists\InterfaceList;
use Contentinum\Html\Lists\View\Lists;
use Contentinum\Html\Lists\View\ListElements;
use Contentinum\Html\Lists\View\ListElement;
/**
 * Html List Factory
 * 
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
class FactoryList implements InterfaceList
{

    /**
     * build a list ol or ul
     *
     * @return Lists
     */
    public function createList ()
    {
        $ul = new Lists();
        return $ul;
    }

    /**
     * create a list element
     *
     * @return ListElements
     */
    public function createElement ()
    {
        $element = new ListElements();
        return $element;
    }

    /**
     * build a list element
     *
     * @param string $content
     * @param string $attribute
     * @return ListElement
     */
    public function createListElement ($content, $attribute = false, $tag = false)
    {
        $listelement = new ListElement($content, $attribute, $tag);
        return $listelement;
    }
}