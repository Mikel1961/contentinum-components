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
namespace ContentinumComponents\View\Helper\Mcwork;

use Zend\View\Helper\AbstractHelper;

/**
 * Select form element
 * Created a selection form field with the number of data sets of a group
 * Displays the number in the order of the group
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 */
class PublishItem extends AbstractHelper
{

    /**
     * Publish item attribute
     *
     * @var string
     */
    private $yes = array(
        'label' => '<i class="fa fa-toggle-on fa-2x emerald-color"></i>',
        'class' => 'unpublish',
        'title' => 'Publish'
    );

    /**
     * Unpublish item attribute
     *
     * @var string
     */
    private $no = array(
        'label' => '<i class="fa fa-toggle-off fa-2x alizarin-color"></i></a>',
        'class' => 'publish',
        'title' => 'Unpublish'
    );

    public function __invoke($status, $ident, $categoryname)
    {
        $html = '<a class="' . $this->{$status}['class'] . '" data-ident="' . $ident . '" data-categoryname="' . $categoryname . '"';
        $html .= 'href="#" title="' . $this->view->translate($this->{$status}['title']) . '">';
        $html .= $this->{$status}['label'] . '</a>';
        return $html;
    }
}