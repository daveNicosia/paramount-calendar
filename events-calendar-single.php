<?php get_header(); ?>
			
	<div id="content">
		
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<?php if ( get_field( 'header_image' ) ) {
				$thumb_id    = get_field( 'header_image' );
			} 
			else {
				$thumb_id    = get_field( 'default_event_image', 'option' );
			}?>

			<?php 
			$thumb_url_array = wp_get_attachment_image_src( $thumb_id, 'slider-bg-lg', true );
			$thumb_url       = $thumb_url_array[0];
			?>

			<div class="top-page-header top-page-header-short" style="background-image: url(<?php echo $thumb_url; ?>);">

				<?php if ( get_field( 'thumbnail_image' ) ) : ?>
					<div class="header-bar header-bar-transparent header-bar-2col">
				<?php else: ?>
					<div class="header-bar header-bar-transparent header-bar-1col">
				<?php endif; ?>

					<div class="header-bar-inner">
						<div class="header-bar-inner-row">

							<?php if ( get_field( 'thumbnail_image' ) ) : ?>

								<div class="col-one">
									<?php 
									$thumb_id        = get_field( 'thumbnail_image' );
									$thumb_url_array = wp_get_attachment_image_src( $thumb_id, 'full', true );
									$thumb_url       = $thumb_url_array[0];
									?>

									<img class="centered" src="<?php echo $thumb_url; ?>" alt="<?php the_title(); ?>" />

								</div>

								<div data-equalizer-watch class="col-two">
									<h1><?php the_title(); ?></h1>

									<?php
									$cat       = get_the_terms( $post->ID, 'event-category' ); 
									$event_cat =  $cat[0]->name;
									?>

									<p class="event-details-top">
										<?php if ( $cat ) : ?>
											<span class="event-header-category">
												<?php echo $event_cat; ?>
											</span> | 
										<?php endif; ?>
										<span class="event-header-date"><?php the_field( 'event_date' ); ?></span> | 
										<span class="event-header-time"><?php the_field( 'event_time' ); ?></span> 
									</p>

									<?php if ( $event_cat == 'Movies' ) : ?>

										<p class="event-details-bottom">
											<?php if ( get_field( 'movie_callout' ) ): ?>
												<span class="event-header-message"><?php the_field( 'movie_callout' ); ?></span> |
											<?php endif ?>
											<?php if ( get_field( 'movie_year' ) ): ?>
												<span class="event-header-year"><?php the_field( 'movie_year' ); ?></span> | 
											<?php endif; ?>
											<?php if ( get_field( 'movie_rating' ) ): ?>
												<span class="event-header-rating"><?php the_field( 'movie_rating' ); ?></span> | 
											<?php endif; ?>
											<?php if ( get_field( 'movie_runtime' ) ): ?>
												<span class="event-header-runtime">Runtime: <?php the_field( 'movie_runtime' ); ?></span> 
											<?php endif; ?>
										</p>

									<?php endif; ?>

								</div>

							<?php else: ?>

								<div class="col-one">

									<h1><?php the_title(); ?></h1>

									<?php
									$cat       = get_the_terms($post->ID, 'event-category'); 
									$event_cat =  $cat[0]->name;
									?>

									<p class="event-details-top">
										<span class="event-header-category">
											<?php echo $event_cat; ?>
										</span> | 
										<span class="event-header-date"><?php the_field( 'event_date' ); ?></span> | 
										<span class="event-header-time"><?php the_field( 'event_time' ); ?></span> 
									</p>

									<?php if ( $event_cat == 'Movies' ) : ?>

										<p class="event-details-bottom">
											<?php if ( get_field( 'movie_callout' ) ): ?>
												<span class="event-header-message"><?php the_field( 'movie_callout' ); ?></span> |
											<?php endif ?> 
											<span class="event-header-year"><?php the_field( 'movie_year' ); ?></span> | 
											<span class="event-header-rating"><?php the_field( 'movie_rating' ); ?></span> | 
											<span class="event-header-runtime">Runtime: <?php the_field( 'movie_runtime' ); ?></span> 
										</p>

									<?php endif; ?>
								</div>

							<?php endif; ?>

						</div>
					</div>
				</div>
					
				<div class="header-img-overlay"></div>
					
			</div>

			<div class="event-listing-content">

				<div class="column-two-thirds">

					<?php if ( get_field( 'feature_text' ) ) : ?>
						<div class="feature-content event-content">
							<div class="event-info-section">
								<?php the_field( 'feature_text' ); ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( get_field( 'program' ) ) : ?>
						<div class="feature-content">
							<h2>Program</h2>
							<div class="event-info-section event-program">
								<?php the_field( 'program' ); ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( get_field( 'reviews' ) ) : ?>
						<div class="feature-content">
							<h2>Reviews</h2>
							<div class="event-info-section event-reviews">
								<?php the_field( 'reviews' ); ?>
							</div>
						</div>
					<?php endif; ?>

				</div>

				<div class="column-one-third event-sidebar">

					<a class="square-button gold-black buy-tickets" target="_blank" href="<?php the_field( 'tessitura_ticket_link' ); ?>">Buy Tickets</a>

					<div class="add-to-calendar">

						<?php
						$date               = get_post_meta( get_the_ID(), 'showtimes_0_showtime_date', true );
						$time               = get_post_meta( get_the_ID(), 'showtimes_0_showtime_time', true );
						$dateTime           = DateTime::createFromFormat( "Ymd", $date );
						$dateTime_formatted = $dateTime->format( 'm/d/Y' ).' '.$time;
						?>

						<div title="Add to Calendar" class="addeventatc">
							Add to Calendar
							<span class="start"><?php echo $dateTime_formatted; ?></span>
							<span class="timezone">America/New_York</span>
							<span class="title"><?php the_title(); ?></span>
							<span class="description"><?php the_title(); ?></span>
							<span class="location">215 East Main Street, Charlottesville, Virginia, 22902</span>
							<span class="organizer">The Paramount Theater of Charlottesville</span>
							<span class="organizer_email">info@theparamount.net</span>
							<span class="date_format">MM/DD/YYYY</span>
							<span class="client">ayRsfxAFczszDfJQlmHe23157</span>
						</div>

					</div>

					<div class="event-sidebar-section event-prices">

						<h4>Ticket Prices</h4>

						<?php if( have_rows( 'ticket_prices' ) ): ?>

							<ul class="ticket-prices">
						
							<?php while ( have_rows( 'ticket_prices' ) ) : the_row(); ?>
								<li>
									<?php if ( get_sub_field( 'ticket_price_amount' ) ) { echo '$'; } ?>
									<?php echo get_sub_field( 'ticket_price_amount' ) . ' ' . get_sub_field( 'ticket_price_label' ); ?>
								</li>
							<?php endwhile; ?>

							</ul>
						
						<?php endif; ?>

					</div>

					<div class="event-sidebar-section event-schedule">

						<h4>Schedule of Events</h4>

						<?php if( have_rows( 'showtimes' ) ): ?>

							<ul class="ticket-showtimes">
							
								<?php while ( have_rows( 'showtimes' ) ) : the_row(); ?>
							
									<li>
									<?php 
									$date     = get_sub_field( 'showtime_date' );
									$dateTime = DateTime::createFromFormat( "Ymd", $date );

									?>
										<span class="showtime-date"><?php echo $dateTime->format( 'F j, Y' ); ?></span> | 
										<span class="showtime-time"><?php the_sub_field( 'showtime_time' ); ?></span>
									</li>
							
								<?php endwhile; ?>

							</ul>

							<a href="<?php the_field( 'tessitura_ticket_link' ); ?>" class="learn-more-link whitebg" target="blank">buy tickets</a>
							
						<?php endif; ?>

					</div>

					<?php if( have_rows( 'movie_cast' ) ): ?>
						<div class="event-sidebar-section event-cast">
							<h4>Cast</h4>
							<ul>
								<?php while ( have_rows( 'movie_cast' ) ) : the_row(); ?>
									<li><?php echo '<strong>'.get_sub_field( 'cast_member' ) . '</strong> as "' . get_sub_field( 'character_name' ).'"'; ?></li>
								<?php endwhile; ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php if( get_field( 'composer' ) ): ?>
						<div class="event-sidebar-section event-composer">
							<h4>Composer</h4>
							<p><?php the_field( 'composer' ) ?></p>
						</div>
					<?php endif; ?>

					<?php if( have_rows( 'musicians' ) ): ?>
						<div class="event-sidebar-section event-musicians">
							<h4>Musicians</h4>
							<ul>
								<?php while ( have_rows( 'musicians' ) ) : the_row(); ?>
									<li><?php the_sub_field( 'musician_name' ); ?></li>
								<?php endwhile; ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php if( have_rows( 'performers' ) ): ?>
						<div class="event-sidebar-section event-performers">
							<h4>Performers</h4>
							<ul>
								<?php while ( have_rows( 'performers' ) ) : the_row(); ?>
									<li><?php the_sub_field( 'performer_name' ); ?></li>
								<?php endwhile; ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php if( have_rows( 'presented_by' ) || have_rows( 'event_sponsors' ) ): ?>

						<div class="event-sidebar-section event-presentedby">

							<?php if( have_rows( 'presented_by' ) ): ?>
								<h4>Presented By</h4>
								<div class="event-presentedby-logos">
										<ul>
										<?php while ( have_rows( 'presented_by' ) ) : the_row(); ?>
											<?php 
											$thumb_id        = get_sub_field( 'presentedby_logo' );
											$thumb_url_array = wp_get_attachment_image_src( $thumb_id, 'full', true );
											$thumb_url       = $thumb_url_array[0];
											?>
											<li>
												<?php if ( get_sub_field( 'link_url' ) ) : ?>
													<a href="" target="_blank">
												<?php endif; ?>
												<img src="<?php echo $thumb_url; ?>" alt="<?php the_title(); ?>" />
												<?php if ( get_sub_field( 'link_url' ) ) : ?>
													</a>
												<?php endif; ?>
											</li>
										<?php endwhile; ?>
									</ul>
								</div>
							<?php endif; ?>

							<?php if( have_rows( 'event_sponsors' ) ): ?>

								<div class="event-presentedby-sponsors">
									<h4>Thank you to our event sponsors:</h4>
									<ul>
										<?php while ( have_rows( 'event_sponsors' ) ) : the_row(); ?>
											<?php 
											$thumb_id        = get_sub_field( 'sponsor_logo' );
											$thumb_url_array = wp_get_attachment_image_src( $thumb_id, 'full', true );
											$thumb_url       = $thumb_url_array[0];
											?>
											<?php if ( get_sub_field( 'link_url' ) ) : ?>
												<a href="" target="_blank">
											<?php endif; ?>
											<img src="<?php echo $thumb_url; ?>" alt="<?php the_title(); ?>" /></li>
											<?php if ( get_sub_field( 'link_url' ) ) : ?>
												</a>
											<?php endif; ?>
										<?php endwhile; ?>
									</ul>
								</div>

							<?php endif; ?>

						</div>

					<?php endif; ?>

				</div>

			</div>
			
		<?php endwhile; endif; ?>							

	</div> <!-- end #content -->

<?php get_footer(); ?>
