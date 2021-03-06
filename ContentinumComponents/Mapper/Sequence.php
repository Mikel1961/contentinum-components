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
namespace ContentinumComponents\Mapper;

use ContentinumComponents\Mapper\Rang\Calculate;

/**
 * Calculate and update the new sequence of data records
 * 
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 *
 */
class Sequence extends Worker
{
    /**
     * Column name sequence numbers
     * @var string
     */
    protected $colSequence = 'itemRang';
    
    /**
     * @return the $colSequence
     */
    public function getColSequence()
    {
        return $this->colSequence;
    }

	/**
     * @param string $colSequence
     */
    public function setColSequence($colSequence)
    {
        $this->colSequence = $colSequence;
    }

	/**
     * Prepare and update item rangs 
     * @param Contentinum_Model_Abstract $entity Contentinum_Model_Abstract
     * @param $datas new item rangs
     * @return boolen
     */
    public function updaterang ($entity, $datas)
    {
    	// update the rows
    	foreach ($datas as $row) {
    		$update = array($this->colSequence => $row[ $this->colSequence ]);
    		parent::save( $update, $this->find($row['id']) , self::SAVE_UPDATE, $row['id']);
    	}
    	// sucessfully
    	return true;
    }    
       
    /**
     * Get item rangs
     * @param string $key
     * @param mixed $value
     * @return array
     */
    public function fetchItemRangs ($key = null, $value = null, $entity = null)
    {
    	/**
         *
         * @var EntityManager
         */
    	$db = $this->getStorage();
    	$qb = $db->createQueryBuilder();
    	$qb->add('select', 'main.id, main.' . $this->colSequence);
    	$qb->add('from', $this->getEntityName() . ' AS main');  
    	if ($key && $value) {
    		$qb->add('where', 'main.' . $key . ' = ?1')->setParameter(1, $value);
    	}
    	$qb->add('orderBy','main.'. $this->colSequence . ' ASC');
    	$query = $qb->getQuery();
    	return $query->getResult();   	    	  	
    }

    /**
     * Sort and update item rangs
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    public function sortItemRang ($key = null, $value = null)
    {
  	
        $moveRang = Calculate::itemsort($this->fetchItemRangs( $key, $value, $this->getEntityName()),$this->colSequence);
    	// update the rows
    	try {
    		$this->updaterang($this->getEntityName(),$moveRang); 		
    		if (true === $this->hasLogger()) {
    			$this->logger->info($this->getEntityName() . ' data records sorted successfully');
    		}
    	} catch (\Exception $e) {
    	    if (true === $this->hasLogger()) {
    			$this->logger->crit($this->getEntityName()  . ' error data records sorted ' . $e->getMessage());
    		}  
    		return false;  		
    	}
    	// sucessfully
    	return true;
    }

    /**
     * Move a data row up or down step by step
     * @param int $id
     * @param string $task
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    public function itemmoverang ($id, $task = null, $key = null, $value = null)
    { 	
        // get all contributes from this apge
    	$rowArray = $this->fetchItemRangs($key, $value, $this->getEntityName());
    	// no result or not found
    	if (! $rowArray) {
    		return false;
    	}
    	// calculate and set the move
    	$calc = new Calculate($rowArray, $id);
    	$calc->setTask($task);
    	$moveRang = $calc->itemrang($this->colSequence);
    	// update the rows
    	try {
    		$this->updaterang($this->getEntityName(),$moveRang);   		
    		if (true === $this->hasLogger()) {
    			$this->logger->info($this->getEntityName() . ' record moved to new realm sequence successfully');
    		}    		
    	} catch (\Exception $e) {
    		if (true === $this->hasLogger()) {
    			$this->logger->crit($this->getEntityName()  . ' error item move rang ' . $e->getMessage());
    		}    		
    		return false;
    	}
    	// sucessfully
    	return true;
    }
    /**
     * Move a item over more than on steps
     * @param int $fieldId
     * @param int $changeRang
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    public function itemjumprang ($idToChange, $changeRang, $key = null, $value = null)
    {  	
    	$itemRangs = $this->fetchItemRangs($key, $value);
    	$task = null;
    	foreach ($itemRangs as $row) {
    		if ($row['id'] == $idToChange) {
    			if ($row[$this->colSequence] > $changeRang) {
    				$task = 'moveup';
    			}
    			if ($row[$this->colSequence] < $changeRang) {
    				$task = 'movedown';
    			}
    			break;
    		}
    	}
    	switch ($task) {
    		case 'moveup':
    			$changeRangs = Calculate::moveup($itemRangs, $idToChange, $changeRang, $this->colSequence);
    			break;
    		case 'movedown':
    			$changeRangs = Calculate::movedown($itemRangs, $idToChange, $changeRang, $this->colSequence);
    			break;
    		default:
    	}
    	// update the rows
    	try {
    		$this->updaterang($this->getEntityName(), $changeRangs);
    		if (true === $this->hasLogger()) {
    			$this->logger->info($this->getEntityName() . ' record jump to a new realm sequence successfully');
    		}    		
    	} catch (\Exception $e) {
    		if (true === $this->hasLogger()) {
    			$this->logger->crit($this->getEntityName() . ' error item jump rang ' . $e->getMessage());
    		}    		
    		return false;
    	}
    	// sucessfully
    	return true;
    }
    
    /**
     * Fetch all rangs from a table column
     * @param string $key
     * @param mixed $value
     * @return Ambigous <string, array>
     */
    public function fetchRangForSelect ($key = null, $value = null)
    {
    	$data = $this->fetchItemRangs($key, $value);
    
    	$options[0] = '- 0 -';
    	if ($data) {
    		foreach ($data as $row) {
    			$options[$row[$this->colSequence]] = $row[$this->colSequence];
    		}
    	}
    	return $options;
    }  
}