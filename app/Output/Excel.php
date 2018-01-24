<?php
// TA:#487
require_once 'Abstract.php';

Class Output_Excel extends Output_Abstract{
 
     
    public function __construct($input){
        parent::__construct();
        $this->input = $input;
        $this->main();
    } 
    
    
    public function main(){
    
		if(isset($this->input['csvheaders'])) {
		  $headers = $this->input['csvheaders'];
		  unset($this->input['csvheaders']);
		} else {
		  $headers = array_keys(reset($this->input));  
		}
		
		$this->headers['Expires'] = gmdate('D, d M Y H:i:s') . ' GMT';
		$this->headers['Content-Type'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		
		// lem9 & loic1: IE need specific headers
		$isIE = strstr( $_SERVER['HTTP_USER_AGENT'], 'MSIE' );
		if ( $isIE ) {
			$this->headers['Content-Disposition'] = 'inline; filename="report.xlsx"';
			$this->headers['Cache-Control'] = 'must-revalidate, post-check=0, pre-check=0';
			$this->headers['Pragma'] = 'public';
		} else {
			$this->headers['Content-Disposition'] = 'attachment;filename="report.xlsx"';
			$this->headers['Pragma'] = 'no-cache'; //TA:#487
		}
		
		include_once('PHPExcel/IOFactory.php');
		$objPHPExcel = new PHPExcel();
		$ActiveSheet = $objPHPExcel->setActiveSheetIndex(0);
		// add all headers
		$i=0;
		foreach($headers as $ind_el){
		    $Location = PHPExcel_Cell::stringFromColumnIndex($i) . '1';
		    $ActiveSheet->setCellValue($Location, $ind_el);
		    $i++;
		}
		$ActiveSheet->getStyle("A1:AA1")->getFont()->setBold( true );
		
		$rowIndex=2;
		// add all data
		foreach($this->input as $row){
		    $columnIndex=0; //Column A
		    foreach($row as $ind_el){
		        $Location = PHPExcel_Cell::stringFromColumnIndex($columnIndex) . $rowIndex;
		        $ActiveSheet->setCellValue($Location, $ind_el);    
		        $columnIndex++;
		    }
		    $rowIndex++;
		}
		// set the Column Width
		for($i=0; $i<count($headers);$i++){
		  $Location = PHPExcel_Cell::stringFromColumnIndex($i) ;
		  $ActiveSheet->getColumnDimension($Location)->setAutoSize(true);
		}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
    }     
 }
?>