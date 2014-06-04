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
 * @since contentinum version 3.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Images;

use ContentinumComponents\Images\Exeption\ErrorLogicImagesException;
use ContentinumComponents\File\Extension;
use ContentinumComponents\File\Name;

/**
 * Calculate size and resize a image
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *
 */
class Resize
{
    /**
     * The new images size
     *
     * @var int
     */
    protected $target = null;
    /**
     * The original file with path
     *
     * @var string
     */
    protected $orgFile = null;
    /**
     * The filename
     *
     * @var string
     */
    protected $fileName = null;
    /**
     * The directory it will be save the new images file
     *
     * @var string
     */
    protected $dir = null;
    /**
     * image quality
     * @var int
     */
    protected $imagequality = 100;
    /**
     * The process images
     *
     * @var string
     */
    protected $processImg = null;    
    
    /**
     * Construct
     *
     * @param int $target the new images size
     * @param string $orgFile the original file with path
     * @param string $fileName the filename
     * @param string $dir directory it will be save the new images file
     */
    public function __construct ($target, $orgFile, $fileName, $dir)
    {
    	$this->target = $target;
    	$this->orgFile = $orgFile;
    	$this->fileName = $fileName;
    	$this->dir = $dir;
    }
	/**
	 * @return the $target
	 */
	public function getTarget() 
	{
		return $this->target;
	}

	/**
	 * @param number $target
	 * @param Resize
	 */
	public function setTarget($target) 
	{
		$this->target = $target;
		
		return $this;
	}

	/**
	 * @return the $orgFile
	 */
	public function getOrgFile() 
	{
		return $this->orgFile;
	}

	/**
	 * @param string $orgFile
	 * @param Resize
	 */
	public function setOrgFile($orgFile) 
	{
		$this->orgFile = $orgFile;
		
		return $this;
	}

	/**
	 * @return the $fileName
	 */
	public function getFileName() 
	{
		return $this->fileName;
	}

	/**
	 * @param string $fileName
	 * @param Resize
	 */
	public function setFileName($fileName) 
	{
		$this->fileName = $fileName;
		
		return $this;
	}

	/**
	 * @return the $dir
	 */
	public function getDir() 
	{
		return $this->dir;
	}

	/**
	 * @param string $dir
	 * @param Resize
	 */
	public function setDir($dir) 
	{
		$this->dir = $dir;
		
		return $this;
	}

	/**
	 * @return the $imagequality
	 */
	public function getImagequality() 
	{
		return $this->imagequality;
	}

	/**
	 * @param number $imagequality
	 * @param Resize   
	 */
	public function setImagequality($imagequality) 
	{
		$this->imagequality = (int) $imagequality;
		
		return $this;
	}

	/**
	 * @return the $processImg
	 */
	public function getProcessImg() 
	{
		return $this->processImg;
	}

	/**
	 * @param string $processImg
	 * @param Resize
	 */
	public function setProcessImg($processImg) 
	{
		$this->processImg = $processImg;
		
		return $this;
	}
	
	/**
	 * Run the resize process
	 *
	 */
	public function execute ()
	{
		// Get file type
		$ext = Extension::get($this->fileName);
		// Check is a images
		$this->fileType($ext);
		// Get actual images size ...
		list ($width, $height) = getimagesize($this->orgFile);
		// ... and check make a resize sence
		$this->isResizeble($width, $height);
		// Calculate and resize the new images
		return $this->resizeImages($width, $height, $ext);
	}
	
	/**
	 * Calculate the new images size.
	 * Resize the images and save the new images.
	 *
	 * @param int $width  actual images width
	 * @param int $height actual images height
	 * @return boolen
	 */
	protected function resizeImages ($width, $height, $ext)
	{
		// Get the new images size
		$newSize = $this->calculate($width, $height);
		// Create a images with the new dimensions
		$newImage = @imagecreatetruecolor($newSize['width'], $newSize['height']);
		// copy exists images by resampled, is callable ...
		if (is_callable("imagecopyresampled")) {
			imagecopyresampled($newImage, $this->processImg, 0, 0, 0, 0, $newSize['width'], $newSize['height'], $width, $height);
		} else {
			// ... if resize you lose quality
			imagecopyresized($newImage, $this->processImg, 0, 0, 0, 0, $newSize['width'], $newSize['height'], $width, $height);
		}
		$fileName = Name::get($this->fileName);
		$fileName = strtolower($fileName) . '_' . $this->target;
		// save the resize images
		switch ($ext) {
			case "JPG":
			case "jpg":
			case "jpeg":
				imagejpeg($newImage, $this->dir . $fileName . '.jpg', $this->imagequality); // ... no loss of quality
				break;
			case "PNG":
			case "png":
				imagepng($newImage, $this->dir . $fileName . '.png');
				break;
			case "GIF":
			case "gif":
				imagegif($newImage, $this->dir . $fileName . '.gif');
				break;
			default:
				throw new ErrorLogicImagesException('Can not save the resized images !');
				break;
		} // switch
		return true;
	}
	
	/**
	 * new quality
	 * @return string
	 */
	public function execImageQuality ()
	{
		// Get file type
		$ext = Extension::get($this->fileName);
		// Check is a images
		$this->fileType($ext);
		// Get actual images size ...
		list ($width, $height) = getimagesize($this->orgFile);
		// Create a images with the new dimensions
		$newImage = @imagecreatetruecolor($width, $height);
		// copy exists images by resampled, is callable ...
		if (is_callable("imagecopyresampled")) {
			imagecopyresampled($newImage, $this->processImg, 0, 0, 0, 0, $width, $height, $width, $height);
		} else {
			// ... if resize you lose quality
			imagecopyresized($newImage, $this->processImg, 0, 0, 0, 0, $width, $height, $width, $height);
		}
		$fileName = Name::get($this->fileName);
		$fileName = strtolower($fileName) . '-' . $this->target;
		// save the resize images
		switch ($ext) {
			case "JPG":
			case "jpg":
			case "jpeg":
				imagejpeg($newImage, $this->dir . $fileName . '.jpg', $this->imagequality); // ... no loss of quality
				break;
			case "PNG":
			case "png":
				imagepng($newImage, $this->dir . $fileName . '.png');
				break;
			case "GIF":
			case "gif":
				imagegif($newImage, $this->dir . $fileName . '.gif');
				break;
			default:
				throw new ErrorLogicImagesException('Can not save the images in the new quality !');
				break;
		} // switch
		return true;
	}
	
	/**
	 * Calculate the new images size
	 * @todo resolve dependency
	 *
	 * @uses  Contentinum_Images_Calculate
	 * @param int $width  actual images width
	 * @param int $height actual images height
	 * @return array ['width'], ['height']
	 */
	protected function calculate ($width, $height)
	{
		$newCalc = new CalculateResize($this->target,$width,$height);
		return $newCalc->getNewSize();
	}
	
	/**
	 * Make the target sence to resize the images
	 *
	 * @param int $width  actual images width
	 * @param int $height actual images height
	 */
	protected function isResizeble ($width, $height)
	{
		if ($width < $this->target && $height < $this->target) {
			throw new ErrorLogicImagesException("This target to resize the images make no sence");
		}
	}
	
	/**
	 * Get the file type (is it a images)
	 * and save a process images
	 *
	 */
	protected function fileType ($ext)
	{
		// Is it a images we can process ...
		switch ($ext) {
			case 'JPG':
			case 'jpg':
			case 'jpeg':
				$this->processImg = @imagecreatefromjpeg($this->orgFile);
				break;
			case "png":
				$this->processImg = @imagecreatefrompng($this->orgFile);
				break;
			case "gif":
				if (is_callable("imagegif")) { // gif resize ??
					$this->processImg = @imagecreatefromgif($this->orgFile);
					break;
				} else {
					// the gif format can not be processed on this server
					throw new ErrorLogicImagesException("The gif format can't be processed on this server !");
				}
			default:
				// ... otherwise this format can not be processed
				throw new ErrorLogicImagesException("This format can not be processed or it's not a images !");
		}
	}	
    
}