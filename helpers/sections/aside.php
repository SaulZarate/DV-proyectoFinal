
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <? foreach ($menu as $nameSection => $item): ?>
            
            <? if (isset($item->type) && $item->type === "separate"): ?>
                <li class="nav-heading"><?=strtoupper($item->name)?></li>
            <? continue; 
            endif; ?>

            <? if (isset($item->subSection)): ?>
                <li class="nav-item menu-<?=$nameSection?>">
                    <a 
                        class="nav-link <?=$nameSection == $section ? '' : 'collapsed'?>" 
                        data-bs-target="#<?=$nameSection?>-nav" 
                        data-bs-toggle="collapse" 
                        href="#"
                    >
                        <i class="<?=$item->icon?>"></i>
                        <span><?=ucfirst($item->name)?></span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    
                    <ul id="<?=$nameSection?>-nav" class="nav-content collapse <?=$nameSection === $section ? 'show' : ''?>" data-bs-parent="#sidebar-nav">
                        <? foreach ($item->subSection as $itemSubSection): ?>
                            <li class="menu-<?=$nameSection?>-<?=$itemSubSection->name?>">
                                <a href="<?=$itemSubSection->path?>" class="<?=$itemSubSection->name === $subSection ? 'active' : ''?>">
                                    <i class="bi bi-circle"></i><span><?=ucfirst($itemSubSection->name)?></span>
                                </a>
                            </li>
                        <? endforeach; ?>
                    </ul>
                </li>
            <? else: ?>
                <li class="nav-item menu-<?=$nameSection?>">
                    <a class="nav-link <?=$nameSection === $section ? '' : 'collapsed'?>" href="<?=$item->path?>">
                        <i class="<?=$item->icon?>"></i>
                        <span><?=ucfirst($item->name)?></span>
                    </a>
                </li>
            <? endif; ?>

        <? endforeach; ?>
    </ul>

</aside>
