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
 * @package Storage
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 4.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Images;

use ContentinumComponents\Images\Exeption\ErrorLogicImagesException;

/**
 * Calculate size from a image to resize this
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *
 */
class CalculateResize
{

    /**
     * Images width
     *
     * @var int
     */
    protected $width = null;

    /**
     * Images height
     *
     * @var int
     */
    protected $height = null;

    /**
     * Target value for calc
     *
     * @var int
     */
    protected $target = null;

    /**
     * File path to determine size
     *
     * @var string
     */
    protected $file = null;

    /**
     * Contains the new images size
     *
     * @var array
     */
    protected $newSize = array();
    
    /**
     * 
     * @var unknown
     */
    protected $format;

    /**
     * Construct
     *
     * @param int $target            
     * @param int $width            
     * @param int $height            
     * @param string $file            
     * @return void
     */
    public function __construct($target, $width = null, $height = null, $file = null)
    {
        $this->target = $target;
        $this->width = $width;
        $this->height = $height;
        $this->file = $file;
    }

    /**
     *
     * @return the $width
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     *
     * @param number $width            
     * @return CalculateResize
     */
    public function setWidth($width)
    {
        $this->width = $width;
        
        return $this;
    }

    /**
     *
     * @return the $height
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     *
     * @param number $height            
     * @return CalculateResize
     *
     */
    public function setHeight($height)
    {
        $this->height = $height;
        
        return $this;
    }

    /**
     *
     * @return the $target
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     *
     * @param number $target            
     * @return CalculateResize
     */
    public function setTarget($target)
    {
        $this->target = $target;
        
        return $this;
    }

    /**
     *
     * @return the $file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     *
     * @param string $file            
     * @return CalculateResize
     */
    public function setFile($file)
    {
        $this->file = $file;
        
        return $this;
    }

    /**
     * Get the new images size in a array
     * for use in further processes
     *
     * @return the $newSize
     */
    public function getNewSize()
    {
        // calculate new size
        $this->calc();
        if (empty($this->newSize)) {
            throw new ErrorLogicImagesException('Calculate faild, no values to resize images');
        }
        return $this->newSize;
    }

    /**
     *
     * @param multitype: $newSize            
     * @return CalculateResize
     */
    public function setNewSize($newSize)
    {
        $this->newSize = $newSize;
        
        return $this;
    }

    /**
     * @return the $format
     */
    public function getFormat()
    {
        return $this->format;
    }

	/**
     * @param \ContentinumComponents\Images\unknown $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

	/**
     * Get the new images size as an html string
     * for implement in a img tag
     *
     * @return string html attribute string
     */
    public function getHtmlString()
    {
        // calculate new size
        $this->calc();
        if (empty($this->newSize)) {
            throw new ErrorLogicImagesException('Calculate faild, no values to resize images');
        }
        return "width=\"{$this->newSize['width']}\" height=\"{$this->newSize['height']}\"";
    }

    /**
     * Calculate the new images size
     * this script will work dynamically with any size image
     */
    protected function calc()
    {
        if (! $this->target) {
            throw new ErrorLogicImagesException('You must define a target to resize a images !');
        }
        if (! $this->width || ! $this->height) {
            $this->fileSize();
            // throw new Libary_Images_Exception('You must take a images width and height !');
        }
        // render values
        $target = $this->target;
        $width = $this->width;
        $height = $this->height;
        // if target bigger than width and height ...
        if ($target > $height && $target > $width) {
            $this->newSize['width'] = $width;
            $this->newSize['height'] = $height;
            return true; // .. make no sence to resize
        }
        /**
         * takes the larger size of the width and height and
         * applies the formula accordingly...this is so
         * this script will work dynamically with any size image
         */
        if ($width > $height) {
            $this->format = 'landscape';
            $percentage = ($target / $width);
        } else {
            $this->format = 'portrait';
            $percentage = ($target / $height);
        }
        // gets the new value and applies the percentage, then rounds the value
        $this->newSize['width'] = round($width * $percentage);
        $this->newSize['height'] = round($height * $percentage);
    }

    /**
     * Take images dimension file path is neasaccery
     */
    protected function fileSize()
    {
        if (! $this->file) {
            $str = 'You must take a images width and height or ';
            $str .= 'give a file path to get the images dimensions';
            throw new ErrorLogicImagesException($str);
        }
        // get imges dimension
        $info = getimagesize($this->file);
        // set width and ...
        if (isset($info[0])) {
            $this->width = $info[0];
        }
        // ... height
        if (isset($info[1])) {
            $this->height = $info[1];
        }
    }
}