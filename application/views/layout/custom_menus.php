<?php
  if($this->settings->info->menu_highlight) {
      if(isset($_GET['menuid']) && isset($_GET['sublinkid'])) {
        $u_menuid = intval($_GET['menuid']);
        $u_sublinkid = intval($_GET['sublinkid']);

        if($u_menuid > 0) {
          $activeLink['menu_' . $u_menuid] = array();
          if($u_sublinkid) {
            $activeLink['menu_' . $u_menuid]['link_' . $u_sublinkid] = 1;
          }
        }
      }
    }
        // Custom menu links
        $links = $this->home_model->get_site_links();
        $current_menu = 0;
        $close_menu = 0;
      ?>
      <?php foreach($links->result() as $r) : ?>
        <?php if($this->settings->info->menu_highlight) : ?>
          <?php $r->url = $r->url . "?menuid=" .  $r->menuid . "&sublinkid= " . $r->ID; ?>
        <?php endif; ?>
        <?php if($current_menu != $r->menuid) : ?>
          <?php $current_menu = $r->menuid; ?>
          <?php if($close_menu) : ?>
            <?php $close_menu = 0; ?>
                </ul>
              </div>
            </li>
          <?php endif; ?>
          <?php if($r->dropdown) : ?>
            <?php $close_menu = 1; ?>
            <?php $menu_string = "menu_" . $r->menuid; ?>
            <li id="<?php echo $menu_string ?>_sb">
          <a data-toggle="collapse" data-parent="#<?php echo $menu_string ?>_sb" href="#<?php echo $menu_string ?>_sb_c" class="collapsed <?php if(isset($activeLink['menu_' . $r->menuid])) echo "active" ?>" >
            <span class="glyphicon <?php echo $r->icon ?> sidebar-icon"></span> <?php echo $r->menu_name ?>
            <span class="plus-sidebar"><span class="glyphicon glyphicon-chevron-down"></span></span>
          </a>
          <div id="<?php echo $menu_string ?>_sb_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['menu_' . $r->menuid])) echo "in" ?>">
            <ul class="inner-sidebar-links">
            <li class="<?php if(isset($activeLink['menu_'.$r->menuid]['link_' . $r->ID])) echo "active" ?>"><a href="<?php echo $r->url ?>"><span class="glyphicon glyphicon-arrow-right admin-sb-link"></span> <?php echo $r->name ?></a></li>
          <?php else :  ?>
            <li class="<?php if(isset($activeLink['menu_'.$r->menuid]['link_' . $r->ID])) echo "active" ?>"><a href="<?php echo $r->url ?>"><span class="glyphicon <?php echo $r->icon ?> sidebar-icon"></span> <?php echo $r->name ?></a></li>
          <?php endif; ?>
        <?php else : ?>
          <?php if($r->dropdown) : ?>
            <li class="<?php if(isset($activeLink['menu_'.$r->menuid]['link_' . $r->ID])) echo "active" ?>"><a href="<?php echo $r->url ?>"><span class="glyphicon glyphicon-arrow-right admin-sb-link"></span> <?php echo $r->name ?></a></li>
          <?php else : ?>
            <li class="<?php if(isset($activeLink['menu_'.$r->menuid]['link_' . $r->ID])) echo "active" ?>"><a href="<?php echo $r->url ?>"><span class="glyphicon <?php echo $r->icon ?> sidebar-icon"></span> <?php echo $r->name ?></a></li>
          <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; ?>
      <?php if($close_menu) : ?>
        </ul>
              </div>
            </li>
      <?php endif; ?>