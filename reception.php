
		<p>

			<?php
			// Create a stream
			$opts = array(
			  'http'=>array(
			    'method'=>"GET",
			    'header'=>"apikey: 3uCDfWUJL8ALQm1np72o7/QPP6ktt/qbTzSjQSbNcq/ZrnSTQWsrncaN+DWnweVG5Ul6MQ5h+z1ifqSS8J+poA=="
			  )
			);

			$context = stream_context_create($opts);

			// Open the file using the HTTP headers set above
			$file = file_get_contents("https://api.objenious.com/v1/devices/562949953422033/messages", false, $context);
			$obj = json_decode($file, true);
			// var_dump($obj);
			
			// $jsonIterator = new RecursiveIteratorIterator(
   // 			new RecursiveArrayIterator(json_decode($file, TRUE)),
   //  		RecursiveIteratorIterator::SELF_FIRST);

			// foreach ($jsonIterator as $key => $val) {
   //  			if(is_array($val)) {
   //      			echo "<p>$key:\n</p>";
   //  			} else {
   //      			echo "<p>$key => $val\n</p>";
			//     }
			// }

			echo $obj['messages'][1]['payload'][0]['data']['temperature'].' degrés Celcius<br>';



			?>
		<p>


