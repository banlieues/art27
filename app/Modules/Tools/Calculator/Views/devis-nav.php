<div class="sticky_button">
    <div id="DevisNav" class="list-group small" style="overflow-y: auto;">
        <a class="list-group-item" href="#InfoAnchor"> INFOS GENERALES </a>
        <div id="DevisNavNew" style="display: none;">
            <a class="list-group-item" href="#DevisAnchor">
                <label> NOUVELS OUVRAGES</label>
                <button type="button"
                    class="btn-caret btn btn-sm py-0"
                    data-bs-toggle="collapse"
                    data-bs-target="#DevisNavNewWorksCollapse"
                    >
                    <?php echo fontawesome('caret-down');?>
                </button>
            </a>
            <div id="DevisNavNewWorksCollapse" class="collapse show">
            </div>
        </div>
        <a class="list-group-item" href="#DevisAnchor">
            <label> OUVRAGES ENREGISTRES </label>
            <button type="button"
                class="btn-caret btn btn-sm py-0"
                data-bs-toggle="collapse"
                data-bs-target="#DevisNavWorksCollapse"
                >
                <?php echo fontawesome('caret-down');?>
            </button>
        </a>
        <div id="DevisNavWorksCollapse" class="collapse show">    
            <?php foreach($devis->works as $work):?>
                <?php echo view('Calculator\devis-nav-ouvrage', ['work' => $work]);?>
            <?php endforeach;?>
        </div>
    </div>
</div>