<?php

    /**
     * @var $directoryAsset string
     */

    use adminPanel\widgets\LteMenu;

?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
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
                                'url' => '/admin-panel'
                            ]
                        ]
                    ],
                    [
                        'label' => 'Мененджер категорий',
                        'url' => ['tree-manager/categories-manager'],
                        'icon' => 'list-ul'
                    ],
                    [
                        'label' => 'Мененджер городов',
                        'url' => ['tree-manager/cities-manager'],
                        'icon' => 'building'
                    ],
                    [
                        'label' => 'Мененджер фильтров',
                        'url' => '',
                        'icon' => 'filter',
                        'items' => [
                            [
                                'label' => 'Создание фильтров',
                                'url' => ['/filter-manager/create-filters'],
                                'icon' => 'plus'
                            ],
                            [
                                'label' => 'Редактирование фильтров',
                                'url' => ['/filter-manager/edit-filters'],
                                'icon' => 'pencil'
                            ],
                            [
                                'label' => 'Привязка фильтров',
                                'url' => ['/filter-manager/bind-filters'],
                                'icon' => 'exchange'
                            ],
                            [
                                'label' => 'Cвязанныe данныe',
                                'url' => '',
                                'icon' => 'table',
                                'items' => [
                                    [
                                        'label' => 'Категории-фильтры',
                                        'icon' => 'exchange',
                                        'url' => ['/filter-manager/category-filters']
                                    ],
                                    [
                                        'label' => 'Фильтры-категории',
                                        'icon' => 'exchange',
                                        'url' => ['/filter-manager/filter-categories']
                                    ]
                                ]
                            ]
                        ]
                    ],
//                    [
//                        'label' => 'Просмотр данных',
//                        'url' => '',
//                        'icon' => 'database',
//                        'items' => [
//                            [
//                                'label' => 'Категории',
//                                'url' => ['/просмотр-категорий'],
//                                'icon' => 'list-ul'
//                            ],
//                            [
//                                'label' => 'Города',
//                                'url' => ['/просмотр-городов'],
//                                'icon' => 'building'
//                            ]
//                        ]
//                    ],
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
                            ],
                            [
                                'label' => 'Песочница',
                                'icon' => 'code',
                                'url' => ['/sand-box']
                            ]
                        ]
                    ]
                ],
            ]
        ) ?>

    </section>

</aside>
