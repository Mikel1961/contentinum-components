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

class AccountGroup extends AbstractContentHelper
{

    const VIEW_TEMPLATE = 'orga';
    
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
    protected $member;    
    
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
        'description',
        'accountFax',
        'accountPhone',
        'accountEmail',
        'imgSource',
        'member'
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
            
            $cardData .= $this->deployRow($this->organisation, $entry->organisation);
            if (1 != $entry->imgSource){
                $cardData .= $this->deployRow($this->imgSource, $this->view->images(array('mediaStyle' => '','medias' => $entry->imgSource), $medias));

            }
            $cardData .= $this->deployRow($this->member, $entry->organisationExt);
        
            
            if (isset($this->address['grids'])){
                $location = '';
                $grids = $this->address['grids'];
                    if (strlen($entry->accountStreet) > 1){
                        if (isset($grids['accountStreet'])){
                            $location .= $this->deployRow($grids['accountStreet'], $entry->accountStreet);
                        } else {
                            $location .= $entry->accountStreet . ' ';
                        }
                    }
                                  
                    if (strlen($entry->accountZipcode) > 1){
                        if (isset($grids['accountZipcode'])){
                            $location .= $this->deployRow($grids['accountZipcode'], $entry->accountZipcode);
                        } else {
                            $location .= $entry->accountZipcode . ' ';
                        }                    
                    }
                    
                    if (strlen($entry->accountCity) > 1){
                        if (isset($grids['accountZipcode'])){
                            $location .= $this->deployRow($grids['accountCity'], $entry->accountCity);
                        } else {
                            $location .= $entry->accountCity;
                        }
                    }   
                $cardData .= $this->deployRow($this->address, $location);
                
            }        
            if (strlen($entry->accountPhone) > 1){    
                $cardData .= $this->deployRow($this->accountPhone, $entry->accountPhone);
            }
            if (strlen($entry->accountFax) > 1){
                $cardData .= $this->deployRow($this->accountFax, $entry->accountFax);
            }            
            if (strlen($entry->accountEmail) > 1){
                $cardData .= $this->deployRow($this->accountEmail, $entry->accountEmail);
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