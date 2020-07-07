<?php

// Подключаем модуль
CModule::IncludeModule('sale');

// Запускаем рекурсивную функцию
getLocChild();


/*
 * 
*/
function getLocChild($id = 1, $name_ext = array()){
	$locations = array();
	$res = \Bitrix\Sale\Location\LocationTable::getList(array(
		'filter' => array(
			'=ID' => $id, 
			'=CHILDREN.NAME.LANGUAGE_ID' => LANGUAGE_ID,
			'=CHILDREN.TYPE.NAME.LANGUAGE_ID' => LANGUAGE_ID,
		),
		'select' => array(
			'CHILDREN.*',
			'NAME_RU' => 'CHILDREN.NAME.NAME',
			'TYPE_CODE' => 'CHILDREN.TYPE.CODE',
			'TYPE_NAME_RU' => 'CHILDREN.TYPE.NAME.NAME'					
		)
	));
	while($item = $res->fetch())
	{
		$text = '{"id":'.$item["SALE_LOCATION_LOCATION_CHILDREN_ID"].',"":"'.$item["NAME_RU"].'", "ext":"'.implode(', ', array_reverse($name_ext)).'", "parent_id":'.$id.', "type":"'.$item["TYPE_CODE"].'"},';
		if($item["TYPE_CODE"]=="STREET"){
			file_put_contents('cities/'.$id.'/index.json', $text, FILE_APPEND);
		} elseif($item["TYPE_CODE"]=="CITY" || $item3["TYPE_CODE"]=="VILLAGE"){
			file_put_contents('cities/index.json', $text, FILE_APPEND);
		}
		
		if($item["TYPE_CODE"]=="CITY" || $item["TYPE_CODE"]=="VILLAGE" || $item["TYPE_CODE"]=="REGION" || $item["TYPE_CODE"]=="COUNTRY_DISTRICT" || $item["TYPE_CODE"]=="SUBREGION"){

				$new_ext = array_merge($name_ext, array($item["NAME_RU"]));
				$sublocations = getLocChild($item["SALE_LOCATION_LOCATION_CHILDREN_ID"], $new_ext);
		}
		$new_ext = [];
	}
	
	return array_merge($locations, $sublocations);
}

?>
