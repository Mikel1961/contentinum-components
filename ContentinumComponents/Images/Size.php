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

/**
 * Get/set html images tag attribute
 *
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *
 */
class Size
{
	/**
	 * Path to image file
	 *
	 * @var string
	 */
	protected $src = null;
	/**
	 * Image dimension width
	 *
	 * @var int
	 */
	protected $width = null;
	/**
	 * Image dimension height
	 *
	 * @var int
	 */
	protected $height = null;
	/**
	 * Image type
	 *
	 * @var string
	 */
	protected $type = null;
	/**
	 * Further image attributes
	 *
	 * @var string
	 */
	protected $attr = null;
	/**
	 * Construct
	 *
	 * @param string $src path to images file
	 */
	public function __construct ($src)
	{
		$this->src = $src;
	}
	/**
	 * Get image size and infos
	 *
	 */
	public function imgSize ($src = null)
	{
		if (null !== $src){
		    $this->src = $src;
		}
		// get image file infos
		list ($width, $height, $type, $attr) = @getimagesize($this->src);
		// Set attributes
		$this->width = $width;
		$this->height = $height;
		$this->type = $type;
		$this->attr = $attr;
	}
	/**
	 * Get image attributes
	 *
	 * @return mixed
	 */
	public function getAttribute ()
	{
		return $this->attr;
	}
	/**
	 * Get image type
	 *
	 * @return string
	 */
	public function getType ()
	{
		return $this->type;
	}
	/**
	 * Get image width
	 *
	 * @return int image width
	 */
	public function getWidth ()
	{
		return $this->width;
	}
	/**
	 * Get image height
	 *
	 * @return int image height
	 */
	public function getHeight ()
	{
		return $this->height;
	}
	
	/**
	 * Get properties as array
	 * @return multitype:
	 */
	public function getProperties()
	{
		return get_object_vars($this);
	}	
}