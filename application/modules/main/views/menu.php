<!-- ############ Aside START-->
<div id="aside" class="page-sidenav no-shrink bg-light nav-dropdown fade" aria-hidden="true">
    <div class="sidenav h-100 modal-dialog bg-light">
        <!-- sidenav top -->
        <div class="navbar">
            <!-- brand -->
            <a href="<?=base_url()?>main" class="navbar-brand ">
                <img src="https://trans.my.id/ngimobile/assets/img/zoo.png" width="40">
                
                <!-- <img src="../assets/img/logo.png" alt="..."> -->
                <span class="hidden-folded d-inline l-s-n-1x ">Semarang ZOO</span>
            </a>
            <!-- / brand -->
        </div>
        <!-- Flex nav content -->
        <div class="flex scrollable hover">
            <div class="nav-active-text-primary" data-nav>
                <ul class="nav bg">
                    <li class="nav-header hidden-folded">
                        <span class="text-muted">Main</span>
                    </li>
                    <?php $module_active = $this->uri->segment(1); $menu_active = $this->uri->segment(2);?>

                    <li class="<?=(($module_active == 'main') ? 'active' : '')?>">
                        <a href="<?=base_url()?>main">
                            <span class="nav-icon "><i data-feather="home"></i></span>
                            <span class="nav-text">Dashboard </span>
                        </a>
                    </li>

                    <?php function group_by($array, $by){
                            $groups = array();

                            foreach ($array as $key => $value) {
                                $groups[$value->$by][] = $value;
                            }

                            return $groups;
                        }

                        $module = group_by($this->session->userdata('menu'), 'module_name');

                        foreach ($module as $key => $_module) { 

                            echo '<li class="nav-header hidden-folded">
                                    <span class="text-muted">'.$key.'</span>
                                 </li>';

                            $grouped = group_by($_module, 'menu_parent');

                            foreach ($grouped as $_key => $_grouped) {
                                if($_key == ""){
                                    foreach ($_grouped as $__key => $menu) {
                                        echo '<li class="'.(($menu_active == $menu->menu_url) ? 'active' : '').'">
                                                    <a href="'.base_url().$menu->module_url.'/'.$menu->menu_url.'">
                                                        <span class="nav-icon"><i data-feather="chevrons-right"></i></span>
                                                        <span class="nav-text">'.$menu->menu_name.'</span>
                                                    </a>
                                            </li>';
                                    }
                                }else{
                                    echo '<li class="'.((count(array_filter($_grouped, function($arr) use ($menu_active) { return strtolower($arr->menu_url) == strtolower($menu_active); })) > 0) ? 'active' : '').'">
                                            <a href="#" class="">
                                                <span class="nav-icon"><i data-feather="chevrons-right"></i></span>
                                                <span class="nav-text">'.$_key.'</span>
                                                <span class="nav-caret"></span>
                                            </a>
                                            <ul class="nav-sub nav-mega">';

                                            foreach ($_grouped as $__key => $menu) {
                                                echo '<li class="'.(($menu_active == $menu->module_url) ? 'active' : '').'">
                                                        <a href="'.base_url().$menu->module_url.'/'.$menu->menu_url.'" class="">
                                                            <span class="nav-text">'.$menu->menu_name.'</span>
                                                        </a>
                                                    </li>';
                                            }

                                            
                                    echo    '</ul>
                                        </li>';
                                }
                            }
                        } 
                    ?>
                </ul>
            </div>
        </div>
        <!-- sidenav bottom -->
        <div class="no-shrink ">
            <!-- <div class="p-3 d-flex align-items-center">
                <div class="text-sm hidden-folded text-muted">
                    Trial: 35%
                </div>
                <div class="progress mx-2 flex" style="height:4px;">
                    <div class="progress-bar gd-success" style="width: 35%"></div>
                </div>
            </div> -->
        </div>
    </div>
</div>
<!-- ############ Aside END-->