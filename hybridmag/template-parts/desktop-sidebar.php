<?php if ( true == get_theme_mod( 'hybridmag_show_slideout_sb', false ) ) : ?>
	<aside id="hm-slideout-sidebar" class="hm-slideout-sidebar">
		<div class="hm-slideout-top">
			<?php if ( true == get_theme_mod( 'hybridmag_show_logo_on_slideout', true ) ) : ?>
				<div class="hm-slideout-sb-logo">
					<?php hybridmag_site_title(); ?>
				</div>
			<?php endif; ?>
			<button class="hm-slideout-toggle">
				<?php echo hybridmag_the_icon_svg( 'close' ); ?>
			</button>
		</div>

		<?php if ( true === get_theme_mod( 'hybridmag_show_pmenu_onslideout', false ) ) : ?>
			<div class="hm-mobile-menu-main hm-mobile-menu">
				<?php hybridmag_primary_nav_sidebar(); ?>
			</div>
		<?php endif; ?>

		<?php dynamic_sidebar( 'header-1' ); ?>		
	</aside><!-- .hm-slideout-sidebar -->
<?php endif; ?>