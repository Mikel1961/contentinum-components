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
 * @package Forms
 * @author Michael Jochum, michael.jochum@jochum-mediaservices.de
 * @copyright Copyright (c) 2009-2013 jochum-mediaservices, Katja Jochum (http://www.jochum-mediaservices.de)
 * @license http://www.opensource.org/licenses/bsd-license
 * @since contentinum version 5.0
 * @link      https://github.com/Mikel1961/contentinum-components
 * @version   1.0.0
 */
namespace ContentinumComponents\Forms\Elements;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\Explode as ExplodeValidator;
use Zend\Validator\Regex as RegexValidator;
use Zend\Validator\ValidatorInterface;

class Tel  extends Element implements InputProviderInterface
{
    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = array(
        'type' => 'tel',
    ); 

    /**
     * @var ValidatorInterface
     */
    protected $validator;
    
    /**
     * @var ValidatorInterface
     */
    protected $telValidator;
    
    /**
     * Get primary validator
     *
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        if (null === $this->validator) {
            $telValidator = $this->getTelValidator();
    
            $multiple = (isset($this->attributes['multiple']))
            ? $this->attributes['multiple'] : null;
    
            if (true === $multiple || 'multiple' === $multiple) {
                $this->validator = new ExplodeValidator(array(
                    'validator' => $telValidator,
                ));
            } else {
                $this->validator = $telValidator;
            }
        }
    
        return $this->validator;
    }
    
    /**
     * Sets the primary validator to use for this element
     *
     * @param  ValidatorInterface $validator
     * @return Email
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        return $this;
    }
    
    /**
     * Get the tel validator to use for multiple or single
     * tel addresses.
     *
     * Note from the HTML5 Specs regarding the regex:
     *
     * "This requirement is a *willful* violation of RFC 5322, which
     * defines a syntax for e-mail addresses that is simultaneously
     * too strict (before the "@" character), too vague
     * (after the "@" character), and too lax (allowing comments,
     * whitespace characters, and quoted strings in manners
     * unfamiliar to most users) to be of practical use here."
     *
     * The default Regex validator is in use to match that of the
     * browser validation, but you are free to set a different
     * (more strict) tel validator such as Zend\Validator\Email
     * if you wish.
     *
     * @return ValidatorInterface
     */
    public function getTelValidator()
    {
        if (null === $this->telValidator) {
            
            $Land = '((\+[0-9]{2,4}([ -][0-9]+?[ -]| ?\([0-9]+?\) ?))';
            $Ort = '|(\(0[0-9 ]+?\) ?)|(0[0-9]+? ?( |-|\/) ?))';
            $Nr = '([0-9]+?[ \/-]?)+?[0-9]';
            $regEx = '/^'.$Land.$Ort.$Nr.'$/';         
            
            
            $this->telValidator = new RegexValidator(
                $regEx
            );
        }
        return $this->telValidator;
    }
    
    /**
     * Sets the tel validator to use for multiple or single
     * tel addresses.
     *
     * @param  ValidatorInterface $validator
     * @return Email
     */
    public function setEmailValidator(ValidatorInterface $validator)
    {
        $this->telValidator = $validator;
        return $this;
    }    
    
	/**
	 *  (non-PHPdoc)
     * @see \Zend\InputFilter\InputProviderInterface::getInputSpecification()
     */
    public function getInputSpecification()
    {
        return array(
            'name' => $this->getName(),
            'required' => true,
            'filters' => array(
                array('name' => 'Zend\Filter\StringTrim'),
            ),
            'validators' => array(
                $this->getValidator(),
            ),
        );
        
    }
}