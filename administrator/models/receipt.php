<?php

/**
 * @version     1.0.0
 * @package     com_fee
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Linh <mr.lynk92@gmail.com> - http://
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Fee model.
 */
class FeeModelReceipt extends JModelAdmin {

    /**
     * @var		string	The prefix to use with controller messages.
     * @since	1.6
     */
    protected $text_prefix = 'COM_FEE';
    protected $_tbl = "`#__fee_receipt`";
    public $typeAlias = 'com_fee.receipt';

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'Receipt', $prefix = 'FeeTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param	array	$data		An optional array of data for the form to interogate.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	JForm	A JForm object on success, false on failure
     * @since	1.6
     */
    public function getForm($data = array(), $loadData = true) {
        // Initialise variables.
        $app = JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm('com_fee.receipt', 'receipt', array('control' => 'jform', 'load_data' => $loadData));


        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData() {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_fee.edit.receipt.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Method to get a single record.
     *
     * @param	integer	The id of the primary key.
     *
     * @return	mixed	Object on success, false on failure.
     * @since	1.6
     */
    public function getItem($pk = null) {
        if ($item = parent::getItem($pk)) {

            //Do any procesing on fields here if needed
            if (!$item->title) {
                $item->title = $this->getReceiptNext();
            }
            if (!$item->date) {
                $item->date = date_format(JFactory::getDate(), 'Y-m-d');
            }
        }

        return $item;
    }

    public function getItemPrint($pk = null) {
        if ($item = parent::getItem($pk)) {

            //get student info
            if ($item->student_alias) {
                $db = JFactory::getDbo();

                $query = $db->getQuery(true);

                $query
                        ->select(array(
                            '`#__fee_student`.`title` AS title', '`#__fee_student`.`student_id`'
                        ))
                        ->from('`#__fee_student`')
                        //join department
                        ->select('`#__fee_department`.`title` AS department')
                        ->join('LEFT', '`#__fee_department` ON `#__fee_student`.`department_alias` = `#__fee_department`.`alias`  ')
                        //join course
                        ->select('`#__fee_course`.`title` AS course')
                        ->join('LEFT', '`#__fee_course` ON `#__fee_student`.`course_alias` = `#__fee_course`.`alias`  ')
                        ->where('`#__fee_student`.`alias` = ' . $db->quote($item->student_alias));

                $db->setQuery($query);

                $student = $db->loadObject();
                if (!$student) {
                    $this->setError(JText::_("COM_FEE_ERROR_STUDENT_DO_NOT_EXITS"));
                    return false;
                }
                $item->student_name = $student->title;
                $item->student_id = $student->student_id;
                $item->department = $student->department;
                $item->course = $student->course;
            }

            //get semester to roman
            if ($item->semester_alias) {
                $db = JFactory::getDbo();

                $query = $db->getQuery(true);

                $query
                        ->select('`title`')
                        ->from('`#__fee_semester`')
                        ->where('`alias` = ' . $db->quote($item->semester_alias));

                $db->setQuery($query);

                $semester_title = $db->loadResult();

                if (!$semester_title) {
                    $this->setError(JText::_("COM_FEE_ERROR_SEMESTER_DO_NOT_EXITS"));
                    return false;
                }
                $item->semester_title = ((int) $semester_title > 0) ? $this->number2roman((int) $semester_title) : $semester_title;
            }
            //get year 
            if ($item->year_alias) {
                $db = JFactory::getDbo();

                $query = $db->getQuery(true);

                $query
                        ->select("CONCAT(CAST(`start` AS CHAR), ' - ',CAST(`end` AS CHAR)) AS start")
                        ->from('`#__fee_year`')
                        ->where('`alias` = ' . $db->quote($item->year_alias));

                $db->setQuery($query);

                $year = $db->loadResult();


                if (!$year) {
                    $this->setError(JText::_("COM_FEE_ERROR_YEAR_DO_NOT_EXITS"));
                    return false;
                }
                $item->year_title = $year;
            }

            $date = new DateTime($item->date);
            $item->day = date_format($date, 'd');
            $item->month = date_format($date, 'm');
            $item->year = date_format($date, 'Y');

            if (!$this->convert_number_to_words((int) $item->paid)) {
                $this->setError(JText::_("COM_FEE_ERROR_CONVER_NUMBER_TO_ROMAN"));
                return false;
            }
            $item->paidString = $this->convert_number_to_words((int) $item->paid);
        }
        return $item;
    }

    public function convert_number_to_words($number) {

        $hyphen = ' ';
        $conjunction = '  ';
        $separator = ' ';
        $negative = 'âm ';
        $decimal = ' phẩy ';
        $dictionary = array(
            0 => 'Không',
            1 => 'Một',
            2 => 'Hai',
            3 => 'Ba',
            4 => 'Bốn',
            5 => 'Năm',
            6 => 'Sáu',
            7 => 'Bảy',
            8 => 'Tám',
            9 => 'Chín',
            10 => 'Mười',
            11 => 'Mười một',
            12 => 'Mười hai',
            13 => 'Mười ba',
            14 => 'Mười bốn',
            15 => 'Mười năm',
            16 => 'Mười sáu',
            17 => 'Mười bảy',
            18 => 'Mười tám',
            19 => 'Mười chín',
            20 => 'Hai mươi',
            30 => 'Ba mươi',
            40 => 'Bốn mươi',
            50 => 'Năm mươi',
            60 => 'Sáu mươi',
            70 => 'Bảy mươi',
            80 => 'Tám mươi',
            90 => 'Chín mươi',
            100 => 'trăm',
            1000 => 'ngàn',
            1000000 => 'triệu',
            1000000000 => 'tỷ',
            1000000000000 => 'nghìn tỷ',
            1000000000000000 => 'ngàn triệu triệu',
            1000000000000000000 => 'tỷ tỷ'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
// overflow
            trigger_error(
                    'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . $this->convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    public function roman2number($roman) {
        $conv = array(
            array("letter" => 'I', "number" =>
                1),
            array("letter" => 'V', "number" => 5),
            array("letter" => 'X', "number" => 10),
            array("letter" => 'L', "number" => 50),
            array("letter" => 'C', "number" => 100),
            array("letter" => 'D', "number" => 500),
            array("letter" => 'M', "number" => 1000),
            array("letter" => 0, "number" => 0)
        );
        $arabic = 0;
        $state = 0;
        $sidx = 0;
        $len = strlen($roman);

        while ($len >= 0) {
            $i = 0;
            $sidx = $len;

            while ($conv[$i]['number'] > 0) {
                if (strtoupper(@$roman[$sidx]) == $conv[$i]['letter']) {
                    if ($state > $conv[$i]['number']) {
                        $arabic -= $conv[$i]['number'];
                    } else {
                        $arabic += $conv[$i]['number'];
                        $state = $conv[$i]['number'];
                    }
                }
                $i++;
            }

            $len--;
        }

        return ( $arabic);
    }

    public function number2roman($num, $isUpper = true) {
        $n = intval($num);
        $res = '';

        /*         * * roman_numerals array ** */
        $roman_numerals = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        );

        foreach ($roman_numerals as $roman => $number) {
            /*             * * divide to get matches ** */
            $matches = intval($n / $number);

            /*             * * assign the roman char * $matches ** */
            $res .= str_repeat($roman, $matches);

            /*             * * substract from the number ** */
            $n = $n % $number;
        }

        /*         * * return the res ** */
        if ($isUpper)
            return $res;
        else
            return strtolower($res);
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @since	1.6
     */
    protected function prepareTable($table) {
        jimport('joomla.filter.output');

        if (empty($table->id)) {

            // Set ordering to the last item if not set
            if (@$table->ordering === '') {
                $db = JFactory::getDbo();
                $db->setQuery('SELECT MAX(ordering) FROM #__fee_receipt');
                $max = $db->loadResult();
                $table->ordering = $max + 1;
            }
        }
    }

    protected function getReceiptNext() {
        $year = date_format(JFactory::getDate(), 'Y');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
                ->select('MAX(`title`)')
                ->from($this->_tbl)
                ->where("DATE_FORMAT(`date`,'%Y') = DATE_FORMAT(CURRENT_DATE,'%Y')");

        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result) {
            return $result + 1;
        } else {
            return 1;
        }
    }

}
