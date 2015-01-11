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
namespace ContentinumComponents\View\Helper\Content\Customer;

use Zend\View\Helper\AbstractHelper;


class AccountMembers extends AbstractHelper
{

    
    /**
     *
     * @var unknown
     */
    private $row = array(
        'element' => 'ul',
        'attr' => array('class' => 'small-block-grid-1 medium-block-grid-2 large-block-grid-3 account-member-list')
    
    );
    
    /**
     *
     * @var unknown
    */
    private $grid = array(
        'element' => 'li',
    
    );
    
    /**
     *
     * @var unknown
    */
    private $properties = array(
        'row',
        'grid',
    );
    
    public function __invoke(array $entry, $medias, $template)
    {
        if ('shufflepictures' === $entry['modulFormat']){
            return $this->shufflepictures($entry, $medias, $template);
        }
        
        
        $grid = $this->getTemplateProperty('grid', 'element');
        $list = '';
        $breakloop = false;
        $liAttr = '';
        foreach ($entry['modulContent'] as $orga => $entryRow){
            $character = $orga{0};
            if (false !== $breakloop && $breakloop !== $character){
                $liAttr = ' class="list-item-displaynone"';
            }
            $breakloop = $character;
            $characters[$character] = $character;
            $dataKey = ' data-sortkey="' . $character . '"';
            $list .= '<' . $grid . $liAttr . $dataKey . '>';
            $list .= '<figure class="account-member-list-item">';
            $list .= '<img src="' . $entryRow['imgSource'] . '" alt="" />';
            $list .= '<figcaption class="account-member-list-item-caption"><p>' . $entryRow['organisation'];

            //account_street 	account_addresss 	account_zipcode 	account_city
            //if ( strlen($entryRow['accountStreet']) > 1 ){
            //    $list .= '<br />' . $entryRow['accountStreet'];
            //}
            
            $list .= '</p></figcaption>';
            $list .= '</figure>';
            $list .= '</' . $grid . '>';
        }
        $html = $this->navigation($characters);
        $html .= $this->view->contentelement($this->getTemplateProperty('row', 'element'), $list, $this->getTemplateProperty('row', 'attr'));
        return $html;        
    } 

    /**
     * 
     * @param unknown $characters
     * @return string
     */
    protected function navigation($characters)
    {
        $list = '<ul class="nav-letter-list">';
        foreach (range('a', 'z') as $letter){
            $list .= '<li class="nav-letter-list-item';
            if (isset($characters[$letter])){
                $list .= ' hascontent">';
                $list .= '<a class="nav-letter-list-item-link" href="#" data-letterkey="'.$letter.'">' . strtoupper($letter) . '</a>';
            } else {
                $list .= '"><a href="#">' . strtoupper($letter) . '</a>';
            }
            $list .= '</li>'; 
        }
        $list .= '</ul>';
        return $list;
    }
    
    protected function shufflepictures(array $entry, $medias, $template)
    {
        $grid = $this->getTemplateProperty('grid', 'element');
        $list = '';        
        $listcontent = $entry['modulContent'];
        shuffle($listcontent);
        $list = '';
        $i = 0;
        foreach ($listcontent as $orga => $entryRow){
            $list .= '<' . $grid . ' class="account-list-item">';
            $list .= '<figure class="account-list-item-img">';
            $list .= '<img src="' . $entryRow['imgSource'] . '" alt="" />';
            $list .= '</figure>';
            $list .= '</' . $grid . '>';     
            $i++;
            if ($i == $entry['modulDisplay']){
                break;
            }       
        }
        $html = $this->view->contentelement($this->getTemplateProperty('row', 'element'), $list, array('class' => 'account-list-images'));
        return $html;        
    }

    /**
     *
     * @param unknown $prop
     * @param unknown $key
     * @return boolean
     */
    protected function getTemplateProperty($prop, $key)
    {
        if (isset($this->{$prop}[$key])) {
            return $this->{$prop}[$key];
        } else {
            return false;
        }
    }
    
    /**
     *
     * @param unknown $template
     */
    protected function setTemplate($template)
    {
        if (null !== $template) {
    
            foreach ($template as $key => $values) {
                if (in_array($key, $this->properties)) {
                    $this->{$key} = $values;
                }
            }
        }
    }
    
    protected function unsetProperties()
    {
        foreach ($this->properties as $prop){
            $this->{$prop} = null;
        }
    }    
}