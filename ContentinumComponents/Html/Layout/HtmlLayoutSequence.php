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
 * @package Html
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 1.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Html\Layout;

use ContentinumComponents\Html\Exeption\InvalidValueHtmlException;

/**
 * Create, build array with the layout elements
 * define from squence tag in xml config file
 *
 * @category contentinum library
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) jochum-mediaservices, Katja Jochum
 *            (http://www.jochum-mediaservices.de)
 * @license http://www.contentinum-library.de/licenses BSD License
 */
class HtmlLayoutSequence
{

    /**
     * Number of elements in configuration data
     *
     * @var integer
     */
    protected $count;

    /**
     * Contains array of configuration data
     *
     * @var array
     */
    protected $data;

    /**
     * Set configuration
     *
     * @param array $datas
     */
    public function conf(array $datas = array())
    {
        if (empty($datas)) {
            throw new InvalidValueHtmlException('The array must have a content');
        }
        $this->data = array();
        foreach ($datas as $key => $value) {
            if (is_array($value)) {
                $this->data[$key] = self::conf($value);
            } else {
                $this->data[$key] = $value;
            }
        }
        $this->count = count($this->data);
        return $this;
    }

    /**
     * Retrieve a value and return $default if there is no element set.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        $result = $default;
        if (array_key_exists($name, $this->data)) {
            $result = $this->data[$name];
        }
        return $result;
    }

    /**
     * Magic function so that $obj->value will work.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Set the whole data array
     *
     * @param array $datas
     * @return Contentinum_Interface
     */
    public function set(array $datas = array())
    {
        $this->data = $datas;
        return $this;
    }

    /**
     * Prepare execute if neacassery
     *
     * @param array $params some parameters
     */
    public function prepare($params = array())
    {
        return $this;
    }

    /**
     * Exectute Action
     *
     * @param array $params some parameters
     *       
     */
    public function execute($params = array())
    {
        foreach ($this->data['action'] as $row) {
            if (is_string($row)) {
                continue;
            }
            $layout[] = $this->make($row);
        }
        return $layout;
    }

    /**
     * Build layout informations
     *
     * @param unknown_type $row
     */
    protected function make($row)
    {
        $str = null;
        switch ($row['set']) {
            case 'assign':
                $str = array(
                    'assign' => $row['param']['value']
                );
                break;
            case 'content':
                $str = array(
                    'content' => $row['param']['value']
                );
                break;
            case 'load':
                $str = array(
                    'load' => $row['param']['value']
                );
                break;
            case 'boxend':
                $str = '</' . $row['param']['value'] . '>';
                break;
            default:
                $str = '<' . $row['set'];
                foreach ($row['param'] as $k => $v) {
                    if (is_array($v) && array_key_exists('type', $v)) {
                        $str = $str . ' ' . $v['type'] . '="' . $v['value'] . '"';
                    } else {
                        $str = $str . ' ' . $row['param']['type'] . '="' . $row['param']['value'] . '"';
                        break;
                    }
                }
                $str = $str . '>';
        }
        return $str;
    }
}