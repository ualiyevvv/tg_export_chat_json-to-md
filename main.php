<?php require "index.php"; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>my diary</title>
</head>
<body>

<div class="container">
<?php
		foreach($service_m as $message):
	?>
	<div class="message system" id="<?=$message['id']?>">
		<? echo $message["date"].": ". $message["actor"]."(".$message["actor_id"].")" . ">>>" .$message["action"] . "<hr>"; ?>
	</div>
	<?php endforeach;?>





	<?php
		foreach($simple_m as $message):
			$file =  explode('/', $message["file"])[1];
			$photo =  explode('/', $message["photo"])[1];
			if ($message["forwarded_from"]){
				$forwarded = " (forwarded from: ". $message["forwarded_from"].")";
			}else {
				$forwarded = '';
			}
			$reply = '';
			$flagDate = false;
			$reply_id = $message['reply_to_message_id'];
			if($message['reply_to_message_id']){
				$reply = 'in reply to <a href="#'. $reply_id .'">this message</a><br>';
				$reply_text = searchReplyText($reply_id, $simple_m);
				$reply .= $reply_text;
			}
			$date = explode("T", $message["date"]);
			$flagDate = checkOnceDate($message['id'], $simple_m, $date[0]);
			if( !$flagDate ):
	?>
	
	<div class="date">
		<h1><? echo $date[0]; ?></h1>
	</div>
	<?endif;?>


	<div class="message" id="<?=$message['id']?>">
		<div class="info">
			<? echo $date[1].": ". $message["from"]; ?>
		</div>
		
		<div class="content">
			<?if($reply != ''):?>
				<p class="reply">	
					> <?=$reply?>
			</p>
			<?endif;?>
			<? echo nl2br($message["text"]) . $forwarded; ?>

			<div class="media">
				<? if($message["photo"]):  ?>
					![[<?=$photo?>]]
				<?endif;?>
				<? if($message["file"]):  ?>
					![[<?=$file?>]]
				<?endif;?>
			</div>
		</div>

		<p>---</p>

		
	</div>
	<?php endforeach;?>



</div>
</body>
</html>