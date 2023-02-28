<?php
	$estiloTituloReporte = array(
	    'font' => array(
	        'name'      => 'Verdana',
	        'bold'      => true,
	        'italic'    => false,
	        'strike'    => false,
	        'size' =>16,
	        'color'     => array(
	            'rgb' => 'FFFFFF'
	        )
	    ),
	    'fill' => array(
		    'type'  => PHPExcel_Style_Fill::FILL_SOLID,
		    'color' => array(
	            'argb' => 'DF2222')
		),
	    'borders' => array(
	        'allborders' => array(
	            'style' => PHPExcel_Style_Border::BORDER_NONE
	        )
	    ),
	    'alignment' => array(
	        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	        'rotation' => 0,
	        'wrap' => TRUE
	    )
	);

	$estiloTituloColumnas = array(
	    'font' => array(
	        'name'  => 'Arial',
	        'bold'  => true,
	        'color' => array(
	            'rgb' => '000000')
	    ),
	    'fill' => array(
	        'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
			'rotation'   => 90,
	        'startcolor' => array(
	            'rgb' => 'c47cf2'),
	        'endcolor' => array(
	            'argb' => 'FF431a5d')
	    ),
	    'borders' => array(
	        'top' => array(
	            'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
	            'color' => array(
	                'rgb' => '143860')
	        ),
	        'bottom' => array(
	            'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
	            'color' => array(
	                'rgb' => '143860')
	        ),
	        'left' => array(
	            'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
	            'color' => array(
	                'rgb' => '143860')
	        ),
	        'right' => array(
	            'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
	            'color' => array(
	                'rgb' => '143860')
	        )
	    ),
	    'alignment' =>  array(
	        'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	        'wrap'      => TRUE
	    )
	);

	$estiloInformacion = new PHPExcel_Style();
	$estiloInformacion->applyFromArray( array(
	    'font' => array(
	        'name'  => 'Arial',
	        'color' => array(
	            'rgb' => '000000')
	    ),
	    'fill' => array(
			'type'  => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array(
	        	'argb' => 'FFd9b7f4')
		),
	    'borders' => array(
	        'left' => array(
	            'style' => PHPExcel_Style_Border::BORDER_THIN ,
	    		'color' => array(
	            	'rgb' => '3a2a47')
	        )
	    )
	));

	$estiloCoordina = array(
	    'font' => array(
	        'name'  => 'Arial',
	        'bold'  => true,
	        'color' => array(
	            'rgb' => '000000')
	    ),
	    'alignment' =>  array(
	        'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	        'wrap'      => TRUE
	    )
	);

	$estiloEjecutivos = array(
	    'font' => array(
	        'name'  => 'Arial',
	        'bold'  => true,
	        'color' => array(
	            'rgb' => '000000')
	    )
	);

	$estiloRutas = array(
	    'alignment' =>  array(
	        'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	        'wrap'      => TRUE
	    )
	);

	$estiloRojo = array(
	    'font' => array(
	        'color'     => array(
	            'rgb' => '000000')
	    ),
	    'fill' => array(
		    'type'  => PHPExcel_Style_Fill::FILL_SOLID,
		    'color' => array(
	            'rgb' => 'FF4040')
		),
	    'alignment' =>  array(
	        'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	        'wrap'      => TRUE
	    )
	);

	$estiloAmarillo = array(
	    'font' => array(
	        'color'     => array(
	            'rgb' => '000000')
	    ),
	    'fill' => array(
		    'type'  => PHPExcel_Style_Fill::FILL_SOLID,
		    'color' => array(
	            'rgb' => 'FFFF66')
		),
	    'alignment' =>  array(
	        'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	        'wrap'      => TRUE
	    )
	);

	$estiloVerde = array(
	    'font' => array(
	        'color'     => array(
	            'rgb' => '000000')
	    ),
	    'fill' => array(
		    'type'  => PHPExcel_Style_Fill::FILL_SOLID,
		    'color' => array(
	            'rgb' => '009966')
		),
	    'alignment' =>  array(
	        'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	        'wrap'      => TRUE
	    )
	);
?>