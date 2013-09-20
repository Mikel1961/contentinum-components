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
namespace ContentinumComponents\Storage;


use ContentinumComponents\Entity\AbstractEntity;

/**
 * Directory entity as a warpper for DirectoryIterator
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *
 */
abstract class AbstractStorageEntity extends AbstractEntity
{
    /**
     * File name
     * @var string
     */
	protected $filename;
	
	/**
	 * File extension
	 * @var string
	 */
	protected $extension;
	
	/**
	 * Image width
	 * @var int
	 */
	protected $width;
	
	/**
	 * Image height
	 * @var int
	 */
	protected $height;
	
	/**
	 * Directory items
	 * @var string
	 */
	protected $items;
	
	/**
	 * Path of current Iterator item without filename
	 * @var string
	 */
	protected $path;
	
	/**
	 * Path and file name of current item
	 * @var unknown
	 */
	protected $pathname;
	
    /**
     * Determine the type of the current item
     * @var string
     */
	protected $type;
	
	/**
	 * File size
	 * @var string
	 */
	protected $size;
	
	/**
	 * Last modification time
	 * @var string
	 */
	protected $time;
	
	/**
	 * Permissions of current item
	 * @var string
	 */
	protected $perms;
	
	/**
	 * Determine if current item can be read
	 * @var Boolean
	 */
	protected $readable;
	
	/**
	 * Determine if current item can be write
	 * @var Boolean
	 */
	protected $writable;
	
	/**
	 * Determine if current item is executable
	 * @var unknown
	 */
	protected $executable;	
	
	
	/**
	 * Construct
	 * @param array $options
	 */
	public function __construct (array $options = null)
	{
		if (is_array($options)) {
			$this->setOptions($options);
		}
	
	}	
	
	/**
	 * @see \ContentinumComponents\Entity\AbstractEntity::getEntityName()
	 */
	public function getEntityName() 
	{
		return get_class($this);		
	}

	/**
	 * @see \ContentinumComponents\Entity\AbstractEntity::getPrimaryKey()
	 */
	public function getPrimaryKey() 
	{
		return 'filename';
		
	}

	/**
	 * @see \ContentinumComponents\Entity\AbstractEntity::getPrimaryValue()
	 */
	public function getPrimaryValue() 
	{
		return $this->filename;
		
	}

	/**
	 * @see \ContentinumComponents\Entity\AbstractEntity::getProperties()
	 */
	public function getProperties() 
	{
		return get_object_vars($this);
		
	}
	
	/**
	 * Get path of current file storage
	 */
	abstract public function getCurrentPath();
	
	/**
	 * @return the $filename
	 */
	public function getFilename() 
	{
		return $this->filename;
	}

	/**
	 * @param string $filename
	 */
	public function setFilename($filename) 
	{
		$this->filename = $filename;
		return $this;
	}

	/**
	 * @return the $extension
	 */
	public function getExtension() 
	{
		return $this->extension;
	}

	/**
	 * @param string $extension
	 */
	public function setExtension($extension) 
	{
		$this->extension = $extension;
		return $this;
	}

	/**
	 * @return the $width
	 */
	public function getWidth() 
	{
		return $this->width;
	}

	/**
	 * @param number $width
	 */
	public function setWidth($width) 
	{
		$this->width = $width;
		return $this;
	}

	/**
	 * @return the $height
	 */
	public function getHeight() 
	{
		return $this->height;
	}

	/**
	 * @param number $height
	 */
	public function setHeight($height) 
	{
		$this->height = $height;
		return $this;
	}

	/**
	 * @return the $items
	 */
	public function getItems() 
	{
		return $this->items;
	}

	/**
	 * @param string $items
	 */
	public function setItems($items) 
	{
		$this->items = $items;
		return $this;
	}

	/**
	 * @return the $path
	 */
	public function getPath() 
	{
		return $this->path;
	}

	/**
	 * @param string $path
	 */
	public function setPath($path) 
	{
		$this->path = $path;
		return $this;
	}

	/**
	 * @return the $pathname
	 */
	public function getPathname() 
	{
		return $this->pathname;
	}

	/**
	 * @param string $pathname
	 */
	public function setPathname($pathname) 
	{
		$this->pathname = $pathname;
		return $this;
	}

	/**
	 * @return the $type
	 */
	public function getType() 
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType($type) 
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @return the $size
	 */
	public function getSize() 
	{
		return $this->size;
	}

	/**
	 * @param string $size
	 */
	public function setSize($size) 
	{
		$this->size = $size;
		return $this;
	}

	/**
	 * @return the $time
	 */
	public function getTime() 
	{
		return $this->time;
	}

	/**
	 * @param string $time
	 */
	public function setTime($time) 
	{
		$this->time = $time;
		return $this;
	}

	/**
	 * @return the $perms
	 */
	public function getPerms() 
	{
		return $this->perms;
	}

	/**
	 * @param string $perms
	 */
	public function setPerms($perms) 
	{
		$this->perms = $perms;
		return $this;
	}

	/**
	 * @return the $readable
	 */
	public function getReadable() 
	{
		return $this->readable;
	}

	/**
	 * @param boolean $readable
	 */
	public function setReadable($readable) 
	{
		$this->readable = $readable;
		return $this;
	}

	/**
	 * @return the $writable
	 */
	public function getWritable() 
	{
		return $this->writable;
	}

	/**
	 * @param boolean $writable
	 */
	public function setWritable($writable) 
	{
		$this->writable = $writable;
		return $this;
	}

	/**
	 * @return the $executable
	 */
	public function getExecutable() 
	{
		return $this->executable;
	}

	/**
	 * @param boolen $executable
	 */
	public function setExecutable($executable) 
	{
		$this->executable = $executable;
		return $this;
	}

}