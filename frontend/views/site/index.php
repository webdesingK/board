<br>
Всего объявлений - <?= count($adsData) ?>
<hr>

<? foreach($adsData as $v): ?>

    <div style="width: auto;height: 20px;background-color: #eeeeee;margin: 10px"><?= $v['name'] ?></div>


<? endforeach ?>