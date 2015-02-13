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

class Wantedgroup extends AbstractContentHelper
{

    const VIEW_TEMPLATE = 'person';
    
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
     * @var array
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
    protected $name;
    
    /**
     *
     * @var array
     */
    protected $internet;
    
    /**
     *
     * @var array
     */
    protected $contactImgSource;
    
    /**
     *
     * @var array
     */
    protected $businessTitle;
    
    /**
     *
     * @var array
     */
    protected $phoneWork;
    
    /**
     *
     * @var array
     */
    protected $phoneFax;
    
    /**
     *
     * @var array
     */
    protected $contactEmail;
    
    /**
     *
     * @var array
     */
    protected $organisation;
    
    /**
     *
     * @var array
     */
    protected $address;
    
    /**
     *
     * @var array
     */
    protected $description;

    /**
     *
     * @var array
     */
    protected $properties = array(
        'files',
        'toolbar',
        'wrapper',
        'schema',
        'name',
        'internet',
        'contactImgSource',
        'businessTitle',
        'phoneWork',
        'phoneFax',
        'contactEmail',
        'organisation',
        'address',
        'description'
    );

    /**
     *
     * @param unknown $entries
     * @param unknown $medias
     * @param string $template
     * @return string
     */
    public function __invoke($entries, $medias, $template = null)
    {
        $viewTemplate = $this->view->groupstyles[$this->getLayoutKey()];
        if (isset($viewTemplate[self::VIEW_TEMPLATE])){
            $this->setTemplate($viewTemplate[self::VIEW_TEMPLATE]);
        }        
        
        $html = '';
        foreach ($entries['modulContent'] as $entry) {
            $cardData = '';
            $name = $this->salutation($entry->contacts->salutation) . $entry->contacts->firstName . ' ' . $entry->contacts->lastName;
            if (1 != $entry->contacts->contactImgSource){
                $cardData .= $this->deployRow($this->contactImgSource, $this->view->images(array('mediaStyle' => '','medias' => $entry->contacts->contactImgSource), $medias));

            }
            $cardData .= $this->deployRow($this->name, $name);
            $cardData .= $this->deployRow($this->businessTitle, $entry->contacts->businessTitle);
            
            if (isset($this->address['grids'])){
                $location = '';
                $grids = $this->address['grids'];
                if (strlen($entry->contacts->contactAddress) > 1){
                    if (isset($grids['contactAddress'])){
                        $location .= $this->deployRow($grids['contactAddress'], $entry->contacts->contactAddress);
                    } else {
                        $location .= $entry->contacts->contactAddress . ' ';
                    }
                }
            
                if (strlen($entry->contacts->contactZipcode) > 1){
                    if (isset($grids['contactZipcode'])){
                        $location .= $this->deployRow($grids['contactZipcode'], $entry->contacts->contactZipcode);
                    } else {
                        $location .= $entry->contacts->contactZipcode . ' ';
                    }
                }
            
                if (strlen($entry->contacts->contactCity) > 1){
                    if (isset($grids['contactCity'])){
                        $location .= $this->deployRow($grids['contactCity'], $entry->contacts->contactCity);
                    } else {
                        $location .= $entry->contacts->contactCity;
                    }
                }
                $cardData .= $this->deployRow($this->address, $location);
                
            }        
            if (strlen($entry->contacts->phoneWork) > 1){    
                $cardData .= $this->deployRow($this->phoneWork, $entry->contacts->phoneWork);
            }
            if (strlen($entry->contacts->phoneFax) > 1){
                $cardData .= $this->deployRow($this->phoneFax, $entry->contacts->phoneFax);
            }            
            if (strlen($entry->contacts->contactEmail) > 1){
                $cardData .= $this->deployRow($this->contactEmail, $entry->contacts->contactEmail);
            }
            $html .= $this->deployRow($this->schema, $cardData);
        }
        if (null !== $this->wrapper){
            $html = $this->deployRow($this->wrapper, $html);
        }
        return $html;
    }

    /**
     *
     * @param unknown $var
     * @return string
     */
    protected function salutation($var)
    {
        $str = '';
        switch ($var) {
            case 'mr':
                $str = 'Herr ';
                break;
            case 'ms':
                $str = 'Frau ';
                break;
            default:
                break;
        }
        return $str;
    }
}