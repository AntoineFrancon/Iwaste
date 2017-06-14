<script type="text/javascript">

$(document).ready(function() {
    $.ajax({
        type: "GET",
         // beforeSend: function(request) {
         // request.setRequestHeader("apikey", "3uCDfWUJL8ALQm1np72o7/QPP6ktt/qbTzSjQSbNcq/ZrnSTQWsrncaN+DWnweVG5Ul6MQ5h+z1ifqSS8J+poA==");
         //    },
        
        
        headers: {
            //'apikey': '3uCDfWUJL8ALQm1np72o7/QPP6ktt/qbTzSjQSbNcq/ZrnSTQWsrncaN+DWnweVG5Ul6MQ5h+z1ifqSS8J+poA==',
            //'Access-Control-Allow-Origin': '*'
        },

        dataType: 'json',
        //url: "https://www.data.gouv.fr/api/1/organizations/premier-ministre/"
        url: "http://api.wunderground.com/api/50a65432f17cf542/conditions/q/France/Gif-sur-Yvette.json"
        //url: "https://api.objenious.com/v1/devices/562949953422033/messages",

        //url: "http://api.wunderground.com/api/conditions/q/France/Gif-sur-Yvette.json"
        //url: 'http://iwaste.larez.fr/get_objenious.php?content'

    }).then(function(data) {
        
        //$('.remplissage').append(data.members[0].user.first_name);
        $('.temperature').append(data.current_observation.temp_c+"°C");
        //$('.remplissage').append(data.messages[0].id);

        //$('.remplissage').append(data.response.version);

        //remplissage_poubelle1 = data.current_observation.temp_c
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
          echo 'var remplissagePoubelle1 = '.$obj['messages'][1]['payload'][0]['data']['temperature'].';';

          ?>

          $('.remplissage_1').append(remplissagePoubelle1+"%");

        
        

    });
});

</script>