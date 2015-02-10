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

use Zend\View\Helper\AbstractHelper;

class Wanted extends AbstractHelper
{

    const VIEW_TEMPLATE = 'wanted';

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
        $html = '';
        foreach ($entries['modulContent'] as $entry) {
            $cardData = '';
            $name = $this->salutation($entry->salutation) . $entry->firstName . ' ' . $entry->lastName;
            $cardData .= $this->deployRow($this->name, $name);
            $cardData .= $this->deployRow($this->businessTitle, $entry->businessTitle);
            
            if (isset($this->address['grids'])){
                $location = '';
                $grids = $this->location['grids'];
                if (strlen($entry->contactAddress) > 1){
                    if (isset($grids['contactAddress'])){
                        $location .= $this->deployRow($grids['contactAddress'], $entry->contactAddress);
                    } else {
                        $location .= $entry->contactAddress . ' ';
                    }
                }
            
                if (strlen($entry->contactZipcode) > 1){
                    if (isset($grids['contactZipcode'])){
                        $location .= $this->deployRow($grids['contactZipcode'], $entry->contactZipcode);
                    } else {
                        $location .= $entry->contactZipcode . ' ';
                    }
                }
            
                if (strlen($entry->contactCity) > 1){
                    if (isset($grids['contactCity'])){
                        $location .= $this->deployRow($grids['contactCity'], $entry->contactCity);
                    } else {
                        $location .= $entry->contactCity;
                    }
                }
                $cardData .= $this->deployRow($this->address, $location);
                
            }            
            $cardData .= $this->deployRow($this->phoneWork, $entry->phoneWork);
            $cardData .= $this->deployRow($this->contactEmail, $entry->contactEmail);
            $html .= $this->deployRow($this->schema, $cardData);
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