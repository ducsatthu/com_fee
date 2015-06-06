<?php

/**
 * @version     1.0.0
 * @package     com_fee
 * @copyright   Bản quyền (C) 2015. Các quyền đều được bảo vệ.
 * @license     bản quyền mã nguồn mở GNU phiên bản 2
 * @author      Tran Xuan Duc <ductranxuan.29710@gmail.com> - http://facebook.com/ducsatthuttd
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Fee helper.
 */
class FeeHelperConvert {

    /**
     * convert number to words vietnamese 
     * 
     * @param type $number
     * @return boolean|int
     */
    public static function convert_number_to_words($number) {

        if (is_numeric($number)) {
            $string = '';
            if ($number < 0) {
                $number = abs($number);
                $string = 'Âm ';
            }
            $number = (string) $number;
            $cutN = self::cutStringnumber($number);
            for ($i = count($cutN) - 1; $i >= 0; $i--) {
                if ((int) $cutN[$i] !== 0) {
                    
                        $string .= self::dictionaryNumberVNAll($cutN[$i]) . self::dictionaryNumberVN($i);
                    if((int)$cutN[$i-1] < 100 && ($i+1)<=count($cutN) && ($i-1)!=0){
                        $string .= ' Không trăm ';
                    }
                }
            }
            return $string;
        }
        return 0;
    }

    /**
     * Cut number string to number array 
     * 
     * @param type $string
     * @return string
     */
    public static function cutStringnumber($string) {
        $level = 0;
        while ($len = self::getval($string)) {
            // get partial number from 0 to 999
            $string_partial = substr($string, (strlen($string) - $len));
            // get hundreds
            $hund = ($string_partial - ($string_partial % 100)) / 100;
            // get tens
            $tens = $string_partial - ($hund * 100);
            $tens = ($tens - ($tens % 10)) / 10;
            // get ones
            $ones = $string_partial - ($tens * 10) - ($hund * 100);
            // remove partial_string form original string             
            $string = substr($string, 0, (strlen($string) - $len));
            // edbug echoing
            // you need to create a function that convert number to text only from 0 to 999 to set correct million/thousand etc, use $level.
            //$text = getTextvalue($hund,$tens,$ones,$level).$text;
            //increment $level
            $arrayNumberCut[$level] = $hund . $tens . $ones;

            $level++;
        }
        return $arrayNumberCut;
    }

    /**
     * Get Lenght number to cut
     * 
     * @param type $n
     * @return boolean|int
     */
    public static function getval($n) {
        switch (strlen($n)) {
            case 0: return false;
            case 1: return 1;
            case 2: return 2;
            case 3: return 3;
            default: return 3;
        }
    }

    /**
     * level number to word Vietnameses
     * 
     * @param type $n
     * @return string
     */
    public static function dictionaryNumberVN($n) {
        $dictionary = array(
            0 => ' ',
            1 => ' ngàn ',
            2 => ' triệu ',
            3 => ' tỷ ',
            4 => ' ngàn ',
            5 => ' triệu ',
            6 => ' tỷ ',
        );
        return $dictionary[$n];
    }

    /**
     * Convert number int < 999 
     * 
     * @param type $number
     * @return string
     */
    public static function dictionaryNumberVNAll($number) {
        $number = (int) $number;
        $hyphen = ' ';
        $conjunction = '  ';
        $separator = ' ';
        $negative = 'Âm ';
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
            15 => 'Mười lăm',
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
            1000 => 'ngàn'
        );
        if ($number < 0) {
            return $negative . self::dictionaryNumberVNAll(abs($number));
        }
        switch (true) {
            //      case $number < 10;
            //           $string = 'lẻ ' . $dictionary[$number];
            //          break;
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
                    if ($remainder < 10) {
                        $string .= $conjunction . 'lẻ ' . self::dictionaryNumberVNAll($remainder);
                    } else {
                        $string .= $conjunction . self::dictionaryNumberVNAll($remainder);
                    }
                }
                break;
        }
        return $string;
    }

    public static function roman2number($roman) {
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

    public static function number2roman($num, $isUpper = true) {
        $n = intval($num);
        $res = '';

        /**
         *  roman_numerals array 
         */
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

}
