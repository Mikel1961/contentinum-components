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
        foreach ($entries['modulContent'] as $datas) {
            $dateData = '';
            $dataProp['data-summary'] = $datas->summary;
            $dateData .= '<h3><span  class="summary" itemprop="name"> '. $datas->summary .' </span></h3>';
            $dataProp['data-attendee'] = $datas->organizer;
            $dateData .= '<h5><span class="attendee" itemprop="attendee">'. $datas->organizer .'</span></h5>';
            
            $dateData .= '<p><meta content="2015-01-16T11:00:00Z+01:00" itemprop="startDate">';
            $dateData .= '<time>' . $this->view->dateFormat(new \DateTime($entry->dateStart), \IntlDateFormatter::FULL);
            $dateData .= $this->view->dateFormat(new \DateTime($entry->dateStart), \IntlDateFormatter::NONE, \IntlDateFormatter::SHORT);
            $dateData .= 'Uhr</time></p>';
            
            $events .= '<div itemscope="itemscope" itemtype="http://schema.org/Event">';
            $events .= $dateData;
            $events .= '</div>';
            
        }
        return $events;
    }    
}