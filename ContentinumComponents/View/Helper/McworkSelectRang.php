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
 * @package View
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 5.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Select form element
 * Created a selection form field with the number of data sets of a group
 * Displays the number in the order of the group
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
class McworkSelectRang extends AbstractHelper
{

    /**
     * Field name
     *
     * @var string
     */
    private $name = 'itemRang';

    /**
     * Stylesheet class name
     *
     * @var string
     */
    private $fieldclass = 'changerang';

    /**
     * Property names
     *
     * @var array
     */
    private $attribute = array(
        'name',
        'fieldclass'
    );

    /**
     * Create select form field
     *
     * @param int $group group ident
     * @param int $id this category in the group
     * @param int $rang sequence number of this category
     * @param int $total numbers categories in this group
     * @param string $category category name
     * @return string select form field in html
     */
    public function __invoke($group, $id, $rang, $total, $category, array $attr = array())
    {
        $nums = (int) $total;
        $this->setAttribute($attr);
        
        $html = '<select class="' . $this->fieldclass . '" name="' . $this->name . '" data-group="' . $group . '"';
        $html .= ' data-category="' . $id . '" data-rang="' . $rang . '"';
        $html .= ' data-categoryname="' . $category . '" data-move="jump">';
        
        for ($i = 1; $i <= $total; $i ++) {
            $selected = '';
            if ($i == $rang) {
                $selected = ' selected="selected"';
            }
            $html .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    /**
     * Set properties when available
     *
     * @param array $attr
     */
    protected function setAttribute($attr)
    {
        if (! empty($attr)) {
            foreach ($this->attribute as $props) {
                if (isset($attr[$props])) {
                    $this->$props = $attr[$props];
                }
            }
        }
    }
}