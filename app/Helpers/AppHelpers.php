<?php

if ( ! function_exists('cutUnicode'))
{
	function cutUnicode($str){ //Cắt dấu tiếng việt
		  if(!$str) return false;
		   $unicode = array(
			 'a'=>'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
			 'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
			 'd'=>'đ',
			 'D'=>'Đ',
			 'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
			 'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
			 'i'=>'í|ì|ỉ|ĩ|ị',
			 'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
			 'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
			 'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
			 'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
			 'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
			 'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
			 'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ'
		   );
		   foreach($unicode as $khongdau=>$codau) {
				$arr=explode("|",$codau);
				$str = str_replace($arr,$khongdau,$str);
		   }
		return $str;
	}
}

if ( ! function_exists('toSlug'))
{
	function toSlug($string) {
		$string= trim(cutUnicode($string));
		$string = strtolower($string);
		//Strip any unwanted characters
		$string = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $string);
		$string = strtolower(trim($string, '-'));
		$string = preg_replace("/[\/_|+ -]+/", '-', $string);
		return $string;
	}
}

if (!function_exists('pr')) {
    function pr($data, $type = 0) {
        print '<pre>';
        print_r($data);
        print '</pre>';
        if ($type != 0) {
            exit();
        }
    }
}

if (!function_exists('__make_folder_upload')) {
    function __make_folder_upload($folder_name = 'images') {
       	$folder_path = '/uploads/'.$folder_name.'/'.date('Y').'/'.date('m').'/'.date('d').'/';
        //$folder = __Newfolder($folder_path);
       	$folder = Storage::makeDirectory($folder_path);
       	return $folder_path;
    }
}

if (!function_exists('__Newfolder')) {
    function __Newfolder($folder) {
        $arr_folder = explode('/', $folder);
        $fol = '';
        foreach ($arr_folder as $row) {
            if (!empty($row)) {
                $fol.=$row . '/';
                if (!file_exists($fol)) {
                    @mkdir($fol, 0777);
                } else {
                    if ($row != 'static') {
                        $mod = substr(sprintf('%o', fileperms($fol)), -4);
                        if ($mod != 0777) {
                            @chmod($fol, 0777);
                        }
                    }
                }
            }
        }
    }
}

if (!function_exists('__resize_img')) {
    function __resize_img($bath_file = '', $width = 0, $height = 0) {
        $base64 = '';
    	if(file_exists(base_path() .$bath_file) && $bath_file){
            $img = Image::make(base_path() .$bath_file)->resize( $width, $height);
            $img->encode('jpg');
            $type = 'jpg';
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($img);
        }
    	return $base64;
    }
}


if (!function_exists('__random_string')) {
    function __random_string($length = 10) {
        $allowed_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $allowed_chars_len = strlen($allowed_chars);

        if($allowed_chars_len == 1) {
            return str_pad('', $length, $allowed_chars);
        } else {
            $result = '';

            while(strlen($result) < $length) {
              $result .= substr($allowed_chars, rand(0, $allowed_chars_len), 1);
            } // while

            return $result;
        }
    }
}

if (!function_exists('__xss_clean_string')) {
    function __xss_clean_string($input)
    {
        $output = strip_tags(htmlspecialchars($input));
        return $output;
    }
}

if (!function_exists('__xss_clean_int')) {
    function __xss_clean_int($input)
    {
        $output = intval($input);
        return $output;
    }
}


/* EXPORT */
function __createExcelFile($header, $formatExcel = array()) {

    // Create report
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("setCreator")
        ->setLastModifiedBy("setLastModifiedBy")
        ->setTitle("setTitle")
        ->setSubject("setSubject")
        ->setDescription("setDescription")
        ->setKeywords("setKeywords")
        ->setCategory("setCategory");

    // Set default columns
    $styleArray = array(
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => 'FFFFFF')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    );

    $sheet1 = $objPHPExcel->getActiveSheet(0);

    foreach($header as $k=>$v) {

        // Write Header
        $sheet1->setCellValue($k.'1', $v);

        // Set Column Width
        if(isset($formatExcel['c_width'][$k])) {
            $sheet1->getColumnDimension($k)->setWidth($formatExcel['c_width'][$k]);
        } else {
            $sheet1->getColumnDimension($k)->setAutoSize(true);
        }
    }

    $sheet1->getStyle('A1:'.$sheet1->getHighestColumn().'1')->applyFromArray($styleArray);
    // Set cell background color
    cellColor($objPHPExcel, 'A1:'.$sheet1->getHighestColumn().'1', '0489B1');
    // Set Column Alignment
    $sheet1->getStyle('A1:'.$sheet1->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    return $objPHPExcel;
}

/**
 * Set cell background color
 *
 * @param $objPHPExcel
 * @param $cells
 * @param $color
 */
function cellColor($objPHPExcel, $cells, $color) {
    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()
        ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array('rgb' => $color)
        ));
}


/**
 * Download excel file
 * @param $objPHPExcel
 */
function __downloadExcelFile($objPHPExcel, $fileName = 'report.xls') {

    $styleArray = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '333333')
            )
        )
    );

    $objPHPExcel->getActiveSheet()->getStyle(
        'A1:' .
        $objPHPExcel->getActiveSheet()->getHighestColumn() .
        $objPHPExcel->getActiveSheet()->getHighestRow()
    )->applyFromArray($styleArray);

    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('Report');

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    // Download file
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$fileName.'"');
    header('Cache-Control: max-age=0');

    // Do your stuff here
    $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    // This line will force the file to download
    $writer->save('php://output');
    exit;

}

function __export_to_excel ($data, $name ='') {
  $_headers = array(
  'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
  ,'AA','AB','AC','AD','AE','AF','AG','AH','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
  );
  $headers = $data['headers'];
  $arrHeaders = array();
  foreach($headers as $key=>$value) {
    $arrHeaders[$_headers[$key]]  = $value;
  }



  $objPHPExcel = __createExcelFile($arrHeaders);


  $rowCount = 1;
  $rows = $data['rows'];
  foreach($rows as $item) {
    $rowCount++;
    foreach($item as $key=>$value) {
      $objPHPExcel->getActiveSheet()->setCellValue($_headers[$key].$rowCount, $value);
    }
  }

  __downloadExcelFile($objPHPExcel, $name);
  die;
}


/**
 * This function used to get value config
 * @param type_config
 * @return
 */
if (!function_exists('__get_value_config')) {
    function __get_value_config($key_config, $data = array())
    {
        $value = '';
        $result = DB::table('config')->select('*')->where('key_config', $key_config)->first();
        if(!empty($result)){
            if($result->type_config == 'image'){
                $value = '<img src="'. __resize_img($result->value_config, $data['width'], $data['height']) .'" />';
            } else{
                $value = $result->value_config;
            }
        }

        return $value;
    }
}

if (!function_exists('__pagination')) {
    function __pagination($totalRows, $pageNum = 1, $pageSize, $limit = 3, $current_url = '')
    {
        settype($totalRows, "int");
        settype($pageSize, "int");
        if ($totalRows <= 0)
            return "";
        $totalPages = ceil($totalRows / $pageSize);
        if ($totalPages <= 1)
            return "";
        $currentPage = $pageNum;
        if ($currentPage <= 0 || $currentPage > $totalPages)
            $currentPage = 1;

        //From to
        $form = $currentPage - $limit;
        $to = $currentPage + $limit;

        //Tinh toan From to
        if ($form <= 0) {
            $form = 1;
            $to = $limit * 2;
        };
        if ($to > $totalPages)
            $to = $totalPages;

        //Tinh toan nut first prev next last
        $first = '';
        $prev = '';
        $next = '';
        $last = '';
        $link = '';

        //Link URL
        $linkUrl = $current_url;

        $get = '';
        $querystring = '';
        if ($_GET) {
            foreach ($_GET as $k => $v) {
                if ($k != 'p')
                    $querystring = $querystring . "&{$k}={$v}";
            }
            $querystring = substr($querystring, 1);
            $get .= '?' . $querystring;
        }
        $sep = (!empty($querystring)) ? '&' : '';
        $linkUrl = $linkUrl . '?' . $querystring . $sep . 'p=';

        if ($currentPage > $limit + 2) {
            /** first */
            //$first= "<a href='$linkUrl' class='first'>...</a>&nbsp;";
        }

        /** **** prev ** */
        if ($currentPage > 1) {
            $prevPage = $currentPage - 1;
            $prev = "<li class='paginate_button previous'><a href='$linkUrl$prevPage' class='prev'> Previous </a></li>";
        }

        /** *Next** */
        if ($currentPage < $totalPages) {
            $nextPage = $currentPage + 1;
            $next = "<li class='paginate_button next'><a href='$linkUrl$nextPage' class='next'> Next </a></li>";
        }

        /** *Last** */
        if ($currentPage < $totalPages - 4) {
            $lastPage = $totalPages;
            //$last= "<a href='$linkUrl$lastPage' class='last'>...</a>";
        }

        /* * *Link** */
        for ($i = $form; $i <= $to; $i++) {
            if ($currentPage == $i)
                $link .= "<li class='paginate_button active'><a href='javascript:;'>$i</a></li>";
            else
                $link .= "<li class='paginate_button'><a href='$linkUrl$i'>$i</a></li>";
        }

        $pagination = '<div class="dataTables_paginate paging_simple_numbers pagination" id="dynamic-table_paginate"><ul class="pagination">' . $first . $prev . $link . $next . $last . '</ul></div>';

        return $pagination;
    }
}
