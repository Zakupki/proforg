			<ul class="tisers-list">
					<? 
					$cnt=1;
					foreach($dishes as $dish){
					?>
					<li<?=($cnt==count($dishes))?' class="last"':'';?>>
						<a href="<?=$dish->getUrl();?>">
							<?
				            if(strlen($dish->title)>34){
				            $dish->title=mb_substr(strip_tags($dish->title), 0, 31, 'UTF-8')."...";}
				            ?> 
							<span class="text"><?=$dish->title;?></span>
							<span class="img-holder">
								<?
								if(isset($dish->dishtype->dishtypeimage)){
									echo $dish->dishtype->dishtypeimage->asHtmlImage($dish->title);
								}
								?>
							</span>
						</a>
					</li>
					<?
					$cnt++;
					}
					?>
				</ul>