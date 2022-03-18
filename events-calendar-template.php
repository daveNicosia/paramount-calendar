<?php
/*
Template Name: Events Archive Template
*/
?>

<?php get_header(); ?>
			
	<div id="content">
				
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<?php 
			$active_category = isset( $_GET['category'] ) ? $_GET['category'] : 'all';
			$thumb_id        = get_field( 'header_image' );
			$thumb_url_array = wp_get_attachment_image_src( $thumb_id, 'slider-bg-lg', true );
			$thumb_url       = $thumb_url_array[0];
			?>

			<div class="top-page-header top-page-header-short" style="background-image: url(<?php echo $thumb_url; ?>);">
				<div class="header-bar header-bar-transparent header-bar-1col with-menu">
					<div class="header-bar-inner">
						<div class="header-bar-inner-row">

							<div class="col-one">
								<h1><?php the_title(); ?></h1>
							</div>

							<div class="col-one page-inner-tabs">
								<?php 
								$terms = get_terms( 
									array(
										'taxonomy' => 'event-category',
										'hide_empty' => false,
									) 
								);

								echo '<ul class="menu">';

								if ( isset( $_GET['category'] ) ) {
									if( $active_category == 'all' ) {
										echo '<li data-term-id="all" class="active"><a data-cat="all" class="event-cat-switch">All Events</a></li>';
									}
									else{
										echo '<li data-term-id="all"><a data-cat="all" class="event-cat-switch">All Events</a></li>';
									}
								}
								else{
									echo '<li data-term-id="all" class="active"><a data-cat="all" class="event-cat-switch">All Events</a></li>';
								}

								foreach ( $terms as $term ) {
									if( $term->slug == $active_category ){
										$active_class = "active";
									}
									else{
										$active_class="";
									}

									echo '<li class="'.$active_class.'" data-term-id="'.$term->term_id.'"><a data-cat="'.$term->slug.'" class="event-cat-switch">'.$term->name.'</a></li>';
								}

								echo '</ul>';
								?>
							</div>

						</div>
					</div>
				</div>
			</div>

			<?php
			$todays_date_time 	= isset( $_GET["startdate"] ) ? strtotime( $_GET["startdate"] ) : strtotime('Now');
			$later_date_time 	= isset( $_GET["enddate"] ) ? strtotime( $_GET["enddate"] ) : strtotime("+365 days");
			$total_posts        = sizeof(get_paramount_events($todays_date_time, $later_date_time, -1, 1, $_GET['search'], $active_category));
			$all_events         = get_paramount_events($todays_date_time, $later_date_time, get_paramount_events_per_page(), 1, $_GET['search'], $active_category);
			
			wp_reset_query();
			
			$layout             = isset( $_GET["layout"] ) && $_GET["layout"] == 'grid' ? 'grid' : 'rows'; 
			?>
			
			<div id="event-filters" class="event-filters" data-startdate="<?php echo date( 'm/d/Y', $todays_date_time ); ?>" data-enddate="<?php echo date( 'm/d/Y', $later_date_time ); ?>" data-totalposts="<?php echo $total_posts; ?>" data-searchquery="<?php echo $args['s']; ?>" data-term-id="<?php echo $active_category; ?>">
				<div class="event-filters-inner">
					<div class="event-filters-inner-top">
						<div class="event-filters-inner-top-left">
							<div class="input-group input-daterange">
								<input type="text" class="form-control date-picker-left" value="<?php echo date( 'm/d/Y', $todays_date_time ); ?>">
								<span class="input-group-addon">to</span>
								<input type="text" class="form-control date-picker-right" value="<?php echo date( 'm/d/Y', $later_date_time ); ?>">
							</div>
						</div>
						<div class="event-filters-inner-top-right">
							<form id="event-search-phrase" class="event-search-phrase">
								<input id="archive-search-query-val" type="text" placeholder="Enter Keywords ..." value="<?php echo $args['s']; ?>" />
								<input id="search-events-archive" type="button">
							</form>
						</div>
					</div>
					<div class="event-filters-inner-bottom">
						<div class="event-filters-inner-bottom-left">
							<span class="total-events-label"><span id="total-events-int"><?php echo $total_posts; ?></span> Upcoming Events</span>
						</div>
						<div class="event-filters-inner-bottom-right">
							<span class="display-switcher">
								Display Results as List or Grid 
								<i id="view-list" class="fa fa-list <?php echo $layout != 'grid' ? 'active' : ''; ?>"></i> 
								<i id="view-grid" class="fa fa-th <?php echo $layout == 'grid' ? 'active' : ''; ?>"></i>
							</span>
						</div>
					</div>
				</div>
			</div>

			<div id="event-results" class="event-results">
				<div id="event-results-inner" class="event-results-inner events-<?php echo $layout; ?>" data-layout="<?php echo $layout; ?>" data-equalizer="event-listing" data-equalize-by-row="true" data-equalize-on-stack="true">
					<?php if( count($all_events) > 0 ) : ?>
						<?php display_paramount_events($all_events); ?>
					<?php else: ?>
						<div class="no-events-listed-message"><?php the_field('no_events_message'); ?></div>
					<?php endif; ?>

				</div>
			</div>

			<?php if ( $total_posts > get_paramount_events_per_page() ) : ?>
				<div class="event-archive-load-more">
					<div class="event-archive-load-more-inner">
						<span id="event-archive-load-more-btn" data-page="2" data-total="<?php echo $total_posts; ?>" class="square-button gold-white active">LOAD MORE</span>
					</div>
				</div>
			<?php else: ?>
				<div class="event-archive-load-more">
					<div class="event-archive-load-more-inner">
						<span id="event-archive-load-more-btn" class="square-button gold-white all-loaded">All LOADED</span>
					</div>
				</div>
			<?php endif; ?>

		<?php endwhile; endif; ?>							

	</div> <!-- end #content -->

<?php get_footer(); ?>
