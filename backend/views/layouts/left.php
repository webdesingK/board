<?php

    use backend\widgets\LteMenu;

?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <!--        <form action="#" method="get" class="sidebar-form">-->
        <!--            <div class="input-group">-->
        <!--                <input type="text" name="q" class="form-control" placeholder="Search..."/>-->
        <!--              <span class="input-group-btn">-->
        <!--                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>-->
        <!--                </button>-->
        <!--              </span>-->
        <!--            </div>-->
        <!--        </form>-->
        <!-- /.search form -->

        <?= LteMenu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    [
                        'label' => 'Главные страницы',
                        'icon' => 'home',
                        'items' => [
                            [
                                'label' => 'Главная сайта',
                                'icon' => 'desktop',
                                'url' => '/',
                                'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'
                            ],
                            [
                                'label' => 'Главная админки',
                                'icon' => 'wrench',
                                'url' => '/админка'
                            ]
                        ]
                    ],
                    [
                        'label' => 'Мененджер категорий',
                        'url' => ['/мененджер-категорий'],
                        'icon' => 'list-ul'
                    ],
                    [
                        'label' => 'Мененджер городов',
                        'url' => ['/мененджер-городов'],
                        'icon' => 'building'
                    ],
                    [
                        'label' => 'Мененджер фильтров',
                        'url' => '',
                        'icon' => 'filter',
                        'items' => [
                            [
                                'label' => 'Создание фильтров',
                                'url' => ['/создание-фильтров'],
                                'icon' => 'plus'
                            ],
                            [
                                'label' => 'Редактирование фильтров',
                                'url' => ['/редактирование-фильтров'],
                                'icon' => 'pencil'
                            ],
                            [
                                'label' => 'Привязка фильтров',
                                'url' => ['/привязка-фильтров'],
                                'icon' => 'exchange'
                            ],
                        ]
                    ],
                    [
                        'label' => 'Просмотр данных',
                        'url' => '',
                        'icon' => 'database',
                        'items' => [
                            [
                                'label' => 'Категории',
                                'url' => ['/просмотр-категорий'],
                                'icon' => 'list-ul'
                            ],
                            [
                                'label' => 'Города',
                                'url' => ['/просмотр-городов'],
                                'icon' => 'building'
                            ]
                        ]
                    ],
                    [
                        'label' => 'Разработка',
                        'icon' => 'gears',
                        'url' => '',
                        'items' => [
                            [
                                'label' => 'Gii',
                                'icon' => 'code',
                                'url' => ['/gii'],
                                'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'
                            ],
                            [
                                'label' => 'Debug',
                                'icon' => 'dashboard',
                                'url' => ['/debug'],
                                'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'
                            ]
                        ]
                    ]
                ],
            ]
        ) ?>

    </section>

</aside>
