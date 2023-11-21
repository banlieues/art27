<?php $this->extend('\Layout\index'); ?>
<?php $this->section('body'); ?>
<div class="container mb-2">
    <div class="row mx-auto">

        <div class="col-4"><a href="/calendar/new" class="btn btn-sucess" style="border: 2px solid #90EE90";> Créer un<br>événement </a></div>
        <div class="col-4"><a href="/calendar/delete/" class="btn btn-sucess" style="border: 2px solid #87CEFA";> Modifier un <br>événement </a></div>
    
    </div>
</div>

<div class="container">

    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <form action="/calendar/new" method="post">
                <div class="form-group" >
                
                <label for="">Titre de l'événement</label>
                <input  id="name" class="form-control" type="text" name="name">
                </div>


                <div class="form-group">
                <label for="">Date de début</label>
                <input  id="start_date" class="form-control" type="date" name="start_date">
                </div>

                <div class="form-group">
                <label for="">Date de fin</label>
                <input  id="end_date" class="form-control" type="date" name="end_date">
                </div>


                <div class="form-group" style="margin-top:10px">
                <button class="btn btn-success btn-sm">Create </button>
            </form>
        </div>
    </div>

</div>

<?php $this->endSection(); ?>