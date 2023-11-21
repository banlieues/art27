<?php $this->extend('\Layout\index'); ?>
<?php $this->section('body'); ?>


<div class="container mb-2">
    <div class="row mx-auto">

        <div class="col-4"><a href="/calendar/new" class="btn btn-sucess" style="border: 2px solid #90EE90";> Créer un<br>événement </a></div>
        <div class="col-4"><a href="/calendar/delete/" class="btn btn-sucess" style="border: 2px solid #87CEFA";> Modifie <br>événement </a></div>
    
    </div>
</div>




<div class="container mt-lg-5">
<table class="table table-bordered table-striped">
<thead class="bg-secondary">
<tr class="text-center">
<th>Event Id</th>
<th>Event Title</th>
<th>Event Start Date</th>
<th>Event End Date</th>
<th>Action</th>
</tr>
</thead>
<?php 

    if(empty($data))
    {
        $data=null;
    }
    else
    {
        foreach ($data as $event=>$values) 
        {
            extract($data[$event]);
            echo '<tr class="text-center">';
            echo '<td>'.$id.'</td>';
            echo '<td>'.$title.'</td>';
            echo '<td>'.$start.'</td>';
            echo '<td>'.$end.'</td>';
            echo '<td>'.anchor(base_url('/calendar/edit/'.$id), 'Edit', ['target' => '_self']);?> / <?=anchor(base_url('calendar/delete/'.$id), 'Delete', ['target' => '_self']).'</td>';
            echo '</tr>';
        }
    }
?>
</table>
</div>


<?php $this->endSection(); ?>