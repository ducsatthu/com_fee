<?php

//su dung autoload cua thang joomla
jimport('phpexcel.library.PHPExcel');

class FeeHelperExcel {

    public static function readExcel($path) {
        
        $inputFileType = 'Excel5';
        $inputFileName = $path;
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($inputFileName);

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();

        $i = 0;
        $j = 0;
        for ($row = 4; $row <= $highestRow; $row++) {
            $department = $sheet->getCellByColumnAndRow(4, $row)->getValue();
            $course = $sheet->getCellByColumnAndRow(8, $row)->getValue();

            if ($row == 4 || $department == $sheet->getCellByColumnAndRow(4, $row - 1)->getValue() && $course == $sheet->getCellByColumnAndRow(8, $row - 1)->getValue()) {
                @$objStudent[$i]->department = $department;
                @$objStudent[$i]->course = $course;
                @$objStudent[$i]->student[$j]->name = $sheet->getCellByColumnAndRow(1, $row)->getValue() . " " . $sheet->getCellByColumnAndRow(2, $row)->getValue();
                @$objStudent[$i]->student[$j]->birthday = $sheet->getCellByColumnAndRow(3, $row)->getValue();
                @$objStudent[$i]->student[$j]->idstudent = $sheet->getCellByColumnAndRow(5, $row)->getValue() . $sheet->getCellByColumnAndRow(6, $row)->getValue() . $sheet->getCellByColumnAndRow(7, $row)->getValue();
                $j++;
            } else {
                $i++;
                $j = 0;
            }
        }
        return json_encode($objStudent);
    }

}
