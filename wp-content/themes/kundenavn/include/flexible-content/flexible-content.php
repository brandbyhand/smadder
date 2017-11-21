<?php
/**
 *
 * Flexible content field markup.
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// check if the flexible content field has rows of data
if( have_rows('FLEXIBLE_FIELD_NAVN') ): ?>

	<div class="flexible-field-wrapper">

        <?php // loop through the rows of data
        while ( have_rows('FLEXIBLE_FIELD_NAVN') ) : the_row();

			/*--========================================
            =            FLEX LÆSELIGT NAVN            =
            =========================================--*/           
            if( get_row_layout() == 'FLEX_NAVN' ): ?>

            <div class="flexible-inner-section bbh-inner-section">
                    <div class="grid-container">
                        <div class="row">
                            <div class="col-sm-12">
                                
                            </div> <!-- Content Col -->
                        </div>  <!-- Row -->
                    </div> <!-- Grid container -->
                </div> <!-- Flexible inner -->
            <?php
            
            /*--========================================
            =            FLEX LÆSELIGT NAVN            =
            =========================================--*/
            elseif( get_row_layout() == 'FLEX_NAVN' ):
            	?>
            	<div class="flexible-inner-section bbh-inner-section">
                    <div class="grid-container">
                        <div class="row">
                            <div class="col-sm-12">
            					
            				</div> <!-- Content Col -->
            			</div>	<!-- Row -->
            		</div> <!-- Grid container -->
            	</div> <!-- Flexible inner -->
    		<?php
            endif;
        endwhile; // END while have_rows() ?>
	</div> <?php // END div.flexible-field-wrapper ?>
<?php else :

    // no layouts found

endif;

?>


