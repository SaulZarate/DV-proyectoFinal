
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <? foreach ($menu as $item): ?>
            
            <? if (isset($item->type) && $item->type === "separate"): ?>
                <li class="nav-heading"><?=strtoupper($item->name)?></li>
            <? continue; 
            endif; ?>

            <? if (isset($item->subSection)): ?>
                <li class="nav-item menu-<?=$item->name?>">
                    <a 
                        class="nav-link <?=$item->name == $section ? '' : 'collapsed'?>" 
                        data-bs-target="#<?=$item->name?>-nav" 
                        data-bs-toggle="collapse" 
                        href="#"
                    >
                        <i class="<?=$item->icon?>"></i>
                        <span><?=ucfirst($item->name)?></span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    
                    <ul id="<?=$item->name?>-nav" class="nav-content collapse <?=$item->name === $section ? 'show' : ''?>" data-bs-parent="#sidebar-nav">
                        <? foreach ($item->subSection as $itemSubSection): ?>
                            <li class="menu-<?=$item->name?>-<?=$itemSubSection->name?>">
                                <a href="<?=$itemSubSection->path?>" class="<?=$itemSubSection->name === $subSection ? 'active' : ''?>">
                                    <i class="bi bi-circle"></i><span><?=ucfirst($itemSubSection->name)?></span>
                                </a>
                            </li>
                        <? endforeach; ?>
                    </ul>
                </li>
            <? else: ?>
                <li class="nav-item menu-<?=$item->name?>">
                    <a class="nav-link <?=$item->name === $section ? '' : 'collapsed'?>" href="<?=$item->path?>">
                        <i class="<?=$item->icon?>"></i>
                        <span><?=ucfirst($item->name)?></span>
                    </a>
                </li>
            <? endif; ?>

        <? endforeach; ?>
    </ul>

</aside>
