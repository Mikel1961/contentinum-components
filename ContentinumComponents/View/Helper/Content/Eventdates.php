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
namespace ContentinumComponents\View\Helper\Content;




use ContentinumComponents\Html\HtmlAttribute;
class Eventdates extends AbstractContentHelper
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
    
    /**
     *
     * @param array $entry
     * @param unknown $medias
     * @param unknown $template
     * @return unknown
    */
    public function __invoke(array $entries, $medias, $template)
    {
        $this->setTemplate($template);
        $events = '';
        $dataProp = array();
        foreach ($entries['modulContent'] as $entry) {
            $dateData = '';
            $dataProp['data-summary'] = $entry->summary;
            $dateData .= '<h3  class="event-summary"><span  class="summary" itemprop="name"> '. $entry->summary .' </span></h3>';
            $dataProp['data-attendee'] = $entry->organizer;
            $dateData .= '<h5  class="event-location-organizer"><span class="attendee" itemprop="attendee">'. $entry->organizer .'</span></h5>';
            // time
            
            $datetime = new \DateTime($entry->dateStart);        
            $dataProp['data-dstart'] = $datetime->format("Ymd\\THis");
            $dataProp['data-dend'] = '00000000T000000';
            $dateData .= '<p class="event-date"><meta content="' . $datetime->format("Y-m-d\\TH:i:s\\Z+01:00") . '" itemprop="startDate">';
            $dateData .= '<time>' . $this->view->dateFormat(new \DateTime($entry->dateStart), \IntlDateFormatter::FULL);
            $dateData .= ', ' . $datetime->format("H:i");
            $dateData .= ' Uhr</time></p>';
            
            // location
            $dateData .= '<div class="location" itemtype="http://schema.org/Place" itemscope="" itemprop="location">';
            $dateData .= '<p class="event-location-name" itemprop="name">';
            $orgExt = '';
            $dateData .= $entry->account->organisation;
            if (strlen($entry->account->organisation) > 1){
                $dateData .= ', ' . $entry->account->organisationExt;
                $orgExt = ', ' . $entry->account->organisationExt;
            }    

            $dateData .= '</p>';
            $dateData .= '<p class="event-location-address" itemtype="http://schema.org/PostalAddress" itemscope="itemscope" itemprop="address">';
            $dateData .= '<span itemprop="streetAddress">'.$entry->account->accountStreet .'</span>';
            $dateData .= ', <span itemprop="postalCode">'.$entry->account->accountZipcode .'</span>';
            $dateData .= ' <span itemprop="addressLocality">'.$entry->account->accountCity .'</span>';
            $dateData .= '</p>';
            $dateData .= '</div>';
            $dataProp['data-location'] = $entry->account->organisation . $orgExt . ', ' . $entry->account->accountStreet . ', ' . $entry->account->accountZipcode . ' ' . $entry->account->accountCity;
            $events .= '<div class="event-wrapper panel" itemscope="itemscope" itemtype="http://schema.org/Event">';
            $events .= $this->btnroup($dataProp);
            $events .= $dateData;
            $events .= '</div>';
            $dataProp = array();
            
        }
        $this->view->inlinescript()->offsetSetFile(30,'/assets/app/js/vendor/ics/ics-libs.js');
        $this->view->inlinescript()->offsetSetFile(31,'/assets/app/js/vendor/ics/getics.js');
        return $events;
    }  

    
    protected function btnroup($datas)
    {
        $datas['class'] = 'getics';
        $datas['title'] = 'Termin herunterladen';
        $str = '<ul class="inline-list right right"><li>';
        $str .= '<a ' . HtmlAttribute::attributeArray($datas) . '>';
        $str .= '<i class="fa fa-download"></i></a>';
        $str .= '</li></ul>';
        return $str;
    }
}