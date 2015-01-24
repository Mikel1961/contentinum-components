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

use ContentinumComponents\View\Helper\Content\AbstractContentHelper;

class MunicipalGroups extends AbstractContentHelper
{

    /**
     *
     * @var array
     */
    protected $row;

    /**
     *
     * @var array
     */
    protected $grid;

    /**
     *
     * @var array
     */
    protected $elements;

    /**
     *
     * @var array
     */
    protected $properties = array(
        'row',
        'grid',
        'elements'
    );

    protected $salutation = array(
        'mr' => 'Herr',
        'ms' => 'Frau'
    );

    public function __invoke(array $entries, $medias, $template)
    {
        $html = '';        
        if (isset($entries['modulContent']['departments']) && $entries['modulContent']['business']) {
            
            $departments = $entries['modulContent']['departments'];
            $business = $entries['modulContent']['business'];
            $id = 0;

            $filter = new \Zend\Filter\Digits(); // business
            foreach ($departments as $entry) {
                if ('4' != $entry->employeeTypes->id) {
                    $html .= $this->buildContact($entry, $entry->departments->name, $filter, $medias);
                }
                if ($id !== $entry->departments->id) {
                    if (isset($business[$entry->departments->id])) {
                        $html .= $this->buildBusiness($business[$entry->departments->id], $medias);
                    }
                }
                $id = $entry->departments->id;
            }
        }
        return $html;
    }

    protected function buildBusiness($businessEntries, $medias)
    {
        $html = '';
        $filter = new \Zend\Filter\Digits(); // business
        foreach ($businessEntries as $entry) {
            if ('4' != $entry->employeeTypes->id) {
                $html .= $this->buildContact($entry, $entry->business->name . '<br />' . $entry->business->nameDesc, $filter, $medias);
            }
        }
        return $html;
    }

    /**
     *
     * @param unknown $entry
     * @param unknown $department
     * @param \Zend\Filter\Digits $filter \Zend\Filter\Digits
     * @return string
     */
    protected function buildContact($entry, $department, $filter, $medias)
    {
        $contact = '<div class="panel" itemscope itemtype="http://schema.org/Person">';
        
        $contact .= '<div itemscope itemtype="http://schema.org/Organization">';
        $contact .= '<h5 itemprop="department">';
        $contact .= $department;
        $contact .= '</div>';
        $contact .= '<div class="vcard-description">';
        
        if (strlen($entry->contact->contactImgSource) > 1) {
            $contact .= '<figure class="vcard-images right" itemprop="image">' . $this->view->images(array(
                'medias' => $entry->contact->contactImgSource,
                'mediaStyle' => ''
            ), $medias) . '</figure>';
        }
        
        $contact .= '<h4 itemprop="name">' . $this->cSalutation($entry->contact->salutation) . $entry->contact->firstName . ' ' . $entry->contact->lastName . '</h4>';
        
        $contact .= '<p itemprop="jobTitle">' . $entry->contact->title . '<br />';
        $contact .= '' . $entry->contact->businessTitle . '</p>';
        $contact .= '<ul class="vcard-contact-list">';
        $contact .= '<li>Telefon: <a href="tel:0049' . substr($filter->filter($entry->contact->phoneWork), 1) . '" itemprop="telephone">' . $entry->contact->phoneWork . '</a></li>';
        if (strlen($entry->contact->phoneFax) > 1) {
            $contact .= '<li>Fax: <a href="tel:0049' . substr($filter->filter($entry->contact->phoneFax), 1) . '" itemprop="faxNumber">' . $entry->contact->phoneFax . '</a></li>';
        }
        if (strlen($entry->contact->alternateEmail) > 1) {
            $contact .= '<li>E-Mail: <a href="mailto:' . $entry->contact->alternateEmail . '" itemprop="email">' . $entry->contact->alternateEmail . '</a></li>';
        }
        $contact .= '</ul>';
        $contact .= $entry->contact->description;
        $contact .= '</div>';
        
        $contact .= '</div>';
        return $contact;
    }

    protected function cSalutation($string)
    {
        $str = '';
        if (strlen($string) > 1) {
            if (isset($this->salutation[$string])) {
                return $this->salutation[$string] . ' ';
            }
        }
        return $str;
    }
}