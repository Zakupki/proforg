<ul class="partneer-list">
	<? foreach($partners as $partner){?>
		<li>
			<? if($partner['url']){?>
			<a href="http://<?=str_replace(array('http://','https://'),'',$partner['url']);?>">
			<?}?>
				<?=$partner->image->asHtmlImage($partner->title);?>
				<!--<img src="/images/img33.png" width="170" height="140" alt="image description" />-->
			<? if($partner['url']){?>
			</a>
			<?}?>
		</li>
	<?}?>
</ul>
		