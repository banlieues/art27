<?php $session = session('filter') && isset(session('filter')->period) ? session('filter')->period : null;?>
<?php $type = isset($session) && isset($session->type) && isset($session->type->value) ? $session->type->value : null;?>
<?php $from = isset($session) && isset($session->from) && isset($session->from->value) ? $session->from->value : null;?>
<?php $to = isset($session) && isset($session->to) && isset($session->to->value) ? $session->to->value : null;?>
<?php $year = isset($session) && isset($session->year) && isset($session->year->value) ? $session->year->value : null;?>
<?php $month = isset($session) && isset($session->month) && isset($session->month->value) ? $session->month->value : null;?>

<div class="row mb-2">
    <label class="col-form-label col-4 pt-0"> 
        <strong> Période </strong> <br>
        <button type="button" class="badge bg-secondary" data-target=".periodCollapse" onclick="period_collapse_toggle(this);"> 
            <?php if(empty($from) && empty($to)):?> + <?php else:?> - <?php endif;?> de détails 
        </button>
    </label>
    <div class="col-8">
        <div class="row">
            <div class="col-form-label col-sm-auto pt-0"> basée sur la date </div>
            <div class="col-sm-auto">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="period[type]" id="enqueteDatetypeAnswer" value="answer" checked/>
                    <label class="form-check-label" for="enqueteDatetimeAnswer"> de réponse </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="period[type]" id="enqueteDatetypeSend" value="send"
                        <?php if(!empty($type) && $type=='send'):?> checked <?php endif;?>
                    />
                    <label class="form-check-label" for="enqueteDatetimeSend"> d'envoi </label>
                </div>
            </div>
        </div>
        <div class="collapse periodCollapse row align-items-center <?php if(empty($from) && empty($to)):?> show <?php endif;?>">
            <div class="col-auto">
                <select name="period[year]" onchange="filter_month_collapse(this);" 
                    class="form-control <?php if(!empty($year)):?> highlighted <?php endif;?>"
                    >
                    <option value="" selected> - Année - </option>
                    <?php foreach($year_list as $year_l):?>
                        <option value="<?php echo $year_l;?>"
                            <?php if(!empty($year) && $year==$year_l):?> selected <?php endif;?>
                            > 
                            <?php echo $year_l;?> 
                        </option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="collapse col-auto <?php if(!empty($year)):?> show <?php endif;?>" id="monthCollapse">
                <select name="period[month]" 
                    class="form-control <?php if(!empty($month)):?> highlighted <?php endif;?>"
                    >
                    <option value="" selected> - Mois - </option>
                    <?php foreach($month_list as $month_l):?>
                        <option value="<?php echo $month_l->num;?>"
                            <?php if(!empty($month) && $month==$month_l->num):?> selected <?php endif;?>
                            > 
                            <?php echo $month_l->name;?> 
                        </option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="collapse periodCollapse row align-items-center <?php if(!empty($from) || !empty($to)):?> show <?php endif;?>">
            <label for="enqueteFrom" class="col-form-label col-auto"> du </label>
            <div class="col-sm-auto">
                <input type="date" name="period[from]" placeholder="du"
                    class="form-control w-auto <?php if(!empty($from)):?> highlighted <?php endif;?>" 
                    <?php if(!empty($from)):?>
                        value="<?php echo $from;?>"
                    <?php endif;?>
                />
            </div>
            <label for="enqueteTo" class="col-form-label col-auto">au</label>
            <div class="col-sm-auto">
                <input type="date" name="period[to]" placeholder="au" 
                    class="form-control w-auto <?php if(!empty($to)):?> highlighted <?php endif;?>"
                    <?php if(!empty($to)):?>
                        value="<?php echo $to;?>"
                    <?php endif;?>
                />        
            </div>
        </div>
    </div>
</div> 
  