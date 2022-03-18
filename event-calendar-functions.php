<?php
/**
 * Gets the global setting, used in the calendar to determine if the load more button should
 * be activ or not.
 */
function get_paramount_events_per_page() {
	return get_field( 'events_per_page','options' ) ? get_field( 'events_per_page','options' ) : get_option( 'posts_per_page' );
}

/**
 * Gets the events that match the calendar's filter & date criteria
 */
function get_paramount_events( $from_date, $to_date, $per_page = -1, $page = 1, $search = '', $category = 'all', $additonal = [] ) {
	
	$args = array( 
    	'post_type'      => 'event',
    	'posts_per_page' => $per_page,
    	'offset'	     => ($per_page > 0 ? (($page - 1) * $per_page) : 0),
		'fields'         => 'ids',
		'post_status'    => 'publish',
		'meta_key'		 => 'event_date',
		'meta_query'     => array(
	        array(
	            'key'        => 'event_date',
	            'value'      => array(date("Ymd", $from_date), date("Ymd", $to_date)),
	            'compare'    => 'BETWEEN',
	            'type'       => 'DATE'
	        )
	    ),
	    'orderby'		 => 'meta_value_num',
		'order'			 => 'ASC',
		's'              => $search,
	);

	if ( $category != 'all' ){
		$args['tax_query'] = array(
			array(
				'taxonomy'   => 'event-category',
				'field'      => 'slug',
				'terms'      => array( $category ),
				'operator'   => 'IN'
			)
		);
	}

	$args = array_merge( $args, $additonal );
	return get_posts( $args );
}

/**
 * Prints the events in the calendar to teh screen
 */
function display_paramount_events( $all_events, $last_month = null ) {
	global $post;
	$m=1;

	foreach ( $all_events as $post ) : setup_postdata( $post );
		$event_ID = $post->ID;
		$date = get_field('event_date');
		$dateTime = DateTime::createFromFormat( "m/j/Y", $date );

		if ( $last_month != $dateTime->format( 'Y-m' )) {

			if ( $m % 4 != 1 ) {
				echo "</div>";
				$m = 1;
			}

			$last_month = $dateTime->format( 'Y-m' );
			echo '<span class="event-archive-month" data-format="' . $dateTime->format( 'Y-m' ) . '"><span>'.$dateTime->format( 'F' ) . '</span></span>';

			if ( $m % 4 != 1 ) {
				echo '<div class="row">';
			}

		}
		?>

		<?php 
		if ( $m % 4 == 1 ) {  
	         echo '<div class="row">';

	         $n++;
	    }
	    ?>

			<div class="event-archive-box" data-exclude="<?php echo get_the_id(); ?>">

				<div class="event-archive-box-left" data-equalizer-watch="event-listing">

					<?php
					if ( get_field( 'thumbnail_image' ) ) 
					{
						$thumb_id     = get_field( 'thumbnail_image' );
					}
					else 
					{
						if ( get_field( 'header_image' ) ) 
						{
							$thumb_id = get_field( 'header_image', $event_ID );
						} 
						else {
							$thumb_id = get_field( 'default_event_image', 'option' );
						}
					}

					$thumb_url_array  = wp_get_attachment_image_src( $thumb_id, 'event-archive-thumb', true );
					$thumb_url        = $thumb_url_array[0];

					?>

					<a href="<?php the_permalink(); ?>">
						<img class="event-archive-thumb" src="<?php echo $thumb_url; ?>" alt="" />
					</a>

					<a href="<?php the_permalink(); ?>">
						<h3><?php the_title(); ?></h3>
					</a>

					<p class="event-details-top">

						<?php
						$cat = get_the_terms( $post->ID, 'event-category' ); 
						if( count($cat) > 0):
						$event_cat =  $cat[0]->name;
						?>
						<span class="event-header-category">
							<?php echo $event_cat; ?>
						</span> | 
						<?php endif; ?>
						<span class="event-header-date"><?php the_field( 'event_date' ); ?></span> | 
						<span class="event-header-time"><?php the_field( 'event_time' ); ?></span> | 

						<?php if( have_rows( 'ticket_prices' ) ): $i=0; ?>
						
							<?php while ( have_rows( 'ticket_prices' ) ) : the_row(); ?>
								<?php if ( $i > 0 ) { echo ', '; } ?>
								<span class="event-header-price"><?php if ( get_sub_field( 'ticket_price_amount' ) ) { echo '$'; } ?><?php echo get_sub_field( 'ticket_price_amount' ) . ' ' . get_sub_field( 'ticket_price_label' ); ?></span>
							<?php $i++; endwhile; ?>
							
						<?php endif; ?>
						 
					</p>

					<?php if ( $event_cat == 'Movies' ) : ?>

						<p class="event-details-bottom">
							<?php if ( get_field( 'movie_callout' ) ): ?>
								<span class="event-header-message"><?php the_field( 'movie_callout' ); ?></span><br />
							<?php endif ?>
							<span class="event-header-year"><?php the_field( 'movie_year' ); ?></span> | 
							<span class="event-header-rating"><?php the_field('movie_rating'); ?></span> | 
							<span class="event-header-runtime">Runtime: <?php the_field( 'movie_runtime' ); ?></span> 
						</p>

					<?php endif; ?>

				</div>

				<div class="event-archive-box-right">
					<div class="event-archive-box-right-inner">
						<a class="square-button gold-white buy-tickets" target="_blank" href="<?php the_field( 'tessitura_ticket_link' ); ?>">Buy Tickets</a>
						<a href="<?php the_permalink(); ?>" class="learn-more-link gold more-info">more info</a> <a href="<?php the_permalink(); ?>" class="learn-more-link gold show-dates">show all dates</a>
					</div>
				</div>
				<div class="clear"></div>
			</div>

			<?php
			if ( $m % 4 == 0 ) {  
		         echo '</div>';
		    }
		    ?>

	<?php $m++; endforeach; if ( $m % 4 != 1 ) echo "</div>"; ?>

    <?php
	wp_reset_query();
}

/**
 * Enables the ajax for the calendar filter and load more button on the backend
 */
add_action( 'wp_ajax_load_more_events', 'load_more_events' );

/**
 * Enables the ajax for the calendar filter and load more button on the frontend
 */
add_action( 'wp_ajax_nopriv_load_more_events', 'load_more_events' );

/**
 * Loads the next page of events into the calendar when the load more button is clicked
 */
function load_more_events() {
	$all_events = get_paramount_events(
			strtotime( $_POST['data_startdate'] ), 
			strtotime( $_POST['data_enddate'] ), 
			get_paramount_events_per_page(), 
			$_POST['page'], 
			$_POST['data_searchquery'], 
			$_POST['data_category'] 
        );
	display_paramount_events( $all_events, $_POST['last_month'] );
    die();
}

/**
 * Removes an event from the front page's events slick slider
 */
function removeElementWithValue($array, $key, $value){
    foreach($array as $subKey => $subArray){
        if($subArray[$key] == $value){
            unset($array[$subKey]);
        }
    }
    return $array;
}


/**
 * set expired events to draft & remove form homepage slider.
 */
add_action( 'wp', 'draft_old_events' );
function draft_old_events() {

	$old_event_ids = array();
	$args          = array( 'post_type'=> 'event', 'posts_per_page' => -1, 'post_status' => 'publish' );
	$the_query     = get_posts( $args );

	foreach( $the_query as $single_post ) {

		$id               = $single_post->ID;
		$event_close_date = get_post_meta( $id, 'event_date', true );

		if( $event_close_date != '' ){
			$today = date( "Ymd" );
			if($event_close_date < $today){
				$update_post = array(
					'ID' 			=> $id,
					'post_status'	=>	'draft',
					'post_type'	=>	'event' 
                );
				wp_update_post( $update_post );
				$old_event_ids[] = $id;
			}	
		}
	}

	$frontpage_id = get_option( 'page_on_front' );
	$field_key    = 'featured_events_slider';

	foreach ( $old_event_ids as $old_event ) {

		$events_slider = $events_slider - 1;
		$value         = get_field( $field_key, $frontpage_id );
		$value         = removeElementWithValue( $value, 'featured_event', $old_event );
		update_field( $field_key, $value, $frontpage_id );

	}

}
