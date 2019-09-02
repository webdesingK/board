<div class="sidebar px-1">
    <div class="logo p-3 text-center border-bottom">
        <a class="text-black-50 h5 text-decoration-none" href="/admin-panel">
            <i class="fas fa-tools text-info"></i>
            <span><?= Yii::$app->name ?></span>
        </a>
    </div>
    <div class="mt-2 d-flex flex-column" style="font-size: 15px;">
        <div class="px-3 pt-2">
            <a href="#treeManager" data-toggle="collapse" class="text-black-50 text-decoration-none d-flex align-items-center">
                <i class="fas fa-project-diagram text-info"></i>
                <span class="ml-2 mr-5">Мененджер Nested Sets</span>
                <i class="fas fa-angle-down text-info ml-auto"></i>
            </a>
            <div id="treeManager" class="collapse px-4 py-1" style="font-size: 14px;">
                <div class="d-flex flex-column">
                    <a href="#" class="text-black-50 text-decoration-none pt-1">Категории</a>
                    <a href="#" class="text-black-50 text-decoration-none pt-1">Города</a>
                </div>
            </div>
        </div>
        <div class="px-3 pt-2">
            <a href="#filterManager" data-toggle="collapse" class="text-black-50 text-decoration-none d-flex align-items-center">
                <i class="fas fa-filter text-info"></i>
                <span class="ml-2 mr-5">Мененджер фильтров</span>
                <i class="fas fa-angle-down text-info ml-auto"></i>
            </a>
            <div id="filterManager" class="collapse px-4" style="font-size: 14px;">
                <div class="d-flex flex-column">
                    <a href="/admin-panel/filter-manager/create-filter" class="text-black-50 text-decoration-none pt-1">Создание фильтров</a>
                    <a href="#" class="text-black-50 text-decoration-none pt-1">Редактирование фильтров</a>
                    <a href="#" class="text-black-50 text-decoration-none pt-1">Привязка фильтров</a>
                </div>
            </div>
        </div>
        <div class="px-3 pt-2">
            <a href="#development" data-toggle="collapse" class="text-black-50 text-decoration-none d-flex align-items-center">
                <i class="fas fa-code text-info"></i>
                <span class="ml-2 mr-5">Разработка</span>
                <i class="fas fa-angle-down text-info ml-auto"></i>
            </a>
            <div id="development" class="collapse px-4" style="font-size: 14px;">
                <div class="d-flex flex-column">
                    <a href="/gii" class="text-black-50 text-decoration-none pt-1" target="_blank">Gii</a>
                    <a href="/debug" class="text-black-50 text-decoration-none pt-1" target="_blank">Debug Panel</a>
                    <a href="#" class="text-black-50 text-decoration-none pt-1">Песочница 1</a>
                    <a href="#" class="text-black-50 text-decoration-none pt-1">Песочница 2</a>
                    <a href="#" class="text-black-50 text-decoration-none pt-1">Песочница 3</a>
                </div>
            </div>
        </div>
    </div>
</div>

