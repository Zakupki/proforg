			<div id="sidebar">
				<? foreach($widgets as $widget){
				if(strlen($widget['title'])>0){
				?>
					<div class="sidebar-box">
						<h2><?=$widget['title'];?></h2>
						<p><?=nl2br($widget['preview_text']);?></p>
						<div class="btn-holder right">
							<? if(strlen($widget['link'])>0){?>
							<a href="/<?=trim($widget['link'],'/');?>/" class="green-btn">
								<span>Подробне</span>
							</a>
							<?}elseif(strlen($widget['code'])>0){?>
							<a href="/page/<?=$widget['code'];?>/" class="green-btn">
								<span>Подробнее</span>
							</a>	
							<?}?>								
						</div>
					</div>
				<?
				}
				}?>
			</div>