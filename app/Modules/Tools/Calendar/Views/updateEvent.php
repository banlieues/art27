<?php $this->extend('\Layout\index'); ?>
<?php $this->section('body'); ?>

<div class="container mb-2">
    <div class="row mx-auto">

    <div class="col-4"><a href="/calendar/new" class="btn btn-sucess" style="border: 2px solid #90EE90";> Créer un<br>événement </a></div>
        <div class="col-4"><a href="/calendar/delete/" class="btn btn-sucess" style="border: 2px solid #87CEFA";> Modifier un <br>événement </a></div>
    
    </div>
</div>
<div class="container">
<h1> Modifier un événement</h1>
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <form action="/calendar/edit/<?= $post['id']?>" method="post">
                <div class="form-group" >
                
                <label for="">Titre de l'événement</label>
                <input value="<?= $post['name']?>"id="title" class="form-control" type="text" name="name">
                </div>


                <div class="form-group">
                <label for="">Date de début</label>
                <input value="<?= $post['start_date']?>" id="start_date" class="form-control" type="date" name="start_date">
                </div>

                <div class="form-group">
                <label for="">Date de fin</label>
                <input value="<?= $post['end_date']?>" id="end_date" class="form-control" type="date" name="end_date">
                </div>


                <div class="form-group" style="margin-top:10px">
                <button class="btn btn-success btn-sm">Modifier</button>
            </form>
        </div>
    </div>

</div>


<?php $this->endSection(); ?>