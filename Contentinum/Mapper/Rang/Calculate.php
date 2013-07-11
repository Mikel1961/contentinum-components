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
 * @package Mapper
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 4.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace Contentinum\Mapper\Rang;

/**
 * Calculate and set the new sequence in a array before update database table
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *
 */
class Calculate
{
    /**
     * Item id
     * @var int
     */
    protected $_id = null;
    
    /**
     * Column name to sort table rows
     * @var unknown
     */
    protected $_sortField = 'item_rang';
    /**
     * Array of item rang and ids
     * @var array
     */
    protected $_rowArrays = array();
    /**
     * Task item move up or move down
     * @var string
    */
    protected $_task = null;
    /**
     * Construct
     * @param $rowArrays array
     * @param $id int
     * @return void
     */
    public function __construct ($rowArrays, $id)
    {
    	$this->_id = $id;
    	$this->_rowArrays = $rowArrays;
    }
    /**
     * @param $task string the $task to set
     */
    public function setTask ($task)
    {
    	$this->_task = $task;
    }
    /**
     * @param $rowArrays array the $rowArrays to set
     */
    public function setRowArrays ($rowArrays)
    {
    	$this->_rowArrays = $rowArrays;
    }
    /**
     * @param $id int the $id to set
     */
    public function setId ($id)
    {
    	$this->_id = $id;
    }
    /**
     * @return string the $_task
     */
    public function getTask ()
    {
    	return $this->_task;
    }
    /**
     * @return array the $_rowArrays
     */
    public function getRowArrays ()
    {
    	return $this->_rowArrays;
    }
    /**
     * @return int the $_id
     */
    public function getId ()
    {
    	return $this->_id;
    }
    /**
     * Calculate a new sequence
     * sort item rangs
     *
     * @param int $cid current move item
     * @param array $rowArray array of data rows with row id and item rang
     * @return array $moveRang return of new sort item rang
     */
    public function itemrang ()
    {
    	$i = 1;
    	$rowArray = $this->_rowArrays;
    	$cid = $this->_id;
    	$itemRang = array();
    	// put in a array
    	foreach ($rowArray as $row) {
    		if ($row['id'] == $cid) {
    			$itemRang[$i]['id'] = $row['id'];
    			$itemRang[$i][$this->_sortField] = $row[$this->_sortField];
    			$itemRang[$i]['move'] = $this->_task;
    			$moveKey = $i;
    		} else {
    			$itemRang[$i]['id'] = $row['id'];
    			$itemRang[$i][$this->_sortField] = $row[$this->_sortField];
    		}
    		$i ++;
    	}
    	$tmp = $moveKey;
    	// get the direction to move down or up
    	switch ($this->_task) {
    		case 'itemmovedown':
    			$changeKey = $tmp + 1;
    			$itemRang[$moveKey][$this->_sortField] = $itemRang[$moveKey][$this->_sortField] + 1;
    			$itemRang[$changeKey][$this->_sortField] = $itemRang[$changeKey][$this->_sortField] - 1;
    			break;
    		default:
    			$changeKey = $tmp - 1;
    			$itemRang[$moveKey][$this->_sortField] = $itemRang[$moveKey][$this->_sortField] - 1;
    			$itemRang[$changeKey][$this->_sortField] = $itemRang[$changeKey][$this->_sortField] + 1;
    	}
    	return $itemRang;
    }
    /**
     *
     * @param $itemRangs
     * @param $idToChange
     * @param $changeRang
     * @return unknown_type
     */
    public static function movedown ($itemRangs, $idToChange, $changeRang)
    {
    	$changeRangs = $tmp = array();
    	$i = 1;
    	foreach ($itemRangs as $k => $row) {
    		$render = $i;
    		if ($row['id'] == $idToChange) {
    			$oldRang = $row[$this->_sortField];
    			$row[$this->_sortField] = $changeRang;
    			$render = 'tmp';
    		}
    		$tmp[$render] = $row;
    		$i ++;
    	}
    	foreach ($tmp as $k => $row) {
    		if ($k != 'tmp') {
    			if ($row[$this->_sortField] >= $oldRang && $row[$this->_sortField] <= $changeRang) {
    				$row[$this->_sortField] = $row[$this->_sortField] - 1;
    			}
    			$changeRangs[$row[$this->_sortField]] = $row;
    		}
    	}
    	$changeRangs[$changeRang] = $tmp['tmp'];
    	ksort($changeRangs);
    	return $changeRangs;
    }
    /**
     *
     * @param $itemRangs
     * @param $idToChange
     * @param $changeRang
     * @return unknown_type
     */
    public static function moveup ($itemRangs, $idToChange, $changeRang)
    {
    	$tmp = array();
    	foreach ($itemRangs as $row) {
    		if ($row[$this->_sortField] >= $changeRang) {
    			if ($row['id'] == $idToChange) {
    				$row[$this->_sortField] = $changeRang;
    			} else {
    				$row[$this->_sortField] = $row[$this->_sortField] + 1;
    			}
    		}
    		$tmp[$row[$this->_sortField]] = $row;
    	}
    	ksort($tmp);
    	$i = 1;
    	foreach ($tmp as $row) {
    		$row[$this->_sortField] = $i;
    		$i ++;
    		$changeRangs[] = $row;
    	}
    	return $changeRangs;
    }
    /**
     * Sort item rang after delete a data row
     *
     * @param array $rowArray array of data rows with row id and item rang
     * @return array $itemRang return of new sort item rang
     */
    public static function itemsort ($rowArray)
    {
    	$i = 1;
    	$itemRang = array();
    	// put in a array
    	foreach ($rowArray as $row) {
    		$itemRang[$i]['id'] = $row['id'];
    		$itemRang[$i][$this->_sortField] = $i;
    		$i ++;
    	}
    	return $itemRang;
    }    
}