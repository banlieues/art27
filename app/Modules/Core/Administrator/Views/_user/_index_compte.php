<?php $this->extend("\Administrator\Views\administrator/index"); ?>

<?php $this->section("admin_body"); ?>

<h4>
    <?php echo $title.' : '.$subtitle; ?>
</h4>

<div class="clearfix">

</div>

<div class="row">
    <div class="col-md-6 col-xl-6">
        <div class="card flex-fill border-top-theme mb-4">
            <h5 class="card-header">
                Projects Status <i class="fas fa-chart-bar float-end mt-1"></i>
            </h5>
            <div class="card-body">
                <small>Identification (login/register)</small>
                <div class="progress mb-3">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
                <small>Email (verify/reverify)</small>
                <div class="progress mb-3">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
                <small>Forgot password</small>
                <div class="progress mb-3">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
            </div>
            <div class="card-footer text-body-secondary"></div>
        </div>
    </div>
    <div class="col-md-6 col-xl-6">
        <div class="card flex-fill border-top-theme mb-4">
            <h5 class="card-header">
                Pages Status <i class="fas fa-chart-line float-end mt-1"></i>
            </h5>
            <div class="card-body">
                <small>Dashboard Page</small>
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100">89%</div>
                </div>
                <small>Profile Page</small>
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100">55%</div>
                </div>
                <small>Tutorial Page</small>
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100">66%</div>
                </div>
            </div>
            <div class="card-footer text-body-secondary"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col text-center">
        Page load time {elapsed_time} seconds
    </div>
</div>

<?php $this->endSection(); ?>
