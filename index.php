<?php

require_once('functions.php');
$file = 'chat1/result.json';
$messages = getArticles($file)['messages'];
$k = 0;
/*
foreach ($messages as $message) {
	if (is_array($message['text'])){

		$k++;
		pr($message);

	} 
}
echo $k."<hr><hr><hr>";
*/
/*
if ($k === 0) {
	echo "<hr>dont have<hr>";
	
} else {
	echo "<hr>$k<hr>";

}*/



foreach ($messages as $message) {
	if($message['type']==='service'){
		$service_m[] = $message;
	}else {
		if( is_array($message['text']) ) {
			$new_text = '';
			
			foreach ($message['text'] as $mess):
				if(is_array($mess)):
					/*switch ($mess['type']) {
						case 'bold':
							$new_text .= "<b>{$mess['text']}</b>";
							break;
						case 'italic':
							$new_text .= "<i>{$mess['text']}</i>";
							break;
						case 'underline':
							$new_text .= "<ins>{$mess['text']}</ins>";
							break;
						case 'strikethrough':
							$new_text .= "<del>{$mess['text']}</del>";
							break;
						case 'monospace':
							$new_text .= "<small>{$mess['text']}</small>";
							break;
						default:
						$new_text .= "<sup>{$mess['text']}</sup>";
					}*/
					switch ($mess['type']) {
						case 'bold':
							$new_text .= "__{$mess['text']}__";
							break;
						case 'italic':
							$new_text .= "_{$mess['text']}_";
							break;
						case 'underline':
							$new_text .= "~~{$mess['text']}~~";
							break;
						case 'strikethrough':
							$new_text .= "~~{$mess['text']}~~";
							break;
						case 'monospace':
							$new_text .= "```{$mess['text']}```";
							break;
						default:
						$new_text .= "***{$mess['text']}***";
					}
				else:
					$new_text .= $mess;
				endif;
			endforeach;

			$message['text'] = $new_text;
			$simple_m[] = $message;
		}else {
			$simple_m[] = $message;
		}

	}
}


//pr($simple_m);
//pr($simple_m);
testTxt();
writeToMd($simple_m);

