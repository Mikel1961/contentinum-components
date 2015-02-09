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
    
    const VIEW_TEMPLATE = 'events';
   
    /**
     *
     * @var array
     */
    protected $files;
    
    /**
     *
     * @var array
     */
    protected $toolbar;
    
    /**
     * 
     * @var unknown
     */
    protected $schema;
    
    /**
     *
     * @var array
     */
    protected $wrapper;

    
    /**
     *
     * @var array
     */
    protected $summary;
    
    /**
     *
     * @var array
     */
    protected $organizer;
    
    /**
     *
     * @var array
     */
    protected $dateStart;

    /**
     *
     * @var array
     */
    protected $organisation;
    
    /**
     *
     * @var array
     */
    protected $location;  
    
    /**
     *
     * @var array
     */
    protected $properties = array(        
        'files',        
        'toolbar',    
        'wrapper',
        'schema',
        'summary',
        'organizer',
        'dateStart',
        'organisation',
        'location',
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
        
        $viewTemplate = $this->view->groupstyles[$this->getLayoutKey()];
        if (isset($viewTemplate[self::VIEW_TEMPLATE])){
            $this->setTemplate($viewTemplate[self::VIEW_TEMPLATE]);
        }
        $events = '';
   
        $dataProp = array();
        foreach ($entries['modulContent'] as $entry) {
            $dateData = '';
            $dataProp['data-summary'] = $entry->summary;
            
            $dateData .= $this->deployRow($this->summary, $entry->summary);
            $dataProp['data-attendee'] = $entry->organizer;
            $dateData .= $this->deployRow($this->organizer, $entry->organizer);
            // time
            
            $datetime = new \DateTime($entry->dateStart);        
            $dataProp['data-dstart'] = $datetime->format("Ymd\\THis");
            $dataProp['data-dend'] = '00000000T000000';
            
            $content = $this->view->dateFormat(new \DateTime($entry->dateStart), \IntlDateFormatter::FULL) . ', ' . $datetime->format("H:i");
            $dateData .= $this->deployRow($this->dateStart, $content, '<meta content="' . $datetime->format("Y-m-d\\TH:i:s\\Z+01:00") . '" itemprop="startDate">');
            
            
            // location new
            if (1 !== $entry->account->id){
                if (isset($this->location['grids'])){
                    $location = '';
                    $grids = $this->location['grids'];
                    if (strlen($entry->account->accountStreet) > 1){
                        if (isset($grids['accountStreet'])){
                            $location .= $this->deployRow($grids['accountStreet'], $entry->account->accountStreet);
                        } else {
                            $location .= $entry->account->accountStreet . ' ';
                        }
                    }
                                  
                    if (strlen($entry->account->accountZipcode) > 1){
                        if (isset($grids['accountZipcode'])){
                            $location .= $this->deployRow($grids['accountZipcode'], $entry->account->accountZipcode);
                        } else {
                            $location .= $entry->account->accountZipcode . ' ';
                        }                    
                    }
                    
                    if (strlen($entry->account->accountCity) > 1){
                        if (isset($grids['accountZipcode'])){
                            $location .= $this->deployRow($grids['accountCity'], $entry->account->accountCity);
                        } else {
                            $location .= $entry->account->accountCity;
                        }
                    }                
                    
                }
                $orgaExt = '';
                if ( strlen($entry->account->organisationExt) > 1 ){
                    $orgaExt = ' ' . $entry->account->organisationExt;
                }
                
                $locationName = $this->deployRow($this->organisation, $entry->account->organisation.$orgaExt);
                
                $dateData .= $this->deployRow($this->location, $location, $locationName);
                
                $dataProp['data-location'] = $entry->account->organisation . $orgaExt . ' ' . $entry->account->accountStreet . ' ' . $entry->account->accountZipcode . ' ' . $entry->account->accountCity;
            } else {
                if (isset($this->location['grids'])){
                    $location = '';
                    $grids = $this->location['grids'];
                    if (strlen($entry->locationAddresse) > 1){
                        if (isset($grids['accountStreet'])){
                            $location .= $this->deployRow($grids['accountStreet'], $entry->locationAddresse);
                        } else {
                            $location .= $entry->locationAddresse . ' ';
                        }
                    }
                    
                    if (strlen($entry->locationZipcode) > 1){
                        if (isset($grids['accountZipcode'])){
                            $location .= $this->deployRow($grids['accountZipcode'], $entry->locationZipcode);
                        } else {
                            $location .= $entry->locationZipcode . ' ';
                        }
                    }                    
                    
                    
                    if (strlen($entry->locationCity) > 1){
                        if (isset($grids['accountZipcode'])){
                            $location .= $this->deployRow($grids['accountCity'], $entry->locationCity);
                        } else {
                            $location .= $entry->locationCity;
                        }
                    } 
                }
                
                $locationName = $this->deployRow($this->organisation, $entry->location);
                
                $dateData .= $this->deployRow($this->location, $location, $locationName);
                
                $dataProp['data-location'] = $entry->location . ' ' . $entry->locationAddresse . ' ' . $entry->locationZipcode . ' ' . $entry->locationCity;                
                
            }
            
            $toolbar = '';
            if (null !== $this->toolbar){
                $toolbar = $this->view->contenttoolbar(array('getevent' => array('attr' => $dataProp)),$medias, $this->toolbar->toArray());

            }            
            $events .= $this->deployRow($this->schema, $toolbar . $dateData);
            $dataProp = array();
            
        }
     
        
        $events = $this->deployRow($this->wrapper, $events);
        
        if (null !== $this->files){
            $this->deployFiles($this->files);
        }
        
        return $events;
    }  

}