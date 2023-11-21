<?php $this->extend('\Layout\index'); ?>

<?php $this->section('script_head_injected');?>
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>-->
<?php $this->endSection(); ?>


<?php $this->section('body'); ?>
<div class="my-2 py-3">  <h2 class="text-center">Codeigniter 4 Fullcalendar - Stan - Test1</h2> </div>
    <div class="container">
        <div class="">
        
            <div class="row pb-3" style="">
                <div class="col-6">
                    <div id="calendar"></div>
                    
                </div>
                <div class="col-6"> 
            <iframe id="inlineFrameExample"
                    title="Inline Frame Example"
                    height="100%"
                    width="100%"
                    src="https://h4.local/calendar/new">
                </iframe>
            </div>
        </div>       
    </div>
</div>
    

<?php $this->endSection(); ?>
<?php $this->section('script_foot_injected');?>

<script type="text/javascript">
    <?php
        if(empty($data))
                $data=null;
    ?>    
        var events = <?php echo json_encode($data) ?>;
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
          schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
          events: events,
        });
        calendar.render();
      });
    
</script>
<?php $this->endSection(); ?>

<!--"is set" = variable exist ?
"is null" = est e que la valeur est nul ?
"empty" = valeur null ou zero ?



objectif :
C.R.U.D = Creer read update delete  = fonctionnement complet de base  

plusieurs technique pour travailler avec la base de données :-->